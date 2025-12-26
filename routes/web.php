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
