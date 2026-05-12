@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('content')
<div class="container my-5">
    <div class="row">
        <div class="col-lg-8">
            <h1 class="mb-4">
                <i class="fas fa-shopping-cart me-2" style="color: var(--primary-color);"></i>
                Shopping Cart
            </h1>

            @if(empty($cart))
                <div class="alert alert-info text-center py-5">
                    <i class="fas fa-inbox fa-3x mb-3" style="color: var(--primary-color); opacity: 0.5;"></i>
                    <p class="mb-0"><strong>Your cart is empty</strong></p>
                    <small>Add items to your cart and they will appear here.</small>
                    <div class="mt-3">
                        <a href="{{ route('products.index') }}" class="btn btn-primary">
                            <i class="fas fa-shopping-bag me-2"></i>Continue Shopping
                        </a>
                    </div>
                </div>
            @else
                <div class="cart-items-container">
                    @foreach($cart as $productId => $item)
                        <div class="cart-item mb-3 p-3" style="border: 1px solid #e9ecef; border-radius: 8px; background-color: white;">
                            <div class="row align-items-center">
                                <!-- Product Image -->
                                <div class="col-md-2 text-center mb-2 mb-md-0">
                                    @if($item['image'])
                                        <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" 
                                             style="max-width: 120px; height: auto; border-radius: 6px; object-fit: cover;">
                                    @else
                                        <div style="width: 120px; height: 120px; background-color: #f0f0f0; border-radius: 6px; display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-image" style="font-size: 24px; color: #ccc;"></i>
                                        </div>
                                    @endif
                                </div>

                                <!-- Product Details -->
                                <div class="col-md-4">
                                    <h5 class="mb-1">{{ $item['name'] }}</h5>
                                    <p class="text-muted mb-2" style="font-size: 14px;">
                                        <strong>Price:</strong> ₱{{ number_format($item['price'], 2) }}
                                    </p>
                                    <p class="text-muted" style="font-size: 12px;">
                                        <small>Stock Available: {{ $item['stock'] }}</small>
                                    </p>
                                </div>

                                <!-- Quantity Controls -->
                                <div class="col-md-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <button type="button" class="btn btn-sm btn-outline-secondary decrease-qty" 
                                                data-product-id="{{ $productId }}"
                                                style="padding: 4px 8px;">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                        <input type="number" class="form-control text-center quantity-input" 
                                               value="{{ $item['quantity'] }}" 
                                               min="1" 
                                               max="{{ $item['stock'] }}"
                                               data-product-id="{{ $productId }}"
                                               style="width: 60px; padding: 4px 8px;">
                                        <button type="button" class="btn btn-sm btn-outline-secondary increase-qty" 
                                                data-product-id="{{ $productId }}"
                                                style="padding: 4px 8px;">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Subtotal -->
                                <div class="col-md-2 text-md-right mb-2 mb-md-0">
                                    <p class="mb-0 fw-bold" style="color: var(--primary-color); font-size: 16px;">
                                        ₱{{ number_format($item['price'] * $item['quantity'], 2) }}
                                    </p>
                                    <small class="text-muted">Subtotal</small>
                                </div>

                                <!-- Delete Button -->
                                <div class="col-md-1 text-md-right">
                                    <button type="button" class="btn btn-sm btn-danger remove-item" 
                                            data-product-id="{{ $productId }}"
                                            title="Remove from cart">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Cart Summary Sidebar -->
        @if(!empty($cart))
            <div class="col-lg-4">
                <div class="card" style="border: 1px solid #e9ecef; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Order Summary</h5>

                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span>₱<span id="subtotal">{{ number_format($total, 2) }}</span></span>
                        </div>

                        <div class="d-flex justify-content-between mb-2">
                            <span>Shipping:</span>
                            <span style="color: var(--primary-color);">FREE</span>
                        </div>

                        <hr class="my-3">

                        <div class="d-flex justify-content-between mb-4">
                            <span class="fw-bold">Total:</span>
                            <span class="fw-bold" style="color: var(--primary-color); font-size: 18px;">
                                ₱<span id="total">{{ number_format($total, 2) }}</span>
                            </span>
                        </div>

                        <a href="{{ route('orders.create') }}" class="btn btn-primary w-100 py-2" 
                           style="background: linear-gradient(135deg, var(--primary-color) 0%, var(--accent-color) 100%); border: none;">
                            <i class="fas fa-credit-card me-2"></i>Proceed to Checkout
                        </a>

                        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary w-100 py-2 mt-2">
                            <i class="fas fa-shopping-bag me-2"></i>Continue Shopping
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<style>
    .cart-item {
        transition: all 0.3s ease;
    }

    .cart-item:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .quantity-input {
        font-size: 14px;
    }

    .quantity-input:focus {
        border-color: var(--primary-color) !important;
        box-shadow: 0 0 0 0.2rem rgba(255, 159, 67, 0.25) !important;
    }

    .btn-outline-secondary:hover {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
        color: white;
    }

    @media (max-width: 768px) {
        .col-md-right {
            text-align: left;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Increase Quantity
    document.querySelectorAll('.increase-qty').forEach(button => {
        button.addEventListener('click', function () {
            const productId = this.dataset.productId;
            const quantityInput = document.querySelector(`.quantity-input[data-product-id="${productId}"]`);
            let newQuantity = parseInt(quantityInput.value) + 1;
            const maxQuantity = parseInt(quantityInput.max);

            if (newQuantity > maxQuantity) {
                newQuantity = maxQuantity;
                showAlert('Maximum quantity reached based on available stock.', 'warning');
            }

            quantityInput.value = newQuantity;
            updateQuantity(productId, newQuantity);
        });
    });

    // Decrease Quantity
    document.querySelectorAll('.decrease-qty').forEach(button => {
        button.addEventListener('click', function () {
            const productId = this.dataset.productId;
            const quantityInput = document.querySelector(`.quantity-input[data-product-id="${productId}"]`);
            let newQuantity = parseInt(quantityInput.value) - 1;

            if (newQuantity < 1) {
                newQuantity = 1;
                showAlert('Quantity cannot be less than 1.', 'warning');
            }

            quantityInput.value = newQuantity;
            updateQuantity(productId, newQuantity);
        });
    });

    // Manual Quantity Input
    document.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('change', function () {
            const productId = this.dataset.productId;
            let quantity = parseInt(this.value);
            const maxQuantity = parseInt(this.max);

            if (quantity < 1) {
                quantity = 1;
                this.value = 1;
            }

            if (quantity > maxQuantity) {
                quantity = maxQuantity;
                this.value = maxQuantity;
                showAlert('Quantity adjusted to available stock.', 'warning');
            }

            updateQuantity(productId, quantity);
        });
    });

    // Remove Item
    document.querySelectorAll('.remove-item').forEach(button => {
        button.addEventListener('click', function () {
            const productId = this.dataset.productId;
            if (confirm('Are you sure you want to remove this item from your cart?')) {
                removeItem(productId);
            }
        });
    });

    // Update Quantity
    function updateQuantity(productId, quantity) {
        fetch(`/cart/${productId}/update-quantity`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({ quantity: quantity }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update subtotal for the item
                const cartItem = document.querySelector(`[data-product-id="${productId}"]`).closest('.cart-item');
                const subtotalElement = cartItem.querySelector('p.fw-bold');
                subtotalElement.textContent = '₱' + parseFloat(data.subtotal).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

                // Update total
                document.getElementById('subtotal').textContent = parseFloat(data.total).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                document.getElementById('total').textContent = parseFloat(data.total).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

                showAlert(data.message, 'success');
            } else {
                showAlert(data.message, 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('An error occurred while updating quantity.', 'danger');
        });
    }

    // Remove Item
    function removeItem(productId) {
        fetch(`/cart/${productId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove the item from DOM
                const cartItem = document.querySelector(`[data-product-id="${productId}"]`).closest('.cart-item');
                cartItem.remove();

                // Update total
                document.getElementById('subtotal').textContent = parseFloat(data.total).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                document.getElementById('total').textContent = parseFloat(data.total).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

                showAlert(data.message, 'success');

                // If cart is empty, reload the page
                if (Object.keys(document.querySelectorAll('.cart-item')).length === 0) {
                    setTimeout(() => location.reload(), 1000);
                }
            } else {
                showAlert(data.message, 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('An error occurred while removing item.', 'danger');
        });
    }

    // Show Alert
    function showAlert(message, type) {
        const alertHTML = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
        const alertContainer = document.createElement('div');
        alertContainer.innerHTML = alertHTML;
        document.querySelector('.container').prepend(alertContainer.firstElementChild);

        setTimeout(() => {
            const alert = document.querySelector('.alert');
            if (alert) alert.remove();
        }, 4000);
    }
});
</script>
@endsection
