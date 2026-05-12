@extends('layouts.admin')

@section('page-title', 'Pets Management')
@section('title', 'All Pets')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Pets</h2>
            <a href="{{ route('admin.pets.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Pet
            </a>
        </div>
    </div>
</div>

<!-- Search Section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.pets.index') }}" class="row g-3">
                    <div class="col-md-6">
                        <input type="text" name="search" class="form-control" placeholder="Search pets by name or owner..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-grow-1">
                                <i class="fas fa-search"></i> Search
                            </button>
                            <a href="{{ route('admin.pets.index') }}" class="btn btn-secondary">
                                <i class="fas fa-redo"></i> Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Pets Table -->
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Pet Name</th>
                            <th>Breed/Type</th>
                            <th>Owner</th>
                            <th>Age</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pets as $pet)
                            <tr>
                                <td><strong>#{{ $pet->id }}</strong></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($pet->image)
                                            <img src="{{ asset($pet->image) }}" class="rounded me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                        @else
                                            <div class="bg-light rounded me-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <i class="fas fa-paw text-muted"></i>
                                            </div>
                                        @endif
                                        {{ $pet->name }}
                                    </div>
                                </td>
                                <td>{{ $pet->breed ?? 'N/A' }}</td>
                                <td>{{ 'N/A' }}</td>
                                <td>{{ $pet->age ?? 'N/A' }} years</td>
                                <td>
                                    <span class="badge bg-success">Active</span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('admin.pets.show', $pet->id) }}" class="btn btn-outline-info" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.pets.edit', $pet->id) }}" class="btn btn-outline-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.pets.destroy', $pet->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?')">
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
                                    <i class="fas fa-inbox"></i> No pets found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
