<?php

namespace App\Http\Controllers;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        // Check if this is an admin request
        if (request()->is('admin/*')) {
            $payments = Payment::with('order.user')
                ->latest()
                ->paginate(10);
            
            return view('admin.payments.index', compact('payments'));
        }
        
        // API response
        return Payment::with('order')->get();
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
        return Payment::create($request->all());
    }

    public function show($id)
    {
        return Payment::with('order')->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);
        $payment->update($request->all());
        return $payment;
    }

    public function destroy($id)
    {
        Payment::destroy($id);
        return response()->json(['message' => 'Deleted']);
    }
}