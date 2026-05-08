@extends('layouts.admin')

@section('page-title', 'Stock Management')
@section('title', 'Stock Movements')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Stock Movements</h2>
            <div class="btn-group">
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#stockInModal">
                    <i class="fas fa-arrow-down"></i> Stock In
                </button>
                <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#stockOutModal">
                    <i class="fas fa-arrow-up"></i> Stock Out
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Quick Stats -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm" style="border-left: 4px solid #28a745;">
            <div class="card-body">
                <h6 class="card-title text-muted">Total Stock In</h6>
                <h3 style="color: #28a745;">
                    <i class="fas fa-arrow-down"></i> {{ $totalStockIn ?? 0 }}
                </h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm" style="border-left: 4px solid #dc3545;">
            <div class="card-body">
                <h6 class="card-title text-muted">Total Stock Out</h6>
                <h3 style="color: #dc3545;">
                    <i class="fas fa-arrow-up"></i> {{ $totalStockOut ?? 0 }}
                </h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm" style="border-left: 4px solid #ff9f43;">
            <div class="card-body">
                <h6 class="card-title text-muted">Net Movement</h6>
                <h3 style="color: #ff9f43;">
                    <i class="fas fa-exchange-alt"></i> {{ ($totalStockIn ?? 0) - ($totalStockOut ?? 0) }}
                </h3>
            </div>
        </div>
    </div>
</div>

<!-- Stock Movements Table -->
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom">
                <h5 class="card-title mb-0">Recent Movements</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Product</th>
                            <th>Type</th>
                            <th>Quantity</th>
                            <th>Notes</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($movements as $movement)
                            <tr>
                                <td><strong>#{{ $movement->id }}</strong></td>
                                <td>{{ $movement->product->name ?? 'N/A' }}</td>
                                <td>
                                    @if($movement->type == 'in')
                                        <span class="badge bg-success">
                                            <i class="fas fa-arrow-down"></i> Stock In
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            <i class="fas fa-arrow-up"></i> Stock Out
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <strong>
                                        @if($movement->type == 'in')
                                            <span class="text-success">+{{ $movement->quantity }}</span>
                                        @else
                                            <span class="text-danger">-{{ $movement->quantity }}</span>
                                        @endif
                                    </strong>
                                </td>
                                <td>{{ $movement->notes ?? 'N/A' }}</td>
                                <td>{{ $movement->created_at->format('M d, Y H:i') ?? 'N/A' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="fas fa-inbox"></i> No stock movements yet
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Stock In Modal -->
<div class="modal fade" id="stockInModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-arrow-down"></i> Stock In
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.stock-movements.in') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="product_in" class="form-label">Product <span class="text-danger">*</span></label>
                        <select class="form-select" name="product_id" required>
                            <option value="">Select Product</option>
                            @foreach($products ?? [] as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="quantity_in" class="form-label">Quantity <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="quantity" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label for="notes_in" class="form-label">Notes</label>
                        <textarea class="form-control" name="notes" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check"></i> Confirm Stock In
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Stock Out Modal -->
<div class="modal fade" id="stockOutModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-arrow-up"></i> Stock Out
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.stock-movements.out') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="product_out" class="form-label">Product <span class="text-danger">*</span></label>
                        <select class="form-select" name="product_id" required>
                            <option value="">Select Product</option>
                            @foreach($products ?? [] as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="quantity_out" class="form-label">Quantity <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="quantity" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label for="notes_out" class="form-label">Notes</label>
                        <textarea class="form-control" name="notes" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-check"></i> Confirm Stock Out
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
