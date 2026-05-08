@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row mb-5">
    <div class="col-12">
        <div class="text-center mb-5">
            <h1 style="font-family: 'Comfortaa', cursive; color: #ff9f43;">Welcome to PawMart! 🐾</h1>
            <p class="text-muted lead">Your pet supplies shopping destination</p>
        </div>
    </div>
</div>

<!-- User Welcome Card -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #ff9f43 0%, #ff6b6b 100%); color: white;">
            <div class="card-body py-5">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h3 class="card-title" style="font-family: 'Comfortaa', cursive;">Hello, {{ Auth::user()->name }}! 👋</h3>
                        <p class="card-text mb-0">Browse our wide selection of pet products and essentials. Happy shopping!</p>
                    </div>
                    <div class="col-md-4 text-end">
                        <i class="fas fa-shopping-bag" style="font-size: 60px; opacity: 0.2;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Stats Row -->
<div class="row mb-4">
    <!-- My Orders Card -->
    <div class="col-md-6 col-lg-4 mb-3">
        <div class="card border-0 shadow-sm h-100 text-center" style="border-top: 4px solid #ff9f43;">
            <div class="card-body py-4">
                <i class="fas fa-shopping-bag" style="font-size: 40px; color: #ff9f43; margin-bottom: 15px; display: block;"></i>
                <h5 class="card-title">My Orders</h5>
                <p class="text-muted mb-3" style="font-size: 28px; color: #ff9f43; margin: 0;">
                    <strong>{{ $myOrdersCount ?? 0 }}</strong>
                </p>
                <a href="{{ route('orders.index') }}" class="btn btn-sm btn-outline-primary">
                    View Orders <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Favorite Pets Card -->
    <div class="col-md-6 col-lg-4 mb-3">
        <div class="card border-0 shadow-sm h-100 text-center" style="border-top: 4px solid #a55eea;">
            <div class="card-body py-4">
                <i class="fas fa-heart" style="font-size: 40px; color: #a55eea; margin-bottom: 15px; display: block;"></i>
                <h5 class="card-title">My Pets</h5>
                <p class="text-muted mb-3" style="font-size: 28px; color: #a55eea; margin: 0;">
                    <strong>{{ $myPetsCount ?? 0 }}</strong>
                </p>
                <a href="{{ route('pets.index') }}" class="btn btn-sm btn-outline-primary">
                    View Pets <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- My Pet Orders Card -->
    <div class="col-md-6 col-lg-4 mb-3">
        <div class="card border-0 shadow-sm h-100 text-center" style="border-top: 4px solid #ff6b6b;">
            <div class="card-body py-4">
                <i class="fas fa-gifts" style="font-size: 40px; color: #ff6b6b; margin-bottom: 15px; display: block;"></i>
                <h5 class="card-title">Pet Orders</h5>
                <p class="text-muted mb-3" style="font-size: 28px; color: #ff6b6b; margin: 0;">
                    <strong>{{ $myPetOrdersCount ?? 0 }}</strong>
                </p>
                <a href="{{ route('pet-orders.index') }}" class="btn btn-sm btn-outline-primary">
                    View Pet Orders <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Featured Products Section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 style="font-family: 'Comfortaa', cursive;">
                <i class="fas fa-star"></i> Featured Products
            </h4>
            <a href="{{ route('products.index') }}" class="text-decoration-none" style="color: #ff9f43;">
                See All <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        <div class="row">
            @if(isset($featuredProducts) && $featuredProducts->count() > 0)
                @foreach($featuredProducts->take(4) as $product)
                    <div class="col-md-6 col-lg-3 mb-3">
                        <div class="card border-0 shadow-sm h-100 text-center hover-scale" style="cursor: pointer; transition: all 0.3s ease;">
                            <div class="card-body py-4" style="background-color: #f9f9f9;">
                                <i class="fas fa-cube" style="font-size: 50px; color: #ff9f43; margin-bottom: 15px; display: block;"></i>
                                <h6 class="card-title text-truncate">{{ $product->name }}</h6>
                                <p class="text-muted mb-3" style="font-size: 12px;">{{ Str::limit($product->description, 50) }}</p>
                                <p class="mb-3" style="color: #ff9f43; font-weight: 600; font-size: 18px;">
                                    ₱{{ number_format($product->price ?? 0, 2) }}
                                </p>
                                <a href="{{ route('products.show', $product->id) }}" class="btn btn-sm btn-primary">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="fas fa-box-open" style="font-size: 60px; color: #ddd; margin-bottom: 15px; display: block;"></i>
                        <p class="text-muted">No products available yet</p>
                        <a href="{{ route('products.index') }}" class="btn btn-primary">
                            Browse All Products
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Recent Orders Section -->
<div class="row">
    <div class="col-12">
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
                            <th>Date</th>
                            <th>Status</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($recentOrders) && $recentOrders->count() > 0)
                            @foreach($recentOrders as $order)
                                <tr>
                                    <td><strong>#{{ $order->id }}</strong></td>
                                    <td>{{ $order->created_at->format('M d, Y') ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge" style="background-color: #ff9f43;">
                                            {{ ucfirst($order->status ?? 'pending') }}
                                        </span>
                                    </td>
                                    <td><strong>₱{{ number_format($order->total ?? 0, 2) }}</strong></td>
                                    <td>
                                        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">
                                            Details
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    <i class="fas fa-inbox"></i> No orders yet. Start shopping!
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            @if(isset($recentOrders) && $recentOrders->count() > 0)
                <div class="card-footer bg-light border-top text-center">
                    <a href="{{ route('orders.index') }}" class="text-decoration-none" style="color: #ff9f43;">
                        View All Orders <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .hover-scale:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1) !important;
    }
</style>

@endsection