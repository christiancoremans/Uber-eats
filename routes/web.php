<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;

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

// Restaurant show route (placeholder) - this should be LAST as a catch-all
// Use 'id' as the parameter since we're passing User model instances
Route::get('/restaurant/{restaurant}', function($id) {
    return redirect()->route('home')->with('info', 'Restaurant detail pagina wordt nog ontwikkeld.');
})->name('restaurant.show');
