@extends('layouts.app')

@section('content')

<div class="container">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-0">Pets</h3>
            <p class="text-muted">Browse available pets for adoption or purchase.</p>
        </div>

        <div>
            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary me-2">
                ← Back
            </a>
        </div>
    </div>

    {{-- Pet Cards --}}
    <div class="row g-3">
        @foreach($pets as $pet)
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card shadow-sm border-0 h-100 pet-card">
                    @if($pet->image)
                        <img src="{{ $pet->image }}" class="card-img-top" alt="{{ $pet->name }}" style="height: 200px; object-fit: cover;">
                    @else
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                            <i class="fas fa-paw fa-3x text-muted"></i>
                        </div>
                    @endif

                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="card-title mb-0 fw-bold">{{ $pet->name }}</h5>
                            <span class="badge bg-{{ $pet->status == 'available' ? 'success' : 'secondary' }}">
                                {{ ucfirst($pet->status) }}
                            </span>
                        </div>

                        <p class="mb-1 text-muted small"><i class="fas fa-dna me-1"></i> <strong>Species:</strong> {{ $pet->species }}</p>
                        <p class="mb-1 text-muted small"><i class="fas fa-dog me-1"></i> <strong>Breed:</strong> {{ $pet->breed }}</p>
                        <p class="mb-2 text-muted small"><i class="fas fa-birthday-cake me-1"></i> <strong>Age:</strong> {{ $pet->age }} {{ $pet->age_unit ?? 'yrs' }}</p>
                        
                        <h5 class="text-primary mt-3 fw-bold">₱{{ number_format($pet->price, 2) }}</h5>
                    </div>

                    @if($pet->status == 'available')
                    <div class="card-footer bg-white border-0 pb-3 pt-0">
                        <button class="btn btn-primary w-100 btn-adopt" data-pet-id="{{ $pet->id }}" style="background: linear-gradient(135deg, var(--primary-color) 0%, var(--accent-color) 100%); border: none;">
                            <i class="fas fa-heart me-1"></i> Adopt Me
                        </button>
                    </div>
                    @endif
                </div>
            </div>
        @endforeach
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

    document.querySelectorAll('.btn-adopt').forEach(button => {
        button.addEventListener('click', function() {
            const petId = this.dataset.petId;
            const btn = this;
            
            // Visual feedback
            const originalText = btn.innerHTML;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Adding...';
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
            .then(response => response.json().then(data => ({ status: response.status, body: data })))
            .then(res => {
                if (res.status === 200) {
                    // Update cart badge if it exists
                    const badge = document.querySelector('.nav-link .badge');
                    if (badge) {
                        badge.textContent = res.body.cart_count;
                    } else {
                        // Create badge if it didn't exist
                        const cartIcon = document.querySelector('.fa-shopping-cart');
                        if (cartIcon) {
                            cartIcon.insertAdjacentHTML('afterend', ` <span class="badge bg-danger rounded-pill">${res.body.cart_count}</span>`);
                        }
                    }
                    
                    btn.innerHTML = '<i class="fas fa-check me-1"></i> Added to Cart!';
                    btn.classList.remove('btn-primary');
                    btn.classList.add('btn-success');
                    
                    setTimeout(() => {
                        btn.innerHTML = originalText;
                        btn.disabled = false;
                        btn.classList.remove('btn-success');
                        btn.classList.add('btn-primary');
                    }, 2000);
                } else {
                    alert(res.body.message || 'Error adding pet to cart.');
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
                btn.innerHTML = originalText;
                btn.disabled = false;
            });
        });
    });
});
</script>

@endsection