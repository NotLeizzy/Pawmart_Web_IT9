@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h2 class="mb-4">
                <i class="fas fa-box-open me-2" style="color: var(--primary-color);"></i>
                Checkout
            </h2>

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                    <h5 class="mb-0">Order Summary</h5>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0">
                            <tbody>
                                @foreach($cart as $item)
                                    <tr>
                                        <td style="width: 80px;">
                                            @if($item['image'])
                                                <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="img-fluid rounded" style="width: 60px; height: 60px; object-fit: cover;">
                                            @else
                                                <div class="rounded bg-light d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                                    <i class="fas fa-image text-muted"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <h6 class="mb-0">{{ $item['name'] }}</h6>
                                            <small class="text-muted">Qty: {{ $item['quantity'] }} × ₱{{ number_format($item['price'], 2) }}</small>
                                        </td>
                                        <td class="text-end fw-bold">
                                            ₱{{ number_format($item['price'] * $item['quantity'], 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot style="border-top: 1px solid #dee2e6;">
                                <tr>
                                    <td colspan="2" class="text-end pt-3"><strong>Total Amount:</strong></td>
                                    <td class="text-end pt-3 text-primary fs-5 fw-bold" style="color: var(--primary-color) !important;">
                                        ₱{{ number_format($total, 2) }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                    <h5 class="mb-0">Delivery Details</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('orders.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="delivery_address" class="form-label fw-bold">Complete Delivery Address</label>
                            <textarea class="form-control @error('delivery_address') is-invalid @enderror" 
                                      id="delivery_address" 
                                      name="delivery_address" 
                                      rows="3" 
                                      required
                                      placeholder="House/Unit No., Street, Barangay, City, Province, Zip Code">{{ old('delivery_address') }}</textarea>
                            @error('delivery_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                            <a href="{{ route('cart.index') }}" class="text-decoration-none text-muted">
                                <i class="fas fa-arrow-left me-1"></i> Back to Cart
                            </a>
                            <button type="submit" class="btn btn-primary px-5 py-2" style="background: linear-gradient(135deg, var(--primary-color) 0%, var(--accent-color) 100%); border: none;">
                                Place Order & Proceed to Pay <i class="fas fa-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.25rem rgba(255, 159, 67, 0.25);
    }
</style>
@endsection
