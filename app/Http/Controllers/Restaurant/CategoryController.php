<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::where('restaurant_id', Auth::id())
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('restaurant.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('restaurant.categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean'
        ]);

        $validated['restaurant_id'] = Auth::id();
        $validated['slug'] = Str::slug($validated['name']);

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'restaurant_id' => Auth::id(),
            'is_default' => false,
            'is_active' => true,
        ]);

        return redirect()->route('restaurant.categories.index')
            ->with('success', 'Categorie succesvol aangemaakt!');
    }

    public function edit(Category $category)
    {
        $this->authorize('update', $category);

        return view('restaurant.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $this->authorize('update', $category);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean'
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $category->update($validated);

        return redirect()->route('restaurant.categories.index')
            ->with('success', 'Categorie succesvol bijgewerkt!');
    }

    public function destroy(Category $category)
    {
        $this->authorize('delete', $category);

        // Check if category has menu items
        if ($category->menuItems()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Kan categorie niet verwijderen omdat er menu items aan gekoppeld zijn.');
        }

        $category->delete();

        return redirect()->route('restaurant.categories.index')
            ->with('success', 'Categorie succesvol verwijderd!');
    }
}
