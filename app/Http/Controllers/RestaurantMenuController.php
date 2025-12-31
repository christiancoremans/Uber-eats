<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Category;
use App\Models\MenuItem;
use Illuminate\Http\Request;

class RestaurantMenuController extends Controller
{
    public function show($id)
    {
        $restaurant = User::where('id', $id)
            ->where('role', 'restaurant')
            ->whereNotNull('restaurant_name')
            ->firstOrFail();

        // Get all active categories for this restaurant
        $categories = Category::whereHas('menuItems', function($query) use ($restaurant) {
                $query->where('restaurant_id', $restaurant->id)
                      ->where('is_active', true);
            })
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        // Get all active menu items for this restaurant with their category
        $menuItems = MenuItem::where('restaurant_id', $restaurant->id)
            ->where('is_active', true)
            ->with('category')
            ->orderBy('name')
            ->get();

        // Group menu items by category for display
        $menuByCategory = [];
        foreach ($menuItems as $item) {
            $categoryName = $item->category ? $item->category->name : 'Overig';
            if (!isset($menuByCategory[$categoryName])) {
                $menuByCategory[$categoryName] = [];
            }
            $menuByCategory[$categoryName][] = $item;
        }

        return view('restaurant.menu', compact('restaurant', 'categories', 'menuByCategory'));
    }
}
