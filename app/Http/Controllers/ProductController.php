<?php

namespace App\Http\Controllers;

use App\Helpers\BinarySearch;
use App\Helpers\MergeSort;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        if (request()->is('admin/*')) {
            $products = Product::with('category')
                ->when(request('search'), function ($query) {
                    $query->where('name', 'like', '%' . request('search') . '%');
                })
                ->paginate(10);

            $categories = Category::all();

            return view('admin.products.index', compact('products', 'categories'));
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
        if (request()->is('admin/*')) {
            $product = Product::with(['category', 'images'])->findOrFail($id);
            return redirect()->route('admin.products.index');
        }

        $product = Product::with(['category', 'images'])->findOrFail($id);
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

        $product = Product::findOrFail($validated['product_id']);

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

    /**
     * Sort a list of product names using the MergeSort helper.
     *
     * @param array<int, string> $productNames
     * @return array<int, string>
     */
    protected function sortProductNames(array $productNames): array
    {
        return MergeSort::sort($productNames);
    }

    /**
     * Search a sorted list of product names using the BinarySearch helper.
     *
     * @param array<int, string> $productNames
     * @param string $target
     * @return int|null
     */
    protected function searchProductName(array $productNames, string $target): ?int
    {
        return BinarySearch::search($productNames, $target);
    }
}
