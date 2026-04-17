<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Recipe extends Model
{
    protected $fillable = [
        'title',
        'category',
        'active',
        'image',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    protected $appends = ['image_url'];

    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? '/storage/' . $this->image : null;
    }
}
