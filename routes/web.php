<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', function () {
    return view('home');
});

// Profile route
Route::get('/profile', function () {
    return view('profile');
})->name('profile')->middleware('auth');

// Clear session if there's a glitch
Route::get('/clear-session', function () {
    session()->flush();
    return redirect('/')->with('status', 'Session cleared!');
});

// Restaurant menu routes
Route::middleware(['auth'])->prefix('restaurant')->name('restaurant.')->group(function () {
    // Menu dashboard
    Route::get('/menu/dashboard', [\App\Http\Controllers\Restaurant\MenuController::class, 'dashboard'])
        ->name('menu.dashboard');

    // Categories routes
    Route::resource('categories', \App\Http\Controllers\Restaurant\CategoryController::class);

    // Menu items routes
    Route::resource('menu-items', \App\Http\Controllers\Restaurant\MenuItemController::class);
});
