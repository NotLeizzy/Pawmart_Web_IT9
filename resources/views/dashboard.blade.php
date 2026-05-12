@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<!-- Condensed User Greeting -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #ff9f43 0%, #ff6b6b 100%); color: white;">
            <div class="card-body py-3 px-4 d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0" style="font-family: 'Comfortaa', cursive;">Welcome back, {{ Auth::user()->name }}! 👋</h4>
                    <p class="mb-0 small" style="opacity: 0.9;">Ready to spoil your furry friends today?</p>
                </div>
                <i class="fas fa-paw fa-2x" style="opacity: 0.3;"></i>
            </div>
        </div>
    </div>
</div>

<!-- Quick Stats Row -->
<div class="row mb-4">
    <!-- My Orders Card -->
    <div class="col-md-4 mb-3">
        <div class="card border-0 shadow-sm h-100 text-center pet-card" style="border-top: 4px solid #ff9f43;">
            <div class="card-body py-3">
                <i class="fas fa-shopping-bag text-muted mb-2" style="font-size: 24px;"></i>
                <h6 class="card-title text-muted mb-1">My Orders</h6>
                <h3 style="color: #ff9f43; font-weight: bold;">{{ $myOrdersCount ?? 0 }}</h3>
                <a href="{{ route('orders.index') }}" class="btn btn-sm btn-outline-primary mt-2">View Orders</a>
            </div>
        </div>
    </div>

    <!-- Favorite Pets Card -->
    <div class="col-md-4 mb-3">
        <div class="card border-0 shadow-sm h-100 text-center pet-card" style="border-top: 4px solid #a55eea;">
            <div class="card-body py-3">
                <i class="fas fa-heart text-muted mb-2" style="font-size: 24px;"></i>
                <h6 class="card-title text-muted mb-1">My Pets</h6>
                <h3 style="color: #a55eea; font-weight: bold;">{{ $myPetsCount ?? 0 }}</h3>
                <a href="{{ route('pets.index') }}" class="btn btn-sm btn-outline-primary mt-2">View Pets</a>
            </div>
        </div>
    </div>

    <!-- My Pet Orders Card -->
    <div class="col-md-4 mb-3">
        <div class="card border-0 shadow-sm h-100 text-center pet-card" style="border-top: 4px solid #ff6b6b;">
            <div class="card-body py-3">
                <i class="fas fa-gifts text-muted mb-2" style="font-size: 24px;"></i>
                <h6 class="card-title text-muted mb-1">Pet Orders</h6>
                <h3 style="color: #ff6b6b; font-weight: bold;">{{ $myPetOrdersCount ?? 0 }}</h3>
                <a href="{{ route('pet-orders.index') }}" class="btn btn-sm btn-outline-primary mt-2">View Pet Orders</a>
            </div>
        </div>
    </div>
</div>

<!-- Featured Products Section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
            <h4 style="font-family: 'Comfortaa', cursive; color: var(--primary-color);">
                <i class="fas fa-star me-1 text-warning"></i> Featured Products
            </h4>
            <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill">
                See All <i class="fas fa-arrow-right ms-1"></i>
            </a>
        </div>

        <div class="row g-3">
            @forelse($featuredProducts as $product)
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card border-0 shadow-sm h-100 pet-card">
                        @if($product->images->first())
                            <img src="{{ asset('storage/' . $product->images->first()->path) }}" class="card-img-top" alt="{{ $product->name }}" style="height: 180px; object-fit: cover;">
                        @else
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 180px;">
                                <i class="fas fa-box fa-3x text-muted"></i>
                            </div>
                        @endif

                        <div class="card-body d-flex flex-column pb-2">
                            <h6 class="card-title text-truncate fw-bold mb-1">{{ $product->name }}</h6>
                            <p class="text-muted small mb-2 text-truncate">{{ $product->category->name ?? 'General' }}</p>
                            <h5 class="text-primary fw-bold mt-auto mb-2">₱{{ number_format($product->price, 2) }}</h5>
                            <button class="btn btn-sm w-100 btn-add-cart" data-product-id="{{ $product->id }}" style="background: var(--primary-color); color: white; border: none;">
                                <i class="fas fa-cart-plus me-1"></i> Add to Cart
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-light text-center py-4 border">
                        <i class="fas fa-box-open fa-2x text-muted mb-2 d-block"></i>
                        No featured products right now.
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Available Pets Section -->
<div class="row mb-5">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
            <h4 style="font-family: 'Comfortaa', cursive; color: #a55eea;">
                <i class="fas fa-paw me-1"></i> Pets Looking for a Home
            </h4>
            <a href="{{ route('pets.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill">
                View All Pets <i class="fas fa-arrow-right ms-1"></i>
            </a>
        </div>

        <div class="row g-3">
            @forelse($availablePets as $pet)
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card shadow-sm border-0 h-100 pet-card">
                        @if($pet->image)
                            <img src="{{ asset($pet->image) }}" class="card-img-top" alt="{{ $pet->name }}" style="height: 180px; object-fit: cover;">
                        @else
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 180px;">
                                <i class="fas fa-dog fa-3x text-muted"></i>
                            </div>
                        @endif

                        <div class="card-body pb-2">
                            <h6 class="card-title fw-bold mb-1">{{ $pet->name }}</h6>
                            <p class="text-muted small mb-1"><i class="fas fa-dna me-1"></i> {{ $pet->breed ?? $pet->species }}</p>
                            <h5 class="text-primary fw-bold mt-2 mb-2">₱{{ number_format($pet->price, 2) }}</h5>
                            <button class="btn btn-sm w-100 btn-adopt" data-pet-id="{{ $pet->id }}" style="background: linear-gradient(135deg, #a55eea 0%, #8854d0 100%); color: white; border: none;">
                                <i class="fas fa-heart me-1"></i> Adopt
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-light text-center py-4 border">
                        <i class="fas fa-sad-cry fa-2x text-muted mb-2 d-block"></i>
                        No pets available for adoption right now.
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>

<style>
    .pet-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .pet-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Handle Product Add to Cart
    document.querySelectorAll('.btn-add-cart').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.dataset.productId;
            const btn = this;
            const originalText = btn.innerHTML;
            
            btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Adding...';
            btn.disabled = true;

            fetch('{{ route("products.addToCart") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: 1
                })
            })
            .then(res => res.json().then(data => ({ status: res.status, body: data })))
            .then(res => {
                if (res.status === 200) {
                    updateCartBadge(res.body.cart_count);
                    showSuccess(btn, originalText);
                } else {
                    alert(res.body.message || 'Error adding to cart.');
                    resetButton(btn, originalText);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
                resetButton(btn, originalText);
            });
        });
    });

    // Handle Pet Adopt (Add to Cart)
    document.querySelectorAll('.btn-adopt').forEach(button => {
        button.addEventListener('click', function() {
            const petId = this.dataset.petId;
            const btn = this;
            const originalText = btn.innerHTML;
            
            btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Adopting...';
            btn.disabled = true;

            fetch('{{ route("pets.addToCart") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    pet_id: petId
                })
            })
            .then(res => res.json().then(data => ({ status: res.status, body: data })))
            .then(res => {
                if (res.status === 200) {
                    updateCartBadge(res.body.cart_count);
                    showSuccess(btn, originalText);
                } else {
                    alert(res.body.message || 'Error adding pet to cart.');
                    resetButton(btn, originalText);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
                resetButton(btn, originalText);
            });
        });
    });

    function updateCartBadge(count) {
        // Update both desktop and mobile cart badges
        document.querySelectorAll('.fa-shopping-cart').forEach(icon => {
            let badge = icon.nextElementSibling;
            if (badge && badge.classList.contains('badge')) {
                badge.textContent = count;
            } else if (icon.parentElement.querySelector('.badge')) {
                icon.parentElement.querySelector('.badge').textContent = count;
            } else {
                icon.insertAdjacentHTML('afterend', ` <span class="badge bg-danger rounded-pill position-absolute top-0 start-100 translate-middle" style="font-size: 10px; padding: 2px 6px;">${count}</span>`);
            }
        });
    }

    function showSuccess(btn, originalText) {
        btn.innerHTML = '<i class="fas fa-check"></i> Added!';
        const originalBg = btn.style.background;
        btn.style.background = '#28a745'; // success green
        
        setTimeout(() => {
            btn.innerHTML = originalText;
            btn.style.background = originalBg;
            btn.disabled = false;
        }, 2000);
    }

    function resetButton(btn, originalText) {
        btn.innerHTML = originalText;
        btn.disabled = false;
    }
});
</script>

@endsection