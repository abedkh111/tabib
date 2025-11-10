<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SectionTitle extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'title_ar',
        'title_en',
        'description_ar',
        'description_en',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public static function getTitle($key, $lang = 'ar')
    {
        $section = self::where('key', $key)->where('is_active', true)->first();
        if (!$section) {
            return null;
        }
        return $lang === 'ar' ? $section->title_ar : ($section->title_en ?? $section->title_ar);
    }
}
