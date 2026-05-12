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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'species' => 'required|string|max:255',
            'breed' => 'nullable|string|max:255',
            'age' => 'required|integer|min:0|max:100',
            'price' => 'required|numeric|min:0|max:99999999',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:available,sold,pending',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images/pets'), $imageName);
            $data['image'] = 'images/pets/' . $imageName;
        }

        Pet::create($data);

        return redirect()->route('admin.pets.index')->with('success', 'Pet added successfully!');
    }

    public function show($id)
    {
        return Pet::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $pet = Pet::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'species' => 'required|string|max:255',
            'breed' => 'nullable|string|max:255',
            'age' => 'required|integer|min:0|max:100',
            'price' => 'required|numeric|min:0|max:99999999',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:available,sold,pending',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images/pets'), $imageName);
            $data['image'] = 'images/pets/' . $imageName;
        }

        $pet->update($data);
        
        return redirect()->route('admin.pets.index')->with('success', 'Pet updated successfully!');
    }

    public function destroy($id)
    {
        Pet::destroy($id);
        return response()->json(['message' => 'Deleted']);
    }

    public function addToCart(Request $request)
    {
        $request->validate(['pet_id' => 'required|exists:pets,id']);
        $pet = Pet::findOrFail($request->pet_id);

        if ($pet->status !== 'available') {
            return response()->json(['message' => 'Pet is no longer available.'], 422);
        }

        $cartKey = 'pet_' . $pet->id;
        $cart = session()->get('cart', []);

        if (!isset($cart[$cartKey])) {
            $cart[$cartKey] = [
                'type' => 'pet',
                'pet_id' => $pet->id,
                'name' => $pet->name,
                'price' => $pet->price,
                'quantity' => 1,
                'image' => $pet->image ? asset($pet->image) : null,
                'stock' => 1,
            ];
            session()->put('cart', $cart);
        } else {
            return response()->json(['message' => 'Pet is already in your cart.'], 422);
        }

        return response()->json([
            'message' => 'Pet added to cart!',
            'cart_count' => array_sum(array_column($cart, 'quantity'))
        ]);
    }
}