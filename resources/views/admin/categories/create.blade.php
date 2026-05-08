@extends('layouts.admin')

@section('page-title', isset($category) ? 'Edit Category' : 'Create Category')
@section('title', isset($category) ? 'Edit Category' : 'Create Category')

@section('content')
<div class="row mb-4">
    <div class="col-lg-8 mx-auto">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom">
                <h5 class="card-title mb-0">
                    <i class="fas fa-tag"></i> 
                    {{ isset($category) ? 'Edit Category' : 'Create New Category' }}
                </h5>
            </div>

            <div class="card-body">
                <form action="{{ isset($category) ? route('admin.categories.update', $category->id) : route('admin.categories.store') }}" method="POST">
                    @csrf
                    @if(isset($category))
                        @method('PUT')
                    @endif

                    <!-- Category Name -->
                    <div class="mb-4">
                        <label for="name" class="form-label">
                            <i class="fas fa-tag"></i> Category Name <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" 
                               value="{{ old('name', $category->name ?? '') }}" placeholder="e.g., Dog Food, Cat Toys, etc." required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <label for="description" class="form-label">
                            <i class="fas fa-align-left"></i> Description
                        </label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" 
                                  rows="4" placeholder="Enter category description">{{ old('description', $category->description ?? '') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Form Actions -->
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> 
                            {{ isset($category) ? 'Update Category' : 'Create Category' }}
                        </button>
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
