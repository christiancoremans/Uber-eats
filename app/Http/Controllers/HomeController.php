<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Get all categories with restaurant count
        $categories = Category::withCount(['menuItems' => function($query) {
            $query->where('is_active', true);
        }])->where('is_active', true)
        ->orderBy('sort_order')
        ->orderBy('name')
        ->get();

        // Get restaurants (users with role = 'restaurant')
        $restaurants = User::where('role', 'restaurant')
            ->whereNotNull('restaurant_name')
            ->withCount(['menuItems' => function($query) {
                $query->where('is_active', true);
            }, 'reviews'])
            ->with(['categories' => function($query) {
                // Specify the table name for is_active
                $query->where('categories.is_active', true)  // Changed this line
                    ->orderBy('sort_order')
                    ->orderBy('name');
            }])
            ->with(['menuItems' => function($query) {
                $query->where('is_active', true)
                    ->selectRaw('restaurant_id,
                                AVG(price) as average_price,
                                AVG(preparation_time) as average_preparation_time,
                                MAX(is_featured) as has_featured_items')
                    ->groupBy('restaurant_id');
            }])
            ->with(['reviews' => function($query) {
                $query->selectRaw('restaurant_id, AVG(rating) as average_rating')
                    ->groupBy('restaurant_id');
            }])
            ->orderBy('restaurant_name')
            ->paginate(12);


        // Calculate averages for each restaurant
        $restaurants->getCollection()->transform(function ($restaurant) {
            $restaurant->average_price = $restaurant->menuItems->avg('price') ?? 0;
            $restaurant->average_preparation_time = round($restaurant->menuItems->avg('average_preparation_time') ?? 0);
            $restaurant->has_featured_items = $restaurant->menuItems->max('has_featured_items') ?? false;
            $restaurant->average_rating = $restaurant->reviews->avg('average_rating') ?? 0;
            $restaurant->reviews_count = $restaurant->reviews_count ?? 0;

            return $restaurant;
        });

        return view('home', compact('categories', 'restaurants'));
    }

    public function search(Request $request)
    {
        $query = $request->get('q');

        $restaurants = User::where('role', 'restaurant')
            ->whereNotNull('restaurant_name')
            ->where(function($q) use ($query) {
                $q->where('users.name', 'like', "%{$query}%") // Specify users table
                ->orWhere('restaurant_name', 'like', "%{$query}%")
                ->orWhere('city', 'like', "%{$query}%");
            })
            ->orWhereHas('categories', function($q) use ($query) {
                $q->where('categories.name', 'like', "%{$query}%"); // Specify categories table
            })
            ->orWhereHas('menuItems', function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                ->orWhere('description', 'like', "%{$query}%");
            })
            ->withCount(['menuItems' => function($q) {
                $q->where('is_active', true);
            }, 'reviews'])
            ->with(['categories' => function($q) {
                $q->where('categories.is_active', true)
                ->orderBy('sort_order')
                ->orderBy('name');
            }])
            ->with(['menuItems' => function($q) {
                $q->where('is_active', true)
                ->selectRaw('restaurant_id,
                            AVG(price) as average_price,
                            AVG(preparation_time) as average_preparation_time,
                            MAX(is_featured) as has_featured_items')
                ->groupBy('restaurant_id');
            }])
            ->with(['reviews' => function($q) {
                $q->selectRaw('restaurant_id, AVG(rating) as average_rating')
                ->groupBy('restaurant_id');
            }])
            ->orderBy('restaurant_name')
            ->paginate(12);

        // Calculate averages for each restaurant
        $restaurants->getCollection()->transform(function ($restaurant) {
            $restaurant->average_price = $restaurant->menuItems->avg('price') ?? 0;
            $restaurant->average_preparation_time = round($restaurant->menuItems->avg('average_preparation_time') ?? 0);
            $restaurant->has_featured_items = $restaurant->menuItems->max('has_featured_items') ?? false;
            $restaurant->average_rating = $restaurant->reviews->avg('average_rating') ?? 0;
            $restaurant->reviews_count = $restaurant->reviews_count ?? 0;

            return $restaurant;
        });

        $categories = Category::withCount('menuItems')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('home', compact('categories', 'restaurants', 'query'));
    }
}
