<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PetOrder;
use App\Models\Pet;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        if (request()->is('admin/*')) {
            $orders = Order::with(['items.product', 'petOrders.pet', 'payment', 'user'])
                ->latest()
                ->paginate(10);

            return view('admin.orders.index', compact('orders'));
        }

        // Customer view response
        $orders = Order::where('user_id', Auth::id())
            ->with(['items.product', 'petOrders.pet', 'payment'])
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('products.index')->with('error', 'Your cart is empty. Please add items before checking out.');
        }

        $total = array_reduce($cart, function ($sum, $item) {
            return $sum + ($item['price'] * $item['quantity']);
        }, 0);

        return view('orders.create', compact('cart', 'total'));
    }

    public function edit($id)
    {
        abort(404);
    }

    public function store(Request $request)
    {
        $request->validate([
            'delivery_address' => 'required|string|max:255',
        ]);

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('products.index')->with('error', 'Your cart is empty.');
        }

        $total = array_reduce($cart, function ($sum, $item) {
            return $sum + ($item['price'] * $item['quantity']);
        }, 0);

        // Create the Order
        $order = Order::create([
            'user_id' => Auth::id(),
            'total_amount' => $total,
            'status' => 'pending',
            'delivery_address' => $request->delivery_address
        ]);

        // Create Order Items from Cart
        foreach ($cart as $item) {
            if (isset($item['type']) && $item['type'] === 'pet') {
                PetOrder::create([
                    'order_id' => $order->id,
                    'pet_id' => $item['pet_id'],
                    'price' => $item['price']
                ]);
                
                Pet::where('id', $item['pet_id'])->update(['status' => 'sold']);
            } else {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price']
                ]);
            }
        }

        // Clear the cart
        session()->forget('cart');

        // Redirect to Payment Page
        return redirect()->route('payments.create', ['order' => $order->id])->with('success', 'Order created! Please complete your payment.');
    }

    public function show($id)
    {
        $order = Order::with(['items.product', 'petOrders.pet', 'payment'])
            ->findOrFail($id);

        // Check if user is admin or the order owner
        if (Auth::user()->role !== 'admin' && $order->user_id !== Auth::id()) {
            abort(403);
        }

        return view('orders.show', compact('order'));
    }

    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->update($request->all());

        return $order;
    }

    public function destroy($id)
    {
        Order::destroy($id);
        return response()->json(['message' => 'Deleted']);
    }
}