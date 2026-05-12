@extends('layouts.admin')

@section('page-title', 'Products Management')
@section('title', 'Products')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Products</h2>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#productCreateModal">
                <i class="fas fa-plus"></i> Add Product
            </button>
        </div>
    </div>
</div>

<!-- Search & Filter Section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.products.index') }}" class="row g-3">
                    <div class="col-md-6">
                        <input type="text" name="search" class="form-control" placeholder="Search products..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-grow-1">
                                <i class="fas fa-search"></i> Search
                            </button>
                            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                                <i class="fas fa-redo"></i> Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Products Table -->
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Product Name</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="productTableBody">
                        @forelse($products as $product)
                            <tr>
                                <td><strong>#{{ $product->id }}</strong></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($product->images && $product->images->first())
                                            <img src="{{ asset('storage/' . $product->images->first()->path) }}" class="rounded me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                        @else
                                            <div class="bg-light rounded me-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <i class="fas fa-box text-muted"></i>
                                            </div>
                                        @endif
                                        {{ $product->name }}
                                    </div>
                                </td>
                                <td>
                                    @if($product->category)
                                        <span class="badge bg-info">{{ $product->category->name }}</span>
                                    @else
                                        <span class="badge bg-secondary">Uncategorized</span>
                                    @endif
                                </td>
                                <td>₱{{ number_format($product->price, 2) }}</td>
                                <td>
                                    <span class="badge @if($product->stock > 20) bg-success @elseif($product->stock > 5) bg-warning @else bg-danger @endif">
                                        {{ $product->stock }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-success">Active</span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-outline-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="fas fa-box-open"></i> No products found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Product Create Modal -->
<div class="modal fade" id="productCreateModal" tabindex="-1" aria-labelledby="productCreateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productCreateModalLabel">Add New Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="productCreateForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div id="productCreateAlert"></div>

                    <div class="row gy-3">
                        <div class="col-md-6">
                            <label for="product_name" class="form-label">Product Name</label>
                            <input type="text" id="product_name" name="name" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label for="product_category" class="form-label">Category</label>
                            <select id="product_category" name="category_id" class="form-select" required>
                                <option value="">Select category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="product_price" class="form-label">Price</label>
                            <input type="number" step="0.01" id="product_price" name="price" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label for="product_stock" class="form-label">Stock Quantity</label>
                            <input type="number" id="product_stock" name="stock" class="form-control" min="0" value="0" required>
                        </div>

                        <div class="col-12">
                            <label for="product_description" class="form-label">Description</label>
                            <textarea id="product_description" name="description" class="form-control" rows="3"></textarea>
                        </div>

                        <div class="col-12">
                            <label for="product_images" class="form-label">Images</label>
                            <input type="file" id="product_images" name="images[]" class="form-control" multiple accept="image/*">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Product</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const productCreateForm = document.getElementById('productCreateForm');

    function showProductCreateAlert(type, message) {
        const alertContainer = document.getElementById('productCreateAlert');
        alertContainer.innerHTML = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
    }

    productCreateForm.addEventListener('submit', async function(event) {
        event.preventDefault();

        const submitButton = productCreateForm.querySelector('button[type="submit"]');
        submitButton.disabled = true;

        const formData = new FormData(productCreateForm);
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        try {
            const response = await fetch('{{ route("admin.products.store") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                },
                body: formData,
            });

            const data = await response.json();

            if (!response.ok) {
                showProductCreateAlert('danger', data.message || 'Unable to save product.');
                submitButton.disabled = false;
                return;
            }

            showProductCreateAlert('success', data.message || 'Product added successfully.');

            const product = data.data;
            const tbody = document.getElementById('productTableBody');
            const row = document.createElement('tr');
            row.innerHTML = `
                <td><strong>#${product.id}</strong></td>
                <td>${product.name}</td>
                <td>${product.category ? product.category.name : 'Uncategorized'}</td>
                <td>₱${parseFloat(product.price).toFixed(2)}</td>
                <td><span class="badge bg-success">${product.stock}</span></td>
                <td><span class="badge bg-success">Active</span></td>
                <td>
                    <div class="btn-group btn-group-sm" role="group">
                        <a href="/admin/products/${product.id}" class="btn btn-outline-info" title="View">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="/admin/products/${product.id}/edit" class="btn btn-outline-warning" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="/admin/products/${product.id}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?')">
                            <input type="hidden" name="_token" value="${token}">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="btn btn-outline-danger" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            `;
            tbody.prepend(row);
            productCreateForm.reset();

            const modalElement = document.getElementById('productCreateModal');
            const modalInstance = bootstrap.Modal.getInstance(modalElement);
            if (modalInstance) {
                modalInstance.hide();
            }
        } catch (error) {
            showProductCreateAlert('danger', error.message || 'Unable to save product.');
        } finally {
            submitButton.disabled = false;
        }
    });
</script>
@endpush

<!-- Pagination -->
@if(isset($products) && method_exists($products, 'links'))
<div class="row mt-4">
    <div class="col-12">
        {{ $products->links() }}
    </div>
</div>
@endif

@endsection
