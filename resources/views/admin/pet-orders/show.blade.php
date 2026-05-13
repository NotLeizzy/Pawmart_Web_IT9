@extends('layouts.admin')

@section('page-title', "Pet Order #{$petOrder->id}")
@section('title', "Pet Order #{$petOrder->id}")

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.pet-orders.index') }}" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-left"></i> Back to Pet Orders
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <!-- Pet Details -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0"><i class="fas fa-paw me-2 text-primary"></i> Pet Details</h5>
            </div>
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-4 text-center">
                        @if($petOrder->pet && $petOrder->pet->image)
                            <img src="{{ $petOrder->pet->image }}" alt="{{ $petOrder->pet->name }}" class="img-fluid rounded shadow-sm" style="max-height: 200px;">
                        @else
                            <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 200px;">
                                <i class="fas fa-paw fa-4x text-muted"></i>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-8">
                        <table class="table table-borderless mb-0">
                            <tr>
                                <th style="width: 150px;">Name:</th>
                                <td>{{ $petOrder->pet->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Species:</th>
                                <td>{{ ucfirst($petOrder->pet->species ?? 'N/A') }}</td>
                            </tr>
                            <tr>
                                <th>Breed:</th>
                                <td>{{ $petOrder->pet->breed ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Price:</th>
                                <td class="fs-5 fw-bold text-primary">₱{{ number_format($petOrder->price, 2) }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Associated Order -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0"><i class="fas fa-file-invoice me-2 text-info"></i> Order Information</h5>
            </div>
            <div class="card-body">
                @if($petOrder->order)
                    <table class="table table-borderless mb-0">
                        <tr>
                            <th style="width: 150px;">Order ID:</th>
                            <td>
                                <a href="{{ route('admin.orders.show', $petOrder->order_id) }}" class="fw-bold">
                                    #{{ $petOrder->order_id }}
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <th>Customer:</th>
                            <td>{{ $petOrder->order->user->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td>{{ $petOrder->order->user->email ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Order Status:</th>
                            <td>
                                <span class="badge bg-secondary">{{ ucfirst($petOrder->order->status) }}</span>
                            </td>
                        </tr>
                        <tr>
                            <th>Date:</th>
                            <td>{{ $petOrder->created_at->format('F d, Y h:i A') }}</td>
                        </tr>
                    </table>
                @else
                    <div class="alert alert-warning mb-0">No order details found.</div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Actions or Quick Info -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    @if($petOrder->order_id)
                        <a href="{{ route('admin.orders.show', $petOrder->order_id) }}" class="btn btn-primary">
                            <i class="fas fa-eye me-1"></i> View Full Order
                        </a>
                    @endif
                    <a href="{{ route('admin.pets.show', $petOrder->pet_id) }}" class="btn btn-outline-primary">
                        <i class="fas fa-paw me-1"></i> View Pet Profile
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
