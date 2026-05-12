@extends('layouts.app')

@section('title', 'Payment')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h2 class="mb-4">
                <i class="fas fa-credit-card me-2" style="color: var(--primary-color);"></i>
                Payment
            </h2>

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                    <h5 class="mb-0">Order Summary (Order #{{ $order->id }})</h5>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted">Delivery Address:</span>
                        <span class="fw-medium text-end" style="max-width: 60%;">{{ $order->delivery_address }}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <span class="fs-5 fw-bold">Amount to Pay:</span>
                        <span class="fs-4 fw-bold" style="color: var(--primary-color);">₱{{ number_format($order->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                    <h5 class="mb-0">Select Payment Method</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('payments.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="order_id" value="{{ $order->id }}">
                        
                        <div class="row g-3 mb-4">
                            <!-- Credit Card -->
                            <div class="col-md-6">
                                <input type="radio" class="btn-check" name="payment_method" id="method_cc" value="Credit Card" autocomplete="off" required>
                                <label class="btn btn-outline-primary w-100 p-3 h-100 d-flex flex-column align-items-center justify-content-center payment-card" for="method_cc">
                                    <i class="fas fa-credit-card fs-2 mb-2"></i>
                                    <span>Credit/Debit Card</span>
                                </label>
                            </div>

                            <!-- GCash -->
                            <div class="col-md-6">
                                <input type="radio" class="btn-check" name="payment_method" id="method_gcash" value="GCash" autocomplete="off" required>
                                <label class="btn btn-outline-primary w-100 p-3 h-100 d-flex flex-column align-items-center justify-content-center payment-card" for="method_gcash">
                                    <i class="fas fa-mobile-alt fs-2 mb-2"></i>
                                    <span>GCash</span>
                                </label>
                            </div>

                            <!-- PayPal -->
                            <div class="col-md-6">
                                <input type="radio" class="btn-check" name="payment_method" id="method_paypal" value="PayPal" autocomplete="off" required>
                                <label class="btn btn-outline-primary w-100 p-3 h-100 d-flex flex-column align-items-center justify-content-center payment-card" for="method_paypal">
                                    <i class="fab fa-paypal fs-2 mb-2"></i>
                                    <span>PayPal</span>
                                </label>
                            </div>

                            <!-- COD -->
                            <div class="col-md-6">
                                <input type="radio" class="btn-check" name="payment_method" id="method_cod" value="Cash on Delivery" autocomplete="off" required>
                                <label class="btn btn-outline-primary w-100 p-3 h-100 d-flex flex-column align-items-center justify-content-center payment-card" for="method_cod">
                                    <i class="fas fa-money-bill-wave fs-2 mb-2"></i>
                                    <span>Cash on Delivery</span>
                                </label>
                            </div>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary py-3 fs-5" style="background: linear-gradient(135deg, var(--primary-color) 0%, var(--accent-color) 100%); border: none;">
                                <i class="fas fa-lock me-2"></i> Pay ₱{{ number_format($order->total_amount, 2) }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
    .payment-card {
        border-color: #dee2e6;
        color: #6c757d;
        transition: all 0.2s;
    }
    .payment-card:hover {
        border-color: var(--primary-color);
        background-color: rgba(255, 159, 67, 0.05);
        color: var(--primary-color);
    }
    .btn-check:checked + .payment-card {
        border-color: var(--primary-color);
        border-width: 2px;
        background-color: rgba(255, 159, 67, 0.1);
        color: var(--primary-color);
    }
</style>
@endsection
