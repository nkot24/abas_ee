<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    protected $fillable = [
        'name',
        'price',
        'category',
        'badge',
        'material',
        'height',
        'width',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
        'price'  => 'integer',
        'height' => 'integer',
        'width'  => 'integer',
    ];

    protected $appends = ['main_image_url'];
    protected $hidden  = ['mainImage'];

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order')->orderBy('id');
    }

    public function mainImage(): HasOne
    {
        // Prefer the image marked as main; fall back to the first uploaded image.
        return $this->hasOne(ProductImage::class)->orderByDesc('is_main')->orderBy('sort_order')->orderBy('id');
    }

    public function getMainImageUrlAttribute(): ?string
    {
        if ($this->relationLoaded('mainImage') && $this->mainImage) {
            return $this->mainImage->url;
        }
        return null;
    }
}
