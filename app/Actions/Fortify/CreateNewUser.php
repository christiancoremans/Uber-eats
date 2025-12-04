<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class CreateNewUser implements CreatesNewUsers
{
    /**
     * Validate and create a new user.
     */
    public function create(array $input)
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required','string','email','max:255',
                Rule::unique(User::class),
            ],
            'password' => ['required', 'confirmed', Password::defaults()],
            'role' => ['required', Rule::in(['user','restaurant'])],
            'phone' => ['nullable','string','max:30'],
            'address_line1' => ['nullable','string','max:255'],
            'address_line2' => ['nullable','string','max:255'],
            'postcode' => ['nullable','string','max:20'],
            'city' => ['nullable','string','max:255'],
            'restaurant_name' => ['nullable','string','max:255'],
        ])->validate();

        // Als role restaurant is, kan je aanvullende verplichting doen:
        if ($input['role'] === 'restaurant' && empty($input['restaurant_name'])) {
            // extra validatie voor restaurants
            Validator::make($input, [
                'restaurant_name' => ['required','string','max:255'],
            ])->validate();
        }

        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'role' => $input['role'] ?? 'user',
            'phone' => $input['phone'] ?? null,
            'address_line1' => $input['address_line1'] ?? null,
            'address_line2' => $input['address_line2'] ?? null,
            'postcode' => $input['postcode'] ?? null,
            'city' => $input['city'] ?? null,
            'restaurant_name' => $input['restaurant_name'] ?? null,
        ]);
    }
}
