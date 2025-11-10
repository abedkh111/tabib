<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Specialization extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_ar',
        'name_en',
        'description',
        'icon',
        'color',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get the users for this specialization.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the courses for this specialization.
     */
    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    /**
     * Get the questions for this specialization.
     */
    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    /**
     * Scope to get only active specializations.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to order by sort order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    /**
     * Get the icon URL.
     */
    public function getIconUrlAttribute()
    {
        if ($this->icon) {
            return asset('storage/specializations/' . $this->icon);
        }
        
        return asset('images/default-specialization.png');
    }
}
