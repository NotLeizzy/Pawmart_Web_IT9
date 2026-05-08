<?php

namespace App\Http\Controllers;
use App\Models\Pet;
use Illuminate\Http\Request;

class PetController extends Controller
{
    public function index()
    {
        // Check if this is an admin request
        if (request()->is('admin/*')) {
            $pets = Pet::latest()
                ->paginate(10);
            
            return view('admin.pets.index', compact('pets'));
        }
        
        // Customer view response
        $pets = Pet::where('status', '!=', 'sold')
            ->latest()
            ->paginate(10);
        
        return view('pets.index', compact('pets'));
    }

    public function create()
    {
        if (! request()->is('admin/*')) {
            abort(404);
        }

        return view('admin.pets.create');
    }

    public function edit($id)
    {
        if (! request()->is('admin/*')) {
            abort(404);
        }

        $pet = Pet::findOrFail($id);

        return view('admin.pets.create', compact('pet'));
    }

    public function store(Request $request)
    {
        return Pet::create($request->all());
    }

    public function show($id)
    {
        return Pet::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $pet = Pet::findOrFail($id);
        $pet->update($request->all());
        return $pet;
    }

    public function destroy($id)
    {
        Pet::destroy($id);
        return response()->json(['message' => 'Deleted']);
    }
}