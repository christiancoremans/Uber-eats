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
        return $this->hasManyThrough(
            Category::class,
            MenuItem::class,
            'restaurant_id', // Foreign key on menu_items table
            'id', // Foreign key on categories table
            'id', // Local key on users table
            'category_id' // Local key on menu_items table
        )->distinct();
    }

    public function menuItems()
    {
        return $this->hasMany(MenuItem::class, 'restaurant_id');
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

    // Register media collections
    public function registerMediaCollections(): void
    {
        // Add profile picture for all users
        $this->addMediaCollection('profile_picture')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/jpg', 'image/webp']);

        // Add banner for all users
        $this->addMediaCollection('banner')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/jpg', 'image/webp']);

        // Add restaurant logo only for restaurants
        if ($this->isRestaurant()) {
            $this->addMediaCollection('restaurant_logo')
                ->singleFile()
                ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/jpg', 'image/webp']);
        }
    }

    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    public function getReviewsCountAttribute()
    {
        return $this->reviews()->count();
    }

    public function getMenuItemsCountAttribute()
    {
        return $this->menuItems()->where('is_active', true)->count();
    }
    public function reviewsGiven()
    {
        return $this->hasMany(Review::class, 'customer_id');
    }

    public function reviewsReceived()
    {
        return $this->hasMany(Review::class, 'restaurant_id');
    }
}
