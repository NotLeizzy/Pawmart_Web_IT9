<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StockMovement;
use App\Models\Product;

class StockMovementController extends Controller
{
    // 📊 Show all stock movements
    public function index()
    {
        $movements = StockMovement::with(['product', 'user'])->latest()->paginate(20);
        $totalStockIn = StockMovement::where('type', 'in')->sum('quantity');
        $totalStockOut = StockMovement::where('type', 'out')->sum('quantity');
        $products = Product::orderBy('name')->get();
        
        return view('admin.stock-movements.index', compact('movements', 'totalStockIn', 'totalStockOut', 'products'));
    }

    // ➕ Add stock (IN)
    public function stockIn(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'quantity'   => 'required|integer|min:1',
            'supplier'   => 'nullable|string|max:255',
        ]);

        $product = Product::findOrFail($request->product_id);

        // Update product stock
        $product->increment('stock', $request->quantity);

        // Log movement
        $movement = StockMovement::create([
            'product_id' => $product->id,
            'type'       => 'in',
            'quantity'   => $request->quantity,
            'reason'     => $request->notes ?? 'Manual Stock In',
            'supplier'   => $request->supplier,
            'user_id'    => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Stock added successfully!');
    }

    // ➖ Deduct stock (OUT)
    public function stockOut(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);

        // Prevent negative stock
        if ($product->stock < $request->quantity) {
            return redirect()->back()->with('error', 'Not enough stock available!');
        }

        // Update product stock
        $product->decrement('stock', $request->quantity);

        // Log movement
        $movement = StockMovement::create([
            'product_id' => $product->id,
            'type' => 'out',
            'quantity' => $request->quantity,
            'reason' => $request->notes ?? 'Manual Stock Out',
            'user_id' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Stock deducted successfully!');
    }

    // 🔍 View single movement
    public function show($id)
    {
        return StockMovement::with(['product', 'user'])->findOrFail($id);
    }
}