<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class MenuItem extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'restaurant_id',
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'ingredients',
        'size',
        'in_stock',
        'is_featured',
        'sort_order',
        'preparation_time',
        'is_active'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'in_stock' => 'boolean',
        'is_featured' => 'boolean',
        'is_active' => 'boolean'
    ];

    public function restaurant()
    {
        return $this->belongsTo(User::class, 'restaurant_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('menu_item_images')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/jpg', 'image/webp']);
    }

    public function getImageUrlAttribute()
    {
        return $this->getFirstMediaUrl('menu_item_images') ?: asset('images/default-menu-item.jpg');
    }

    public function getFormattedPriceAttribute()
    {
        return '€' . number_format($this->price, 2, ',', '.');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($menuItem) {
            if (empty($menuItem->slug)) {
                $menuItem->slug = \Str::slug($menuItem->name);
            }
        });
    }
}
