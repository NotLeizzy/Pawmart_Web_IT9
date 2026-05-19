@extends('layouts.admin')

@section('page-title', 'Orders Management')
@section('title', 'Orders')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <h2 class="mb-0">Orders</h2>
            @if(isset($orderHeapTime) && isset($scheduleMode) && $scheduleMode === 'priority')
            <span class="badge border-0" style="background: linear-gradient(135deg, #a55eea 0%, #8c44db 100%); color: white; font-size: 13px; font-weight: 600; padding: 8px 16px; border-radius: 50px; box-shadow: 0 4px 6px rgba(165, 94, 234, 0.2);">
                <i class="fas fa-microchip me-1"></i> Heap Scheduler: {{ $orderHeapTime }} ms
            </span>
            @else
            <span class="badge border-0 bg-secondary" style="font-size: 13px; font-weight: 600; padding: 8px 16px; border-radius: 50px;">
                <i class="fas fa-history me-1"></i> FCFS Sequential Order
            </span>
            @endif
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<!-- Filter Section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.orders.index') }}" class="row g-3">
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control" placeholder="Search ID or customer..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">All Statuses</option>
                            <option value="pending"    {{ request('status') == 'pending'    ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="shipped"    {{ request('status') == 'shipped'    ? 'selected' : '' }}>Shipped</option>
                            <option value="delivered"  {{ request('status') == 'delivered'  ? 'selected' : '' }}>Delivered</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="schedule" class="form-select">
                            <option value="priority" {{ (request('schedule', 'priority') == 'priority') ? 'selected' : '' }}>⚡ Heap Priority Queue</option>
                            <option value="fcfs"     {{ (request('schedule') == 'fcfs') ? 'selected' : '' }}>⏳ FCFS (First-Come, First-Served)</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-grow-1">
                                <i class="fas fa-filter"></i> Apply
                            </button>
                            <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
                                <i class="fas fa-redo"></i> Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Orders Table -->
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Items</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td><strong>#{{ $order->id }}</strong></td>
                            <td>{{ $order->user->name ?? 'N/A' }}</td>
                            <td>
                                <span class="badge bg-light text-dark">
                                    {{ $order->items->sum('quantity') ?? 0 }} items
                                </span>
                            </td>
                            <td><strong>₱{{ number_format($order->total_amount ?? 0, 2) }}</strong></td>
                            <td>
                                <form action="{{ route('admin.orders.update', $order->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <select name="status" class="form-select form-select-sm"
                                        onchange="this.form.submit()"
                                        style="min-width: 130px;"
                                    >
                                        <option value="pending"    {{ $order->status == 'pending'    ? 'selected' : '' }}>⏳ Pending</option>
                                        <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>🔄 Processing</option>
                                        <option value="shipped"    {{ $order->status == 'shipped'    ? 'selected' : '' }}>🚚 Shipped</option>
                                        <option value="delivered"  {{ $order->status == 'delivered'  ? 'selected' : '' }}>✅ Delivered</option>
                                    </select>
                                </form>
                            </td>
                            <td>{{ $order->created_at->format('M d, Y H:i') ?? 'N/A' }}</td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-outline-info" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="fas fa-inbox"></i> No orders found
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
@if(isset($orders) && method_exists($orders, 'links'))
<div class="row mt-4">
    <div class="col-12">
        {{ $orders->links() }}
    </div>
</div>
@endif

@endsection