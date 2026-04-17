<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageSetting extends Model
{
    protected $fillable = ['page', 'key', 'value'];

    public static function get(string $page, string $key, string $default = ''): string
    {
        return static::where('page', $page)->where('key', $key)->value('value') ?? $default;
    }

    public static function set(string $page, string $key, ?string $value): void
    {
        static::updateOrCreate(
            ['page' => $page, 'key' => $key],
            ['value' => $value ?? '']
        );
    }

    public static function getForPage(string $page): array
    {
        return static::where('page', $page)->pluck('value', 'key')->toArray();
    }
}
