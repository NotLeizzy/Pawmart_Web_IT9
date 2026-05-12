@extends('layouts.admin')

@section('page-title', isset($pet) ? 'Edit Pet' : 'Add Pet')
@section('title', isset($pet) ? 'Edit Pet' : 'Add Pet')

@section('content')
<div class="row mb-4">
    <div class="col-lg-8 mx-auto">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom">
                <h5 class="card-title mb-0">
                    <i class="fas fa-paw"></i>
                    {{ isset($pet) ? 'Edit Pet' : 'Add New Pet' }}
                </h5>
            </div>

            <div class="card-body">
                <form action="{{ isset($pet) ? route('admin.pets.update', $pet->id) : route('admin.pets.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    @if(isset($pet))
                        @method('PUT')
                    @endif

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Pet Name <span class="text-danger">*</span></label>
                            <input type="text" id="name" name="name" required
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $pet->name ?? '') }}">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="species" class="form-label">Species <span class="text-danger">*</span></label>
                            <input type="text" id="species" name="species" required
                                class="form-control @error('species') is-invalid @enderror"
                                value="{{ old('species', $pet->species ?? '') }}">
                            @error('species')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="breed" class="form-label">Breed / Type</label>
                            <input type="text" id="breed" name="breed"
                                class="form-control @error('breed') is-invalid @enderror"
                                value="{{ old('breed', $pet->breed ?? '') }}">
                            @error('breed')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="age" class="form-label">Age</label>
                            <input type="number" id="age" name="age" min="0"
                                class="form-control @error('age') is-invalid @enderror"
                                value="{{ old('age', $pet->age ?? '') }}">
                            @error('age')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    <div class="row g-3 mb-4">
                        <div class="col-md-12">
                            <label for="image" class="form-label">Pet Image</label>
                            <input type="file" id="image" name="image" accept="image/*"
                                class="form-control @error('image') is-invalid @enderror">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="price" class="form-label">Price (₱)</label>
                            <input type="number" step="0.01" id="price" name="price"
                                class="form-control @error('price') is-invalid @enderror"
                                value="{{ old('price', $pet->price ?? '') }}">
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="status" class="form-label">Status</label>
                            <select id="status" name="status" class="form-select @error('status') is-invalid @enderror">
                                <option value="available" {{ old('status', $pet->status ?? '') === 'available' ? 'selected' : '' }}>Available</option>
                                <option value="sold" {{ old('status', $pet->status ?? '') === 'sold' ? 'selected' : '' }}>Sold</option>
                                <option value="pending" {{ old('status', $pet->status ?? '') === 'pending' ? 'selected' : '' }}>Pending</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i>
                            {{ isset($pet) ? 'Update Pet' : 'Add Pet' }}
                        </button>
                        <a href="{{ route('admin.pets.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
