<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Stichoza\GoogleTranslate\GoogleTranslate;
class Recipe extends Model
{
    protected $fillable = [
        'title',
        'title_en',
        'category',
        'active',
        'image',
    ];

    protected static function booted(): void
    {
        static::saved(function (Recipe $recipe) {
            if ($recipe->title_en && !$recipe->wasChanged('title')) {
                return;
            }
            try {
                $tr = new GoogleTranslate('en');
                $tr->setSource('lt');
                $recipe->updateQuietly(['title_en' => $tr->translate($recipe->title)]);
            } catch (\Exception) {}
        });
    }

    protected $casts = [
        'active' => 'boolean',
    ];

    protected $appends = ['image_url'];

    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? '/storage/' . $this->image : null;
    }
}
