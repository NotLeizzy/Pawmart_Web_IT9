<?php

namespace App\Http\Controllers;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderItemController extends Controller
{
    public function index()
    {
        return OrderItem::with('product')->get();
    }

    public function create()
    {
        abort(404);
    }

    public function edit($id)
    {
        abort(404);
    }

    public function store(Request $request)
    {
        return OrderItem::create($request->all());
    }

    public function show($id)
    {
        return OrderItem::with('product')->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $item = OrderItem::findOrFail($id);
        $item->update($request->all());
        return $item;
    }

    public function destroy($id)
    {
        OrderItem::destroy($id);
        return response()->json(['message' => 'Deleted']);
    }
}