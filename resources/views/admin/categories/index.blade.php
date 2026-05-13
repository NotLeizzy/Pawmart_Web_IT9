@extends('layouts.admin')

@section('page-title', 'Categories Management')
@section('title', 'Categories')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Categories</h2>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#categoryCreateModal">
                <i class="fas fa-plus"></i> Add Category
            </button>
        </div>
    </div>
</div>

<!-- Categories Table -->
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Category Name</th>
                            <th>Products Count</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="categoryTableBody">
                        @forelse($categories as $category)
                        <tr>
                            <td><strong>#{{ $category->id }}</strong></td>
                            <td>{{ $category->name }}</td>
                            <td>
                                <span class="badge bg-info">
                                    {{ $category->products_count ?? 0 }} products
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-success">Active</span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-outline-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?')">
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
                            <td colspan="5" class="text-center text-muted py-4">
                                <i class="fas fa-tags"></i> No categories found
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Category Create Modal -->
<div class="modal fade" id="categoryCreateModal" tabindex="-1" aria-labelledby="categoryCreateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="categoryCreateModalLabel">Add New Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="categoryCreateForm">
                @csrf
                <div class="modal-body">
                    <div id="categoryCreateAlert"></div>
                    <div class="mb-3">
                        <label for="category_name" class="form-label">Category Name</label>
                        <input type="text" id="category_name" name="name" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Category</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const categoryCreateForm = document.getElementById('categoryCreateForm');

    function showCategoryAlert(type, message) {
        document.getElementById('categoryCreateAlert').innerHTML = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
    }

    categoryCreateForm.addEventListener('submit', async function(event) {
        event.preventDefault();

        const submitButton = categoryCreateForm.querySelector('button[type="submit"]');
        submitButton.disabled = true;

        const formData = new FormData(categoryCreateForm);
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        try {
            const url = @json(route('admin.categories.store'));

            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                },
                body: formData,
            });

            const contentType = response.headers.get("content-type");

            let data;

            if (contentType && contentType.includes("application/json")) {
                data = await response.json();
            } else {
                const text = await response.text();
                throw new Error(text);
            }

            if (!response.ok) {
                showCategoryAlert('danger', data.message || 'Unable to create category.');
                submitButton.disabled = false;
                return;
            }

            showCategoryAlert('success', data.message || 'Category created successfully.');

            const category = data;
            const tbody = document.getElementById('categoryTableBody');
            const row = document.createElement('tr');
            row.innerHTML = `
                <td><strong>#${category.id}</strong></td>
                <td>${category.name}</td>
                <td><span class="badge bg-info">0 products</span></td>
                <td><span class="badge bg-success">Active</span></td>
                <td>
                    <div class="btn-group btn-group-sm" role="group">
                        <a href="/admin/categories/${category.id}" class="btn btn-outline-info" title="View">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="/admin/categories/${category.id}/edit" class="btn btn-outline-warning" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="/admin/categories/${category.id}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?')">
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
            categoryCreateForm.reset();

            const modalElement = document.getElementById('categoryCreateModal');
            const modalInstance = bootstrap.Modal.getInstance(modalElement);
            if (modalInstance) {
                modalInstance.hide();
            }
        } catch (error) {
            showCategoryAlert('danger', error.message || 'Unable to create category.');
        } finally {
            submitButton.disabled = false;
        }
    });
</script>
@endpush

@endsection