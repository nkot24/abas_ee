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
        'ingredients',
        'steps',
        'ingredients_en',
        'steps_en',
    ];

    protected static function booted(): void
    {
        static::saved(function (Recipe $recipe) {
            $titleChanged = $recipe->wasChanged('title');
            $ingChanged   = $recipe->wasChanged('ingredients');
            $stepsChanged = $recipe->wasChanged('steps');

            if (!$titleChanged && !$ingChanged && !$stepsChanged) {
                return;
            }

            $updates = [];

            try {
                $tr = new GoogleTranslate('en');
                $tr->setSource('lt');

                if ($titleChanged || !$recipe->title_en) {
                    $updates['title_en'] = $tr->translate($recipe->title);
                }

                if ($ingChanged || !$recipe->ingredients_en) {
                    $updates['ingredients_en'] = array_map(
                        fn($i) => ['item' => $tr->translate($i['item'])],
                        $recipe->ingredients ?? []
                    );
                }

                if ($stepsChanged || !$recipe->steps_en) {
                    $updates['steps_en'] = array_map(
                        fn($s) => ['step' => $tr->translate($s['step'])],
                        $recipe->steps ?? []
                    );
                }
            } catch (\Exception) {}

            if ($updates) {
                $recipe->updateQuietly($updates);
            }
        });
    }

    protected $casts = [
        'active'         => 'boolean',
        'ingredients'    => 'array',
        'steps'          => 'array',
        'ingredients_en' => 'array',
        'steps_en'       => 'array',
    ];

    protected $appends = ['image_url'];

    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? '/storage/' . $this->image : null;
    }
}
