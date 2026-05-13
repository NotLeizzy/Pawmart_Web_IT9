@extends('layouts.app')

@section('title', "Order #{$order->id}")
@section('content')

<div class="mb-4">
    <a href="{{ route('orders.index') }}" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-left"></i> Back to Orders
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h4 class="mb-0">Order #{{ $order->id }}</h4>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p><strong>Date:</strong> {{ $order->created_at->format('M d, Y H:i') }}</p>
                        <p><strong>Status:</strong> <span class="badge badge-{{ $order->status === 'completed' ? 'success' : ($order->status === 'cancelled' ? 'danger' : 'warning') }}">{{ ucfirst($order->status) }}</span></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Delivery Address:</strong></p>
                        <p>{{ $order->delivery_address }}</p>
                    </div>
                </div>
            </div>
        </div>

        @if($order->items->count() > 0)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Product Items</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th style="width: 80px;">Quantity</th>
                                <th style="width: 100px;">Unit Price</th>
                                <th style="width: 100px;">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                                <tr>
                                    <td>{{ $item->product?->name ?? 'N/A' }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>₱{{ number_format($item->price, 2) }}</td>
                                    <td>₱{{ number_format($item->price * $item->quantity, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        @if($order->petOrders->count() > 0)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Pet Orders</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Pet Name</th>
                                <th>Species</th>
                                <th>Breed</th>
                                <th style="width: 100px;">Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->petOrders as $petOrder)
                                <tr>
                                    <td>{{ $petOrder->pet?->name ?? 'N/A' }}</td>
                                    <td>{{ $petOrder->pet?->species ?? 'N/A' }}</td>
                                    <td>{{ $petOrder->pet?->breed ?? 'N/A' }}</td>
                                    <td>₱{{ number_format($petOrder->price, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        @if($order->payment)
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Payment Information</h5>
                </div>
                <div class="card-body">
                    <p><strong>Method:</strong> {{ ucfirst($order->payment->payment_method) }}</p>
                    <p><strong>Status:</strong> <span class="badge {{ $order->payment->status === 'paid' ? 'bg-success' : 'bg-warning text-dark' }}">{{ ucfirst($order->payment->status) }}</span></p>
                    @if($order->payment->reference_number)
                        <p><strong>Reference Number:</strong> {{ $order->payment->reference_number }}</p>
                    @endif
                    @if($order->payment->account_info)
                        <p><strong>Account Info:</strong> {{ $order->payment->account_info }}</p>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Order Summary</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Subtotal:</span>
                    <span>₱{{ number_format($order->total_amount, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Shipping:</span>
                    <span>Free</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <strong>Total:</strong>
                    <strong>₱{{ number_format($order->total_amount, 2) }}</strong>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
