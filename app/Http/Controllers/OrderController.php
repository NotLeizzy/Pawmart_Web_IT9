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
        abort(404);
    }

    public function edit($id)
    {
        abort(404);
    }

    public function store(Request $request)
    {
        $order = Order::create([
            'user_id' => $request->user_id,
            'total_amount' => 0,
            'status' => $request->status ?? 'pending',
            'delivery_address' => $request->delivery_address
        ]);

        $total = 0;

        if ($request->products) {
            foreach ($request->products as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price']
                ]);

                $total += $item['price'] * $item['quantity'];
            }
        }

        if ($request->pets) {
            foreach ($request->pets as $petData) {
                PetOrder::create([
                    'order_id' => $order->id,
                    'pet_id' => $petData['pet_id'],
                    'price' => $petData['price']
                ]);

                Pet::where('id', $petData['pet_id'])
                    ->update(['status' => 'sold']);

                $total += $petData['price'];
            }
        }

        if ($request->status === 'completed') {
            $this->applyStockOutForOrder($order);
        }

        $order->update(['total_amount' => $total]);

        return $order->load(['items.product', 'petOrders.pet']);
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

        $previousStatus = $order->status;
        $order->update($request->all());

        if ($request->status === 'completed' && $previousStatus !== 'completed') {
            $this->applyStockOutForOrder($order);
        }

        return $order;
    }

    private function applyStockOutForOrder(Order $order): void
    {
        DB::transaction(function () use ($order) {
            $order->load('items.product');

            foreach ($order->items as $item) {
                $product = $item->product;

                if (! $product || $product->stock < $item->quantity) {
                    continue;
                }

                $product->decrement('stock', $item->quantity);

                StockMovement::create([
                    'product_id' => $product->id,
                    'order_id' => $order->id,
                    'type' => 'out',
                    'quantity' => $item->quantity,
                    'reference' => 'sale',
                    'user_id' => Auth::id(),
                ]);
            }
        });
    }

    public function destroy($id)
    {
        Order::destroy($id);
        return response()->json(['message' => 'Deleted']);
    }
}