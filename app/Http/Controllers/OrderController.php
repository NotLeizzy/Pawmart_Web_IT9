<?php

namespace App\Http\Controllers;

use App\Helpers\HeapQueue;
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
    public function index(Request $request)
    {
        // ADMIN VIEW
        if ($request->is('admin/*')) {

            $orders = Order::with(['items.product', 'petOrders.pet', 'payment', 'user'])

                ->when($request->search, function ($query) use ($request) {

                    $search = $request->search;

                    $query->where(function ($q) use ($search) {

                        $q->where('id', $search)
                            ->orWhereHas('user', function ($userQuery) use ($search) {
                                $userQuery->where('name', 'LIKE', "%{$search}%");
                            });
                    });
                })

                ->when($request->status, function ($query) use ($request) {
                    $query->where('status', $request->status);
                })

                ->latest()
                ->paginate(10)
                ->withQueryString();

            return view('admin.orders.index', compact('orders'));
        }

        // CUSTOMER VIEW
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
        $order = Order::with(['items.product', 'petOrders.pet', 'payment', 'user'])
            ->findOrFail($id);

        // Check if user is admin or the order owner
        if (Auth::user()->role !== 'admin' && $order->user_id !== Auth::id()) {
            abort(403);
        }

        if (request()->is('admin/*')) {
            return view('admin.orders.show', compact('order'));
        }

        return view('orders.show', compact('order'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered',
        ]);

        $order = Order::findOrFail($id);
        $order->update(['status' => $request->status]);

        // If the order is delivered, automatically mark the payment as paid
        if ($request->status === 'delivered' && $order->payment) {
            $order->payment->update(['status' => 'paid']);
        }

        return redirect()->route('admin.orders.index')->with('success', "Order #$id status updated to " . ucfirst($request->status) . ".");
    }

    public function destroy($id)
    {
        Order::destroy($id);
        return response()->json(['message' => 'Deleted']);
    }

    /**
     * Build a priority queue from orders for prioritized processing.
     *
     * @param array<int, \App\Models\Order> $orders
     * @return HeapQueue
     */
    protected function buildOrderPriorityQueue(array $orders): HeapQueue
    {
        $queue = new HeapQueue();

        foreach ($orders as $order) {
            $queue->insert($order, $this->mapOrderStatusToPriority($order->status));
        }

        return $queue;
    }

    protected function mapOrderStatusToPriority(string $status): int
    {
        return match ($status) {
            'pending' => 1,
            'processing' => 2,
            'shipped' => 3,
            'delivered' => 4,
            default => 5,
        };
    }
}

