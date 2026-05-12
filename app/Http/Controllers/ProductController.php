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
                $path = $image->store('product-images', 'public');
                $product->images()->create(['path' => $path]);
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully!');
    }

    public function show($id)
    {
        return Product::with('category')->findOrFail($id);
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
                $path = $image->store('product-images', 'public');
                $product->images()->create(['path' => $path]);
            }
        }

        return response()->json([
            'message' => 'Product updated successfully',
            'data' => $product->load(['category', 'images'])
        ]);
    }

    public function destroy($id)
    {
        Product::destroy($id);
        return response()->json(['message' => 'Deleted']);
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
                'image' => optional($product->images->first())->path ? asset('storage/' . $product->images->first()->path) : null,
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