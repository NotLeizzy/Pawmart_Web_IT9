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

    <!-- Performance Analytics & Empirical Evaluation Panel -->
    <div class="row mt-4 mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm overflow-hidden" style="background: #1e1e2f; color: #ffffff; border-radius: 12px;">
                <!-- Panel Header -->
                <div class="card-header border-bottom border-secondary d-flex justify-content-between align-items-center" style="background: rgba(255, 255, 255, 0.05); padding: 18px 24px;">
                    <h5 class="card-title mb-0" style="font-weight: 700; color: #4fc3f7; letter-spacing: 0.5px;">
                        <i class="fas fa-microchip me-2"></i> EMPIRICAL PERFORMANCE ANALYTICS & BENCHMARKS
                    </h5>
                    <span class="badge bg-info text-dark" style="font-size: 12px; font-weight: 600; padding: 6px 12px;">
                        ⚡ Live Engine Active (N=100, 500, 1000)
                    </span>
                </div>

                <!-- Body -->
                <div class="card-body" style="padding: 24px;">
                    <!-- Navigation Tabs -->
                    <ul class="nav nav-tabs border-secondary mb-4" id="dsaTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active text-white border-0 bg-transparent" id="general-tab" data-bs-toggle="tab" data-bs-target="#general-pane" type="button" role="tab" style="font-weight: 600; border-bottom: 3px solid #4fc3f7 !important;">
                                <i class="fas fa-chart-bar me-1"></i> General Summary
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link text-muted border-0 bg-transparent" id="search-tab" data-bs-toggle="tab" data-bs-target="#search-pane" type="button" role="tab" style="font-weight: 600;">
                                <i class="fas fa-search me-1"></i> Product Search
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link text-muted border-0 bg-transparent" id="sort-tab" data-bs-toggle="tab" data-bs-target="#sort-pane" type="button" role="tab" style="font-weight: 600;">
                                <i class="fas fa-sort me-1"></i> Product Sorting
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link text-muted border-0 bg-transparent" id="heap-tab" data-bs-toggle="tab" data-bs-target="#heap-pane" type="button" role="tab" style="font-weight: 600;">
                                <i class="fas fa-layer-group me-1"></i> Order Prioritization
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link text-muted border-0 bg-transparent" id="hash-tab" data-bs-toggle="tab" data-bs-target="#hash-pane" type="button" role="tab" style="font-weight: 600;">
                                <i class="fas fa-hashtag me-1"></i> ID Lookup
                            </button>
                        </li>
                    </ul>

                    <!-- Tab Contents -->
                    <div class="tab-content text-white" id="dsaTabContent">
                        
                        <!-- General Summary Pane -->
                        <div class="tab-pane fade show active" id="general-pane" role="tabpanel" aria-labelledby="general-tab">
                            <div class="table-responsive">
                                <table class="table table-dark table-hover mb-0 align-middle text-center" style="border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; overflow: hidden;">
                                    <thead>
                                        <tr style="background: rgba(79, 195, 247, 0.1); border-bottom: 2px solid rgba(79, 195, 247, 0.3);">
                                            <th style="color: #ffffff; padding: 14px;">Input Size (N)</th>
                                            <th style="color: #ff6b6b; padding: 14px;">Baseline Time (ms)</th>
                                            <th style="color: #81c784; padding: 14px;">Optimized Time (ms)</th>
                                            <th style="color: #ffb74d; padding: 14px;">Improvement (%)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($dsaTimings as $size => $times)
                                        <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                                            <td style="padding: 14px; font-weight: 700;">
                                                <span class="badge" style="background: rgba(255,255,255,0.1); color: #ffffff; font-size: 13px; padding: 6px 12px; border-radius: 4px;">
                                                    N = {{ $size }}
                                                </span>
                                            </td>
                                            <td style="padding: 14px; color: #ff6b6b; font-family: monospace; font-size: 15px; font-weight: 600;">
                                                {{ number_format($times['general_baseline'], 2) }} ms
                                            </td>
                                            <td style="padding: 14px; color: #81c784; font-family: monospace; font-size: 15px; font-weight: 600;">
                                                {{ number_format($times['general_optimized'], 2) }} ms
                                            </td>
                                            <td style="padding: 14px; color: #ffb74d; font-family: monospace; font-size: 15px; font-weight: 700;">
                                                <span class="badge bg-success" style="padding: 6px 12px;">
                                                    <i class="fas fa-arrow-up"></i> {{ $times['general_improvement'] }}%
                                                </span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Product Search Pane -->
                        <div class="tab-pane fade" id="search-pane" role="tabpanel" aria-labelledby="search-tab">
                            <div class="table-responsive">
                                <table class="table table-dark table-hover mb-0 align-middle text-center" style="border: 1px solid rgba(255,255,255,0.1); border-radius: 8px;">
                                    <thead>
                                        <tr style="background: rgba(79, 195, 247, 0.1);">
                                            <th style="padding: 14px;">Input Size (N)</th>
                                            <th style="color: #ff6b6b; padding: 14px;">Baseline (SQL Scan)</th>
                                            <th style="color: #4fc3f7; padding: 14px;">Optimized (BST Search)</th>
                                            <th style="color: #81c784; padding: 14px;">Speedup Factor</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($dsaTimings as $size => $times)
                                        <tr>
                                            <td style="padding: 14px; font-weight: 700;">N = {{ $size }}</td>
                                            <td style="padding: 14px; color: #ff6b6b; font-family: monospace;">{{ number_format($times['search_baseline'], 6) }} ms</td>
                                            <td style="padding: 14px; color: #4fc3f7; font-family: monospace;">{{ number_format($times['search_optimized'], 6) }} ms</td>
                                            <td style="padding: 14px; color: #81c784; font-weight: 700;">~{{ number_format($times['search_speedup']) }}x faster</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Sorting Pane -->
                        <div class="tab-pane fade" id="sort-pane" role="tabpanel" aria-labelledby="sort-tab">
                            <div class="table-responsive">
                                <table class="table table-dark table-hover mb-0 align-middle text-center" style="border: 1px solid rgba(255,255,255,0.1); border-radius: 8px;">
                                    <thead>
                                        <tr style="background: rgba(79, 195, 247, 0.1);">
                                            <th style="padding: 14px;">Input Size (N)</th>
                                            <th style="color: #ff6b6b; padding: 14px;">Baseline (Selection Sort)</th>
                                            <th style="color: #a5d6a7; padding: 14px;">Optimized (Merge Sort)</th>
                                            <th style="color: #81c784; padding: 14px;">Speedup Factor</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($dsaTimings as $size => $times)
                                        <tr>
                                            <td style="padding: 14px; font-weight: 700;">N = {{ $size }}</td>
                                            <td style="padding: 14px; color: #ff6b6b; font-family: monospace;">{{ number_format($times['sort_baseline'], 4) }} ms</td>
                                            <td style="padding: 14px; color: #a5d6a7; font-family: monospace;">{{ number_format($times['sort_optimized'], 4) }} ms</td>
                                            <td style="padding: 14px; color: #81c784; font-weight: 700;">~{{ number_format($times['sort_speedup'], 1) }}x faster</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Heap Order Prioritization Pane -->
                        <div class="tab-pane fade" id="heap-pane" role="tabpanel" aria-labelledby="heap-tab">
                            <div class="table-responsive">
                                <table class="table table-dark table-hover mb-0 align-middle text-center" style="border: 1px solid rgba(255,255,255,0.1); border-radius: 8px;">
                                    <thead>
                                        <tr style="background: rgba(79, 195, 247, 0.1);">
                                            <th style="padding: 14px;">Input Size (N)</th>
                                            <th style="color: #ff6b6b; padding: 14px;">Baseline (FCFS scan)</th>
                                            <th style="color: #b39ddb; padding: 14px;">Optimized (Heap Scheduler)</th>
                                            <th style="color: #81c784; padding: 14px;">Speedup Factor</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($dsaTimings as $size => $times)
                                        <tr>
                                            <td style="padding: 14px; font-weight: 700;">N = {{ $size }}</td>
                                            <td style="padding: 14px; color: #ff6b6b; font-family: monospace;">{{ number_format($times['priority_baseline'], 4) }} ms</td>
                                            <td style="padding: 14px; color: #b39ddb; font-family: monospace;">{{ number_format($times['priority_optimized'], 4) }} ms</td>
                                            <td style="padding: 14px; color: #81c784; font-weight: 700;">~{{ number_format($times['priority_speedup'], 1) }}x faster</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- ID Lookup Pane -->
                        <div class="tab-pane fade" id="hash-pane" role="tabpanel" aria-labelledby="hash-tab">
                            <div class="table-responsive">
                                <table class="table table-dark table-hover mb-0 align-middle text-center" style="border: 1px solid rgba(255,255,255,0.1); border-radius: 8px;">
                                    <thead>
                                        <tr style="background: rgba(79, 195, 247, 0.1);">
                                            <th style="padding: 14px;">Input Size (N)</th>
                                            <th style="color: #ff6b6b; padding: 14px;">Baseline (Sequential ID scan)</th>
                                            <th style="color: #ffe082; padding: 14px;">Optimized (Hash Table)</th>
                                            <th style="color: #81c784; padding: 14px;">Speedup Factor</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($dsaTimings as $size => $times)
                                        <tr>
                                            <td style="padding: 14px; font-weight: 700;">N = {{ $size }}</td>
                                            <td style="padding: 14px; color: #ff6b6b; font-family: monospace;">{{ number_format($times['lookup_baseline'], 6) }} ms</td>
                                            <td style="padding: 14px; color: #ffe082; font-family: monospace;">{{ number_format($times['lookup_optimized'], 6) }} ms</td>
                                            <td style="padding: 14px; color: #81c784; font-weight: 700;">~{{ number_format($times['lookup_speedup']) }}x faster</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Interactive Tab Highlighting
            const triggerTabList = document.querySelectorAll('#dsaTab button');
            triggerTabList.forEach(triggerEl => {
                triggerEl.addEventListener('click', event => {
                    event.preventDefault();
                    
                    // Reset styling on all tab links
                    triggerTabList.forEach(btn => {
                        btn.classList.add('text-muted');
                        btn.classList.remove('text-white');
                        btn.style.borderBottom = 'none';
                    });
                    
                    // Set active style on current link
                    triggerEl.classList.remove('text-muted');
                    triggerEl.classList.add('text-white');
                    triggerEl.style.borderBottom = '3px solid #4fc3f7';
                });
            });
        });
    </script>
    @endpush
    @endsection