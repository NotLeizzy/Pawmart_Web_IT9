@extends('layouts.admin')

@section('page-title', isset($product) ? 'Edit Product' : 'Create Product')
@section('title', isset($product) ? 'Edit Product' : 'Create Product')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom">
                <h5 class="card-title mb-0">
                    <i class="fas fa-cube"></i> 
                    {{ isset($product) ? 'Edit Product' : 'Create New Product' }}
                </h5>
            </div>

            <div class="card-body">
                <form action="{{ isset($product) ? route('admin.products.update', $product->id) : route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @if(isset($product))
                        @method('PUT')
                    @endif

                    <div class="row mb-4">
                        <!-- Product Name -->
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">
                                <i class="fas fa-tag"></i> Product Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" 
                                   value="{{ old('name', $product->name ?? '') }}" placeholder="Enter product name" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Category -->
                        <div class="col-md-6 mb-3">
                            <label for="category_id" class="form-label">
                                <i class="fas fa-folder"></i> Category <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                                <option value="">Select a category</option>
                                @foreach($categories ?? [] as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id ?? '') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-4">
                        <!-- Price -->
                        <div class="col-md-6 mb-3">
                            <label for="price" class="form-label">
                                <i class="fas fa-money-bill"></i> Price (₱) <span class="text-danger">*</span>
                            </label>
                            <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" id="price" name="price" 
                                   value="{{ old('price', $product->price ?? '') }}" placeholder="0.00" required>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Stock -->
                        <div class="col-md-6 mb-3">
                            <label for="stock" class="form-label">
                                <i class="fas fa-boxes"></i> Stock Quantity <span class="text-danger">*</span>
                            </label>
                            <input type="number" class="form-control @error('stock') is-invalid @enderror" id="stock" name="stock" 
                                   value="{{ old('stock', $product->stock ?? 0) }}" placeholder="0" required>
                            @error('stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <label for="description" class="form-label">
                            <i class="fas fa-align-left"></i> Description
                        </label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" 
                                  rows="4" placeholder="Enter product description">{{ old('description', $product->description ?? '') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Images -->
                    <div class="mb-4">
                        <label for="images" class="form-label">
                            <i class="fas fa-images"></i> Product Images
                        </label>
                        <input class="form-control @error('images.*') is-invalid @enderror" type="file" id="images" name="images[]" multiple accept="image/*">
                        <small class="text-muted">You can select multiple images. Leave empty if you do not wish to update images.</small>
                        @error('images.*')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Form Actions -->
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> 
                                    {{ isset($product) ? 'Update Product' : 'Create Product' }}
                                </button>
                                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Cancel
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
