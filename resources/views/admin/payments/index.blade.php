@extends('layouts.admin')

@section('page-title', 'Payments Management')
@section('title', 'Payments')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2>Payments</h2>
    </div>
</div>

<!-- Payment Statistics -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm" style="border-left: 4px solid #28a745;">
            <div class="card-body">
                <h6 class="card-title text-muted">Paid</h6>
                <h3 style="color: #28a745;">{{ $completedPayments ?? 0 }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm" style="border-left: 4px solid #ffc107;">
            <div class="card-body">
                <h6 class="card-title text-muted">Pending</h6>
                <h3 style="color: #ffc107;">{{ $pendingPayments ?? 0 }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm" style="border-left: 4px solid #dc3545;">
            <div class="card-body">
                <h6 class="card-title text-muted">Failed</h6>
                <h3 style="color: #dc3545;">{{ $failedPayments ?? 0 }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm" style="border-left: 4px solid #17a2b8;">
            <div class="card-body">
                <h6 class="card-title text-muted">Total Revenue</h6>
                <h3 style="color: #17a2b8;">₱{{ number_format($totalRevenue ?? 0, 2) }}</h3>
            </div>
        </div>
    </div>
</div>

<!-- Filter Section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.payments.index') }}" class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Search by order ID or customer..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-4">
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-grow-1">
                                <i class="fas fa-search"></i> Filter
                            </button>
                            <a href="{{ route('admin.payments.index') }}" class="btn btn-secondary">
                                <i class="fas fa-redo"></i>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Payments Table -->
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Payment ID</th>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                            <tr>
                                <td><strong>#{{ $payment->id }}</strong></td>
                                <td>
                                    <a href="{{ route('admin.orders.show', $payment->order_id ?? '#') }}">
                                        #{{ $payment->order_id }}
                                    </a>
                                </td>
                                <td>{{ $payment->order->user->name ?? 'N/A' }}</td>
                                <td><strong>₱{{ number_format($payment->amount ?? 0, 2) }}</strong></td>
                                <td>
                                    <span class="badge bg-light text-dark">
                                        {{ ucfirst($payment->method ?? 'unknown') }}
                                    </span>
                                </td>
                                <td>
                                    @if($payment->status == 'paid')
                                        <span class="badge bg-success">Paid</span>
                                    @elseif($payment->status == 'pending')
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @else
                                        <span class="badge bg-danger">Failed</span>
                                    @endif
                                </td>
                                <td>{{ $payment->created_at->format('M d, Y H:i') ?? 'N/A' }}</td>
                                <td>
                                    <a href="{{ route('admin.payments.show', $payment->id) }}" class="btn btn-sm btn-outline-info">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    <i class="fas fa-inbox"></i> No payments found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Pagination -->
@if(isset($payments) && method_exists($payments, 'links'))
<div class="row mt-4">
    <div class="col-12">
        {{ $payments->links() }}
    </div>
</div>
@endif

@endsection
