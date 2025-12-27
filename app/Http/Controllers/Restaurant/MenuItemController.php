<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class MenuItemController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $menuItems = MenuItem::where('restaurant_id', Auth::id())
            ->with('category')
            ->orderBy('category_id')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('restaurant.menu-items.index', compact('menuItems'));
    }

    public function create()
    {
        // Get default categories (restaurant_id = NULL, is_default = true)
        // AND custom categories for this restaurant (restaurant_id = Auth::id())
        $categories = Category::where(function($query) {
            // Default categories
            $query->whereNull('restaurant_id')
                  ->where('is_default', true);
        })->orWhere(function($query) {
            // Custom categories for this restaurant
            $query->where('restaurant_id', Auth::id())
                  ->where('is_default', false);
        })
        ->where('is_active', true)
        ->orderBy('sort_order')
        ->orderBy('name')
        ->get();

        // Group categories for better display
        $defaultCategories = $categories->whereNull('restaurant_id')->where('is_default', true);
        $customCategories = $categories->where('restaurant_id', Auth::id())->where('is_default', false);

        return view('restaurant.menu-items.create', compact('categories', 'defaultCategories', 'customCategories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'ingredients' => 'nullable|string',
            'size' => 'nullable|in:small,medium,large',
            'in_stock' => 'boolean',
            'is_featured' => 'boolean',
            'sort_order' => 'nullable|integer',
            'preparation_time' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'image' => 'nullable|image|max:2048'
        ]);

        // Check if the selected category belongs to this restaurant OR is a default category
        $category = Category::findOrFail($validated['category_id']);

        // If category is not a default category and doesn't belong to this restaurant, reject
        if (!$category->is_default && $category->restaurant_id !== Auth::id()) {
            return back()->withErrors(['category_id' => 'Deze categorie behoort niet tot jouw restaurant.']);
        }

        $validated['restaurant_id'] = Auth::id();
        $validated['slug'] = Str::slug($validated['name']);

        $menuItem = MenuItem::create($validated);

        if ($request->hasFile('image')) {
            $menuItem->addMediaFromRequest('image')
                ->toMediaCollection('menu_item_images');
        }

        return redirect()->route('restaurant.menu-items.index')
            ->with('success', 'Menu item succesvol aangemaakt!');
    }

    public function edit(MenuItem $menuItem)
    {
        $this->authorize('update', $menuItem);

        // Get default categories (restaurant_id = NULL, is_default = true)
        // AND custom categories for this restaurant (restaurant_id = Auth::id())
        $categories = Category::where(function($query) {
            // Default categories
            $query->whereNull('restaurant_id')
                  ->where('is_default', true);
        })->orWhere(function($query) {
            // Custom categories for this restaurant
            $query->where('restaurant_id', Auth::id())
                  ->where('is_default', false);
        })
        ->where('is_active', true)
        ->orderBy('sort_order')
        ->orderBy('name')
        ->get();

        // Group categories for better display
        $defaultCategories = $categories->whereNull('restaurant_id')->where('is_default', true);
        $customCategories = $categories->where('restaurant_id', Auth::id())->where('is_default', false);

        return view('restaurant.menu-items.edit', compact('menuItem', 'categories', 'defaultCategories', 'customCategories'));
    }

    public function update(Request $request, MenuItem $menuItem)
    {
        $this->authorize('update', $menuItem);

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'ingredients' => 'nullable|string',
            'size' => 'nullable|in:small,medium,large',
            'in_stock' => 'boolean',
            'is_featured' => 'boolean',
            'sort_order' => 'nullable|integer',
            'preparation_time' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'image' => 'nullable|image|max:2048'
        ]);

        // Check if the selected category belongs to this restaurant OR is a default category
        $category = Category::findOrFail($validated['category_id']);

        // If category is not a default category and doesn't belong to this restaurant, reject
        if (!$category->is_default && $category->restaurant_id !== Auth::id()) {
            return back()->withErrors(['category_id' => 'Deze categorie behoort niet tot jouw restaurant.']);
        }

        $validated['slug'] = Str::slug($validated['name']);

        $menuItem->update($validated);

        if ($request->hasFile('image')) {
            $menuItem->clearMediaCollection('menu_item_images');
            $menuItem->addMediaFromRequest('image')
                ->toMediaCollection('menu_item_images');
        }

        return redirect()->route('restaurant.menu-items.index')
            ->with('success', 'Menu item succesvol bijgewerkt!');
    }

    public function destroy(MenuItem $menuItem)
    {
        $this->authorize('delete', $menuItem);

        $menuItem->delete();

        return redirect()->route('restaurant.menu-items.index')
            ->with('success', 'Menu item succesvol verwijderd!');
    }
}
