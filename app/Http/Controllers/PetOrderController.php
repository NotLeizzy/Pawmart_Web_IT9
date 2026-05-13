<?php

namespace App\Http\Controllers;
use App\Models\PetOrder;
use Illuminate\Http\Request;

class PetOrderController extends Controller
{
    public function index()
    {
        // Check if this is an admin request
        if (request()->is('admin/*')) {
            $petOrders = PetOrder::with(['pet', 'order.user'])
                ->latest()
                ->paginate(10);
            
            return view('admin.pet-orders.index', compact('petOrders'));
        }
        
        // API response
        return PetOrder::with('pet')->get();
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
        return PetOrder::create($request->all());
    }

    public function show($id)
    {
        $petOrder = PetOrder::with(['pet', 'order.user'])->findOrFail($id);

        if (request()->is('admin/*')) {
            return view('admin.pet-orders.show', compact('petOrder'));
        }

        return $petOrder;
    }

    public function update(Request $request, $id)
    {
        $petOrder = PetOrder::findOrFail($id);
        $petOrder->update($request->all());

        if (request()->is('admin/*')) {
            return redirect()->route('admin.pet-orders.index')->with('success', 'Pet order updated successfully.');
        }

        return $petOrder;
    }

    public function destroy($id)
    {
        PetOrder::destroy($id);
        return response()->json(['message' => 'Deleted']);
    }
}