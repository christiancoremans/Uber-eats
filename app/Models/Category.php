<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'restaurant_id',
        'name',
        'slug',
        'description',
        'sort_order',
        'is_active'
    ];

    public function getRestaurantsCountAttribute()
    {
        return $this->menuItems()
            ->where('is_active', true)
            ->distinct('restaurant_id')
            ->count('restaurant_id');
    }

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function restaurant()
    {
        return $this->belongsTo(User::class, 'restaurant_id');
    }

    public function menuItems()
    {
        return $this->hasMany(MenuItem::class);
    }

    public function activeMenuItems()
    {
        return $this->hasMany(MenuItem::class)->where('is_active', true);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = \Str::slug($category->name);
            }
        });
    }
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('category_images')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/jpg', 'image/webp']);
    }
}
