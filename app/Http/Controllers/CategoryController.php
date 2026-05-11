<?php

namespace App\Http\Controllers;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        // Check if this is an admin request
        if (request()->is('admin/*')) {
            $categories = Category::withCount('products')
                ->when(request('search'), function($query) {
                    $query->where('name', 'like', '%' . request('search') . '%');
                })
                ->paginate(10);
            
            return view('admin.categories.index', compact('categories'));
        }
        
        // API response
        return Category::all();
    }

    public function create()
    {
        if (! request()->is('admin/*')) {
            abort(404);
        }

        return view('admin.categories.create');
    }

    public function edit($id)
    {
        if (! request()->is('admin/*')) {
            abort(404);
        }

        $category = Category::findOrFail($id);

        return view('admin.categories.create', compact('category'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $category = Category::create($validated);

        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully!');
    }

    public function show($id)
    {
        return Category::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $category = Category::findOrFail($id);
        $category->update($validated);

        return response()->json($category);
    }

    public function destroy($id)
    {
        Category::destroy($id);
        return response()->json(['message' => 'Deleted']);
    }
}