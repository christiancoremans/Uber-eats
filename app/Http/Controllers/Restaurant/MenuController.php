<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MenuController extends Controller
{
    public function dashboard()
    {
        $restaurant = Auth::user();
        $categoriesCount = $restaurant->categories()->count();
        $menuItemsCount = $restaurant->menuItems()->count();
        $activeMenuItemsCount = $restaurant->menuItems()->where('is_active', true)->count();

        return view('restaurant.menu.dashboard', compact(
            'categoriesCount',
            'menuItemsCount',
            'activeMenuItemsCount'
        ));
    }
}
