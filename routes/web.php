<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RestaurantMenuController; // Add this import

// Home routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search', [HomeController::class, 'search'])->name('home.search');

// Profile route
Route::get('/profile', function () {
    return view('profile');
})->name('profile')->middleware('auth');

// Clear session if there's a glitch
Route::get('/clear-session', function () {
    session()->flush();
    return redirect('/')->with('status', 'Session cleared!');
});

// Profile routes - should come before other middleware groups
Route::middleware(['auth'])->group(function () {
    Route::post('/profile/update-picture', [ProfileController::class, 'updateProfilePicture'])
        ->name('profile.update-picture');

    Route::post('/profile/update-banner', [ProfileController::class, 'updateBanner'])
        ->name('profile.update-banner');
});

// Restaurant menu routes - these must come BEFORE the catch-all route
Route::middleware(['auth'])->prefix('restaurant')->name('restaurant.')->group(function () {
    // Menu dashboard
    Route::get('/menu/dashboard', [\App\Http\Controllers\Restaurant\MenuController::class, 'dashboard'])
        ->name('menu.dashboard');

    // Categories routes
    Route::resource('categories', \App\Http\Controllers\Restaurant\CategoryController::class);

    // Menu items routes
    Route::resource('menu-items', \App\Http\Controllers\Restaurant\MenuItemController::class);
});

// Restaurant menu page - updated to show actual menu
Route::get('/restaurant/{restaurant}', [RestaurantMenuController::class, 'show'])
    ->name('restaurant.show');
