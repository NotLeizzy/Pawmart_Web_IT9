@extends('layouts.admin')

@section('page-title', 'Pet Orders Management')
@section('title', 'Pet Orders')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2>Pet Orders</h2>
    </div>
</div>

<!-- Filter Section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.pet-orders.index') }}" class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Search by pet name or customer..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-4">
                        <select name="status" class="form-select">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-grow-1">
                                <i class="fas fa-search"></i> Filter
                            </button>
                            <a href="{{ route('admin.pet-orders.index') }}" class="btn btn-secondary">
                                <i class="fas fa-redo"></i>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Pet Orders Table -->
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Pet Order ID</th>
                            <th>Pet Name</th>
                            <th>Owner</th>
                            <th>Order Date</th>
                            <th>Details</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($petOrders as $petOrder)
                            <tr>
                                <td><strong>#{{ $petOrder->id }}</strong></td>
                                <td>
                                    <i class="fas fa-paw"></i> {{ $petOrder->pet->name ?? 'N/A' }}
                                </td>
                                <td>{{ optional($petOrder->order->user)->name ?? 'N/A' }}</td>
                                <td>{{ $petOrder->created_at->format('M d, Y') ?? 'N/A' }}</td>
                                <td>
                                    <small class="text-muted">
                                        {{ Str::limit($petOrder->notes ?? 'No description', 50) }}
                                    </small>
                                </td>
                                <td>
                                    @if($petOrder->status == 'approved')
                                        <span class="badge bg-success">
                                            <i class="fas fa-check"></i> Approved
                                        </span>
                                    @elseif($petOrder->status == 'rejected')
                                        <span class="badge bg-danger">
                                            <i class="fas fa-times"></i> Rejected
                                        </span>
                                    @else
                                        <span class="badge bg-warning text-dark">
                                            <i class="fas fa-clock"></i> Pending
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('admin.pet-orders.show', $petOrder->id) }}" class="btn btn-outline-info" title="View">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="fas fa-inbox"></i> No pet orders found
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
@if(isset($petOrders) && method_exists($petOrders, 'links'))
<div class="row mt-4">
    <div class="col-12">
        {{ $petOrders->links() }}
    </div>
</div>
@endif

@endsection
