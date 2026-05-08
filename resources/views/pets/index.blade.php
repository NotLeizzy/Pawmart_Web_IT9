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
                <div class="card shadow-sm border-0 h-100">

                    <div class="card-body">
                        <h5 class="card-title">{{ $pet->name }}</h5>

                        <p class="mb-1"><strong>Species:</strong> {{ $pet->species }}</p>
                        <p class="mb-1"><strong>Breed:</strong> {{ $pet->breed }}</p>

                        <span class="badge bg-{{ $pet->status == 'available' ? 'success' : 'secondary' }}">
                            {{ ucfirst($pet->status) }}
                        </span>
                    </div>

                </div>
            </div>
        @endforeach
    </div>

</div>

@endsection