@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-0">Pet Products</h3>
            <p class="text-muted">Find the perfect items for your furry friends.</p>

            <div>
                <a href="{{ url()->previous() }}" class="btn btn-outline-secondary me-2">
                    ← Back
                </a>
            </div>
        </div>
    </div>
</div>
    <div class="row g-3">
        @foreach($products as $product)
        <div class="col-6 col-md-4 col-lg-3">
            <div class="card h-100 shadow-sm border-0 product-card">

                {{-- Product Image --}}
                <div class="position-relative">
                    @if($product->images->first())
                    <img src="{{ $product->images->first()->path }}"
                        class="card-img-top"
                        style="height: 180px; object-fit: cover;">
                    @else
                    <img src="{{ asset('images/default-product.jpg') }}"
                        class="card-img-top"
                        style="height: 180px; object-fit: cover;">
                    @endif
                </div>

                {{-- Card Body --}}
                <div class="card-body d-flex flex-column">

                    {{-- Product Name --}}
                    <h6 class="card-title text-truncate">
                        {{ $product->name }}
                    </h6>

                    {{-- Price --}}
                    <p class="text-danger fw-bold mb-1">
                        ₱{{ number_format($product->price, 2) }}
                    </p>

                    {{-- Stock --}}
                    <small class="text-muted mb-2">
                        Stock: {{ $product->stock }}
                    </small>

                    {{-- Button --}}
                    <button class="btn btn-sm btn-primary mt-auto add-to-cart-btn"
                        data-product='@json($product)'
                        data-bs-toggle="modal"
                        data-bs-target="#cartModal">
                        <i class="fas fa-shopping-cart"></i> Add
                    </button>

                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{ $products->links() }}

    <!-- Cart Modal -->
    <div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cartModalLabel">Add to Cart</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="cartForm">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div id="cartProductCarousel" class="carousel slide" data-bs-ride="carousel">
                                    <div class="carousel-inner" id="cartProductCarouselInner"></div>
                                    <button class="carousel-control-prev" type="button" data-bs-target="#cartProductCarousel" data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Previous</span>
                                    </button>
                                    <button class="carousel-control-next" type="button" data-bs-target="#cartProductCarousel" data-bs-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Next</span>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h4 id="cartProductName">Product name</h4>
                                <p id="cartProductPrice" class="text-muted"></p>
                                <p id="cartProductStock" class="text-muted"></p>

                                <div class="mb-3">
                                    <label for="cartQuantity" class="form-label">Quantity</label>
                                    <input type="number" id="cartQuantity" name="quantity" class="form-control" min="1" value="1" required>
                                    <input type="hidden" id="cartProductId" name="product_id">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Confirm Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="toastContainer" class="position-fixed bottom-0 end-0 p-3" style="z-index: 1055;"></div>

    @endsection

    @push('scripts')
    <script>
        const cartModal = document.getElementById('cartModal');
        const cartForm = document.getElementById('cartForm');
        const cartProductCarouselInner = document.getElementById('cartProductCarouselInner');
        const cartProductName = document.getElementById('cartProductName');
        const cartProductPrice = document.getElementById('cartProductPrice');
        const cartProductStock = document.getElementById('cartProductStock');
        const cartQuantity = document.getElementById('cartQuantity');
        const cartProductId = document.getElementById('cartProductId');

        document.querySelectorAll('.add-to-cart-btn').forEach(button => {
            button.addEventListener('click', () => {
                const product = JSON.parse(button.dataset.product);
                cartProductId.value = product.id;
                cartProductName.textContent = product.name;
                cartProductPrice.textContent = `Price: ₱${parseFloat(product.price).toFixed(2)}`;
                cartProductStock.textContent = `Available stock: ${product.stock}`;
                cartQuantity.max = product.stock;
                cartQuantity.value = product.stock > 0 ? 1 : 0;

                cartProductCarouselInner.innerHTML = '';

                if (product.images && product.images.length > 0) {
                    product.images.forEach((image, index) => {
                        const slide = document.createElement('div');
                        slide.className = `carousel-item ${index === 0 ? 'active' : ''}`;
                        slide.innerHTML = `
                        <img src="${image.path ? image.path : ''}" class="d-block w-100 rounded" style="max-height: 300px; object-fit: cover;" alt="${product.name}">
                    `;
                        cartProductCarouselInner.appendChild(slide);
                    });
                } else {
                    const slide = document.createElement('div');
                    slide.className = 'carousel-item active';
                    slide.innerHTML = '<div class="d-flex align-items-center justify-content-center bg-light rounded" style="min-height: 220px;">No images available</div>';
                    cartProductCarouselInner.appendChild(slide);
                }
            });
        });

        cartForm.addEventListener('submit', async function(event) {
            event.preventDefault();

            const formData = new FormData(cartForm);
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            try {
                const response = await fetch('{{ route("products.addToCart") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token,
                    },
                    body: formData,
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.message || 'Unable to add product to cart.');
                }

                showToast('success', data.message || 'Added to cart successfully');
                const bootstrapModal = bootstrap.Modal.getInstance(cartModal);
                if (bootstrapModal) {
                    bootstrapModal.hide();
                }
            } catch (error) {
                showToast('danger', error.message || 'Unable to add product to cart.');
            }
        });

        function showToast(type, message) {
            const toastId = `toast-${Date.now()}`;
            const toastElement = document.createElement('div');
            toastElement.className = `toast align-items-center text-bg-${type} border-0`;
            toastElement.role = 'alert';
            toastElement.ariaLive = 'assertive';
            toastElement.ariaAtomic = 'true';
            toastElement.id = toastId;
            toastElement.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        `;

            document.getElementById('toastContainer').appendChild(toastElement);
            const toast = new bootstrap.Toast(toastElement, {
                delay: 3000
            });
            toast.show();
        }
    </script>
    @endpush