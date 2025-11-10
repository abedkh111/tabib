<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ResearchAndNews extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'type',
        'title_ar',
        'title_en',
        'content_ar',
        'content_en',
        'image',
        'source',
        'author',
        'published_date',
        'summary_ar',
        'summary_en',
        'tags',
        'is_published',
        'is_featured',
        'views_count',
        'created_by',
    ];

    protected $casts = [
        'tags' => 'array',
        'is_published' => 'boolean',
        'is_featured' => 'boolean',
        'published_date' => 'date',
        'views_count' => 'integer',
    ];

    const TYPES = [
        'medical_research' => 'أبحاث طبية',
        'scientific_news' => 'أخبار علمية',
        'medical_news' => 'أخبار طبية',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getTypeLabelAttribute()
    {
        return self::TYPES[$this->type] ?? $this->type;
    }
}
