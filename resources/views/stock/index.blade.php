@extends('layouts.admin')

@section('page-title', 'Stock Movements')
@section('title', 'Stock Movements')

@section('content')

<h3>Stock Movements</h3>

<table class="table table-hover">
    <thead>
        <tr>
            <th>Product</th>
            <th>Type</th>
            <th>Quantity</th>
            <th>Reason</th>
            <th>User</th>
        </tr>
    </thead>

    <tbody>
        @foreach($movements as $move)
        <tr>
            <td>{{ $move->product->name }}</td>
            <td>{{ $move->type }}</td>
            <td>{{ $move->quantity }}</td>
            <td>{{ $move->reason }}</td>
            <td>{{ $move->user->name ?? 'N/A' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection