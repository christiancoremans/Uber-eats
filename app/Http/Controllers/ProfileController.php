<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function updateProfilePicture(Request $request)
    {
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $user = Auth::user();

        // Clear existing profile picture and add new one
        $user->clearMediaCollection('profile_picture');
        $user->addMediaFromRequest('profile_picture')->toMediaCollection('profile_picture');

        return back()->with('success', 'Profielfoto succesvol bijgewerkt!');
    }

    public function updateBanner(Request $request)
    {
        $request->validate([
            'banner' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120', // 5MB max
        ]);

        $user = Auth::user();

        // Clear existing banner and add new one
        $user->clearMediaCollection('banner');
        $user->addMediaFromRequest('banner')->toMediaCollection('banner');

        return back()->with('success', 'Banner succesvol bijgewerkt!');
    }
}
