<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function index()
    {
        // Check if this is an admin request
        if (request()->is('admin/*')) {
            $payments = Payment::with('order.user')
                ->latest()
                ->paginate(10);
            
            return view('admin.payments.index', compact('payments'));
        }
        
        // API response
        return Payment::with('order')->get();
    }

    public function create(Request $request)
    {
        $orderId = $request->query('order');
        
        if (!$orderId) {
            return redirect()->route('orders.index')->with('error', 'No order specified for payment.');
        }

        $order = \App\Models\Order::where('user_id', \Illuminate\Support\Facades\Auth::id())
            ->findOrFail($orderId);

        // If already paid, redirect
        if ($order->status !== 'pending') {
            return redirect()->route('orders.show', $order->id)->with('info', 'This order has already been processed.');
        }

        return view('payments.create', compact('order'));
    }

    public function edit($id)
    {
        abort(404);
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'payment_method' => 'required|string|in:Credit Card,PayPal,Cash on Delivery,GCash',
        ]);

        $order = \App\Models\Order::where('user_id', \Illuminate\Support\Facades\Auth::id())
            ->findOrFail($request->order_id);

        if ($order->status !== 'pending') {
            return redirect()->route('orders.show', $order->id)->with('error', 'This order cannot be paid for.');
        }

        // Create Payment
        $payment = Payment::create([
            'order_id' => $order->id,
            'amount' => $order->total_amount,
            'status' => 'paid',
            'payment_method' => $request->payment_method
        ]);

        // Update Order Status
        $order->update(['status' => 'processing']);

        // Deduct Stock and Record Movement
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
                    'reason' => 'sale',
                    'user_id' => Auth::id(),
                ]);
            }
        });

        return redirect()->route('orders.index')->with('success', 'Payment successful! Your order is now processing.');
    }

    public function show($id)
    {
        $payment = Payment::with(['order.user', 'order.items.product'])->findOrFail($id);

        if (request()->is('admin/*')) {
            return view('admin.payments.show', compact('payment'));
        }

        return $payment;
    }

    public function update(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);
        $payment->update($request->all());
        return $payment;
    }

    public function destroy($id)
    {
        Payment::destroy($id);
        return response()->json(['message' => 'Deleted']);
    }
}