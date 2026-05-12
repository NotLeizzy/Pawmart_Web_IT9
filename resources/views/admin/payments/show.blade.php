@extends('layouts.admin')

@section('page-title', 'Payment Details')
@section('title', 'Payment Details')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Payment Details #{{ $payment->id }}</h2>
            <a href="{{ route('admin.payments.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back to Payments
            </a>
        </div>
    </div>
</div>

<div class="row">
    <!-- Payment Info Card -->
    <div class="col-md-6 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom pt-4 pb-3">
                <h5 class="mb-0"><i class="fas fa-credit-card me-2 text-primary"></i> Payment Information</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th class="ps-0" style="width: 40%;">Payment ID:</th>
                        <td>#{{ $payment->id }}</td>
                    </tr>
                    <tr>
                        <th class="ps-0">Status:</th>
                        <td>
                            @if($payment->status == 'paid')
                                <span class="badge bg-success">Paid</span>
                            @elseif($payment->status == 'failed')
                                <span class="badge bg-danger">Failed</span>
                            @else
                                <span class="badge bg-warning text-dark">Pending</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="ps-0">Amount:</th>
                        <td class="fs-5 fw-bold text-primary">₱{{ number_format($payment->amount, 2) }}</td>
                    </tr>
                    <tr>
                        <th class="ps-0">Method:</th>
                        <td>{{ $payment->payment_method ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th class="ps-0">Date:</th>
                        <td>{{ $payment->created_at->format('F d, Y h:i A') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Associated Order Info Card -->
    <div class="col-md-6 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom pt-4 pb-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-box me-2 text-info"></i> Order Information</h5>
                    <a href="{{ route('admin.orders.show', $payment->order_id) }}" class="btn btn-sm btn-outline-info">View Order</a>
                </div>
            </div>
            <div class="card-body">
                @if($payment->order)
                    <table class="table table-borderless">
                        <tr>
                            <th class="ps-0" style="width: 40%;">Order ID:</th>
                            <td>#{{ $payment->order->id }}</td>
                        </tr>
                        <tr>
                            <th class="ps-0">Customer:</th>
                            <td>{{ $payment->order->user->name ?? 'Guest' }}</td>
                        </tr>
                        <tr>
                            <th class="ps-0">Order Status:</th>
                            <td>
                                <span class="badge bg-secondary">{{ ucfirst($payment->order->status) }}</span>
                            </td>
                        </tr>
                        <tr>
                            <th class="ps-0">Delivery Address:</th>
                            <td>{{ $payment->order->delivery_address }}</td>
                        </tr>
                    </table>
                @else
                    <div class="alert alert-warning">
                        Associated order not found.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@if($payment->order && $payment->order->items->count() > 0)
<!-- Order Items -->
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom pt-4 pb-3">
                <h5 class="mb-0"><i class="fas fa-shopping-basket me-2 text-warning"></i> Items Purchased</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Product</th>
                            <th class="text-center">Price</th>
                            <th class="text-center">Quantity</th>
                            <th class="text-end pe-4">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payment->order->items as $item)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    @if($item->product && $item->product->images->first())
                                        <img src="{{ asset('storage/' . $item->product->images->first()->path) }}" alt="{{ $item->product->name }}" class="rounded me-3" style="width: 40px; height: 40px; object-fit: cover;">
                                    @else
                                        <div class="rounded bg-light d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                            <i class="fas fa-image text-muted"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <h6 class="mb-0">{{ $item->product->name ?? 'Unknown Product' }}</h6>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">₱{{ number_format($item->price, 2) }}</td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td class="text-end pe-4 fw-medium">₱{{ number_format($item->price * $item->quantity, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="3" class="text-end fw-bold">Total:</td>
                            <td class="text-end pe-4 fw-bold fs-5 text-primary">₱{{ number_format($payment->order->total_amount, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endif

@endsection
