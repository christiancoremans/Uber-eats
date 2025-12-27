<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class User extends Authenticatable implements HasMedia
{
    use HasFactory, Notifiable, InteractsWithMedia;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // Changed from user_type to role
        'phone',
        'address_line1',
        'address_line2',
        'postcode',
        'city',
        'restaurant_name'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Check if user is a restaurant
    public function isRestaurant()
    {
        return $this->role === 'restaurant';
    }

    // Check if user is a customer
    public function isCustomer()
    {
        return $this->role === 'user';
    }

    // Restaurant relationships
    public function categories()
    {
        if ($this->isRestaurant()) {
            return $this->hasMany(Category::class, 'restaurant_id');
        }
        return null;
    }

    public function menuItems()
    {
        if ($this->isRestaurant()) {
            return $this->hasMany(MenuItem::class, 'restaurant_id');
        }
        return null;
    }

    public function orders()
    {
        if ($this->isRestaurant()) {
            return $this->hasMany(Order::class, 'restaurant_id');
        } else {
            return $this->hasMany(Order::class, 'customer_id');
        }
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'restaurant_id');
    }

    // Register media collections for restaurant
    public function registerMediaCollections(): void
    {
        if ($this->isRestaurant()) {
            $this->addMediaCollection('restaurant_logo')
                ->singleFile()
                ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/jpg', 'image/webp']);
        }
    }
}
