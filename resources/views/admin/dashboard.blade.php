@extends('layouts.admin')

@section('page-title', 'Admin Dashboard')
@section('title', 'Admin Dashboard')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2 class="mb-2">Welcome back, <strong>{{ Auth::user()->name }}</strong>! 👋</h2>
        <p class="text-muted">Here's an overview of your store performance</p>
    </div>
</div>

<!-- Key Metrics Row -->
<div class="row mb-4">
    <!-- Total Products Card -->
    <div class="col-md-6 col-lg-3 mb-3">
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #ff9f43;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-2" style="font-size: 14px;">Total Products</p>
                        <h3 class="mb-0" style="color: #ff9f43;">
                            <i class="fas fa-cube"></i> {{ $totalProducts ?? 0 }}
                        </h3>
                    </div>
                    <i class="fas fa-cube" style="font-size: 32px; color: #ffe4cc; opacity: 0.5;"></i>
                </div>
            </div>
            <div class="card-footer bg-transparent border-top">
                <a href="{{ route('admin.products.index') }}" class="text-decoration-none" style="color: #ff9f43; font-size: 14px;">
                    View Products <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Total Orders Card -->
    <div class="col-md-6 col-lg-3 mb-3">
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #ff6b6b;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-2" style="font-size: 14px;">Total Orders</p>
                        <h3 class="mb-0" style="color: #ff6b6b;">
                            <i class="fas fa-receipt"></i> {{ $totalOrders ?? 0 }}
                        </h3>
                    </div>
                    <i class="fas fa-receipt" style="font-size: 32px; color: #ffe4cc; opacity: 0.5;"></i>
                </div>
            </div>
            <div class="card-footer bg-transparent border-top">
                <a href="{{ route('admin.orders.index') }}" class="text-decoration-none" style="color: #ff6b6b; font-size: 14px;">
                    View Orders <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Total Pets Card -->
    <div class="col-md-6 col-lg-3 mb-3">
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #a55eea;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-2" style="font-size: 14px;">Total Pets</p>
                        <h3 class="mb-0" style="color: #a55eea;">
                            <i class="fas fa-paw"></i> {{ $totalPets ?? 0 }}
                        </h3>
                    </div>
                    <i class="fas fa-paw" style="font-size: 32px; color: #ffe4cc; opacity: 0.5;"></i>
                </div>
            </div>
            <div class="card-footer bg-transparent border-top">
                <a href="{{ route('admin.pets.index') }}" class="text-decoration-none" style="color: #a55eea; font-size: 14px;">
                    View Pets <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Low Stock Alert Card -->
    <div class="col-md-6 col-lg-3 mb-3">
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #ffa502;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-2" style="font-size: 14px;">Low Stock Items</p>
                        <h3 class="mb-0" style="color: #ffa502;">
                            <i class="fas fa-exclamation"></i> {{ $lowStockItems ?? 0 }}
                        </h3>
                    </div>
                    <i class="fas fa-exclamation" style="font-size: 32px; color: #ffe4cc; opacity: 0.5;"></i>
                </div>
            </div>
            <div class="card-footer bg-transparent border-top">
                <a href="{{ route('admin.stock-movements.index') }}" class="text-decoration-none" style="color: #ffa502; font-size: 14px;">
                    Manage Stock <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions Section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom">
                <h5 class="card-title mb-0">
                    <i class="fas fa-bolt"></i> Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 col-sm-6 mb-3 mb-md-0">
                        <a href="{{ route('admin.products.create') }}" class="btn btn-primary w-100">
                            <i class="fas fa-plus"></i> Add Product
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3 mb-md-0">
                        <a href="{{ route('admin.categories.create') }}" class="btn btn-info w-100">
                            <i class="fas fa-tag"></i> Add Category
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3 mb-md-0">
                        <a href="{{ route('pets.create') }}" class="btn btn-success w-100">
                            <i class="fas fa-paw"></i> Add Pet
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <a href="{{ route('orders.create') }}" class="btn btn-warning w-100">
                            <i class="fas fa-receipt"></i> Create Order
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Orders Section -->
<div class="row">
    <div class="col-lg-8 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom">
                <h5 class="card-title mb-0">
                    <i class="fas fa-history"></i> Recent Orders
                </h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($recentOrders) && $recentOrders->count() > 0)
                        @foreach($recentOrders as $order)
                        <tr>
                            <td><strong>#{{ $order->id }}</strong></td>
                            <td>{{ $order->user->name ?? 'N/A' }}</td>
                            <td>₱{{ number_format($order->total_amount ?? 0, 2) }}</td>
                            <td>
                                <span class="badge bg-warning text-dark">{{ ucfirst($order->status ?? 'pending') }}</span>
                            </td>
                            <td>{{ $order->created_at->format('M d, Y') ?? 'N/A' }}</td>
                            <td>
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">
                                    View
                                </a>
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No orders yet</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Sidebar Cards -->
    <div class="col-lg-4">
        <!-- Quick Info Card -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle"></i> Store Info
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <small class="text-muted">Total Categories</small>
                    <p class="mb-0" style="font-size: 18px; color: #ff9f43;">
                        <strong>{{ $totalCategories ?? 0 }}</strong>
                    </p>
                </div>
                <hr>
                <div class="mb-3">
                    <small class="text-muted">Total Payments</small>
                    <p class="mb-0" style="font-size: 18px; color: #ff6b6b;">
                        <strong>{{ $totalPayments ?? 0 }}</strong>
                    </p>
                </div>
                <hr>
                <div>
                    <small class="text-muted">Pet Orders</small>
                    <p class="mb-0" style="font-size: 18px; color: #a55eea;">
                        <strong>{{ $totalPetOrders ?? 0 }}</strong>
                    </p>
                </div>
            </div>
        </div>

        <!-- Help Card -->
        <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #ff9f43 0%, #ffa502 100%); color: white;">
            <div class="card-body">
                <h5 class="card-title">Need Help?</h5>
                <p class="card-text" style="font-size: 14px;">Check our documentation or contact support for assistance with the admin panel.</p>
                <a href="#" class="btn btn-sm btn-light">
                    <i class="fas fa-question-circle"></i> Get Help
                </a>
            </div>
        </div>
    </div>
</div>

@endsection