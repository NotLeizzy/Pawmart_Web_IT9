<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        if (request()->is('admin/*')) {
            $allProducts = Product::with(['category', 'images'])->get();

            // 1. Build AVL Tree
            $productBst = new \App\Services\ProductBST();
            foreach ($allProducts as $p) {
                $productBst->insert($p->name, $p);
            }

            // 2. Perform Search using our AVL Tree
            $searchQuery = request('search');
            $bstStart = hrtime(true);
            $filteredProducts = $productBst->search($searchQuery);
            $bstEnd = hrtime(true);
            $productSearchTime = round(($bstEnd - $bstStart) / 1_000_000.0, 5); // ms

            // 3. Sort products using MergeSorter
            $sortBy = request('sort', 'name'); // default sorting by name
            if ($sortBy === 'price') {
                $filteredProducts = \App\Services\MergeSorter::sort($filteredProducts, 'price');
            } elseif ($sortBy === 'name') {
                $filteredProducts = \App\Services\MergeSorter::sort($filteredProducts, 'name');
            }

            // 4. Manually paginate the array so Blade pagination doesn't break
            $currentPage = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage();
            $perPage = 10;
            $currentItems = array_slice($filteredProducts, ($currentPage - 1) * $perPage, $perPage);
            $products = new \Illuminate\Pagination\LengthAwarePaginator(
                $currentItems,
                count($filteredProducts),
                $perPage,
                $currentPage,
                ['path' => \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPath(), 'query' => request()->query()]
            );

            $categories = Category::all();

            return view('admin.products.index', compact('products', 'categories', 'productSearchTime'));
        }

        if (request()->wantsJson()) {
            return Product::with(['category', 'images'])->get();
        }

        $products = Product::with(['category', 'images'])->paginate(12);

        return view('products.index', compact('products'));
    }

    public function create()
    {
        if (! request()->is('admin/*')) {
            abort(404);
        }

        $categories = Category::all();

        return view('admin.products.create', compact('categories'));
    }

    public function edit($id)
    {
        if (! request()->is('admin/*')) {
            abort(404);
        }

        $product = Product::findOrFail($id);
        $categories = Category::all();

        return view('admin.products.create', compact('product', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0|max:99999999',
            'stock' => 'required|integer|min:0|max:99999999',
            'category_id' => 'required|exists:categories,id',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
        ]);

        $product = Product::create($validated);

        if ($product->stock > 0) {
            \App\Models\StockMovement::create([
                'product_id' => $product->id,
                'type' => 'in',
                'quantity' => $product->stock,
                'reason' => 'initial_stock',
                'user_id' => \Illuminate\Support\Facades\Auth::id(),
            ]);
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                // Check if GD library is available for compression
                if (function_exists('imagecreatefromstring')) {
                    $img = @imagecreatefromstring(file_get_contents($image->getRealPath()));
                    if ($img) {
                        $width = imagesx($img);
                        $height = imagesy($img);
                        $maxDim = 800;
                        if ($width > $maxDim || $height > $maxDim) {
                            $ratio = $maxDim / max($width, $height);
                            $img = imagescale($img, round($width * $ratio), round($height * $ratio));
                        }
                        ob_start();
                        imagejpeg($img, null, 80);
                        $compressedData = ob_get_clean();
                        imagedestroy($img);
                        $base64Image = 'data:image/jpeg;base64,' . base64_encode($compressedData);
                    } else {
                        $base64Image = 'data:' . $image->getMimeType() . ';base64,' . base64_encode(file_get_contents($image->getRealPath()));
                    }
                } else {
                    // Fallback to raw Base64 if GD is missing
                    $base64Image = 'data:' . $image->getMimeType() . ';base64,' . base64_encode(file_get_contents($image->getRealPath()));
                }
                $product->images()->create(['path' => $base64Image]);
            }
        }

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Product created successfully!',
                'data' => $product->load(['category', 'images']),
            ]);
        }

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully!');
    }

    public function show($id)
    {
        $allProducts = Product::with(['category', 'images'])->get();
        $hashTable = new \App\Services\CustomHashTable(count($allProducts) * 2 + 1);
        foreach ($allProducts as $p) {
            $hashTable->insert($p->id, $p);
        }

        $product = $hashTable->search($id);

        if (!$product) {
            abort(404);
        }

        if (request()->is('admin/*')) {
            return redirect()->route('admin.products.index');
        }

        return view('products.show', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0|max:99999999',
            'stock' => 'required|integer|min:0|max:99999999',
            'category_id' => 'required|exists:categories,id',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
        ]);

        $product->update($validated);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                if (function_exists('imagecreatefromstring')) {
                    $img = @imagecreatefromstring(file_get_contents($image->getRealPath()));
                    if ($img) {
                        $width = imagesx($img);
                        $height = imagesy($img);
                        $maxDim = 800;
                        if ($width > $maxDim || $height > $maxDim) {
                            $ratio = $maxDim / max($width, $height);
                            $img = imagescale($img, round($width * $ratio), round($height * $ratio));
                        }
                        ob_start();
                        imagejpeg($img, null, 80);
                        $compressedData = ob_get_clean();
                        imagedestroy($img);
                        $base64Image = 'data:image/jpeg;base64,' . base64_encode($compressedData);
                    } else {
                        $base64Image = 'data:' . $image->getMimeType() . ';base64,' . base64_encode(file_get_contents($image->getRealPath()));
                    }
                } else {
                    $base64Image = 'data:' . $image->getMimeType() . ';base64,' . base64_encode(file_get_contents($image->getRealPath()));
                }
                $product->images()->create(['path' => $base64Image]);
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully!');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // Log a stock-out for any remaining stock before deletion
        if ($product->stock > 0) {
            \App\Models\StockMovement::create([
                'product_id' => $product->id,
                'type'       => 'out',
                'quantity'   => $product->stock,
                'reason'     => 'Product deleted: ' . $product->name,
                'user_id'    => \Illuminate\Support\Facades\Auth::id(),
            ]);
        }

        $product->delete();

        if (request()->wantsJson()) {
            return response()->json(['message' => 'Product deleted successfully.']);
        }

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }

    public function addToCart(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $allProducts = Product::all();
        $hashTable = new \App\Services\CustomHashTable(count($allProducts) * 2 + 1);
        foreach ($allProducts as $p) {
            $hashTable->insert($p->id, $p);
        }

        $product = $hashTable->search($validated['product_id']);

        if (!$product) {
            return response()->json([
                'message' => 'Product not found.'
            ], 404);
        }

        if ($validated['quantity'] > $product->stock) {
            return response()->json([
                'message' => 'Quantity exceeds available stock.'
            ], 422);
        }

        $cart = session()->get('cart', []);
        $cartKey = 'product_' . $product->id;

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $validated['quantity'];

            if ($cart[$cartKey]['quantity'] > $product->stock) {
                $cart[$cartKey]['quantity'] = $product->stock;
            }
        } else {
            $cart[$cartKey] = [
                'type' => 'product',
                'product_id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $validated['quantity'],
                'image' => optional($product->images->first())->path,
                'stock' => $product->stock,
            ];
        }

        session()->put('cart', $cart);

        return response()->json([
            'message' => 'Product added to cart!',
            'cart_count' => array_sum(array_column($cart, 'quantity'))
        ]);
    }
}