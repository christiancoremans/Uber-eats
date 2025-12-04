<?php

namespace App\Actions\Fortify;

use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    public function update($user, array $input)
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required','email','max:255', Rule::unique(\App\Models\User::class)->ignore($user->id)],
            'phone' => ['nullable','string','max:30'],
            'address_line1' => ['nullable','string','max:255'],
            'address_line2' => ['nullable','string','max:255'],
            'postcode' => ['nullable','string','max:20'],
            'city' => ['nullable','string','max:255'],
            'restaurant_name' => ['nullable','string','max:255'],
        ])->validate();

        $user->forceFill([
            'name' => $input['name'],
            'email' => $input['email'],
            'phone' => $input['phone'] ?? null,
            'address_line1' => $input['address_line1'] ?? null,
            'address_line2' => $input['address_line2'] ?? null,
            'postcode' => $input['postcode'] ?? null,
            'city' => $input['city'] ?? null,
            'restaurant_name' => $input['restaurant_name'] ?? null,
        ])->save();
    }
}
