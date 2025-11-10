<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'short_description',
        'thumbnail',
        'instructor_id',
        'specialization_id',
        'price',
        'discounted_price',
        'duration_hours',
        'level',
        'language',
        'requirements',
        'what_you_learn',
        'is_published',
        'is_featured',
        'enrollment_limit',
        'starts_at',
        'ends_at',
        'certificate_template',
        'meta_keywords',
        'meta_description',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discounted_price' => 'decimal:2',
        'duration_hours' => 'integer',
        'is_published' => 'boolean',
        'is_featured' => 'boolean',
        'enrollment_limit' => 'integer',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'requirements' => 'array',
        'what_you_learn' => 'array',
    ];

    /**
     * Get the instructor of the course.
     */
    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    /**
     * Get the specialization of the course.
     */
    public function specialization()
    {
        return $this->belongsTo(Specialization::class);
    }

    /**
     * Get the lessons of the course.
     */
    public function lessons()
    {
        return $this->hasMany(Lesson::class)->orderBy('sort_order');
    }

    /**
     * Get the enrolled students.
     */
    public function enrolledStudents()
    {
        return $this->belongsToMany(User::class, 'course_enrollments')
                    ->withPivot(['enrolled_at', 'completed_at', 'progress'])
                    ->withTimestamps();
    }

    /**
     * Get the course reviews.
     */
    public function reviews()
    {
        return $this->hasMany(CourseReview::class);
    }

    /**
     * Get the course quizzes.
     */
    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }

    /**
     * Get the course certificates.
     */
    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    /**
     * Scope to get only published courses.
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Scope to get only featured courses.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope to get courses by specialization.
     */
    public function scopeBySpecialization($query, $specializationId)
    {
        return $query->where('specialization_id', $specializationId);
    }

    /**
     * Get the course thumbnail URL.
     */
    public function getThumbnailUrlAttribute()
    {
        if ($this->thumbnail) {
            return asset('storage/courses/' . $this->thumbnail);
        }
        
        return asset('images/default-course.jpg');
    }

    /**
     * Get the effective price (discounted if available).
     */
    public function getEffectivePriceAttribute()
    {
        return $this->discounted_price ?: $this->price;
    }

    /**
     * Check if the course is free.
     */
    public function isFree()
    {
        return $this->effective_price == 0;
    }

    /**
     * Get the average rating.
     */
    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?: 0;
    }

    /**
     * Get the total enrollments count.
     */
    public function getTotalEnrollmentsAttribute()
    {
        return $this->enrolledStudents()->count();
    }

    /**
     * Get the total lessons count.
     */
    public function getTotalLessonsAttribute()
    {
        return $this->lessons()->count();
    }

    /**
     * Check if enrollment is still available.
     */
    public function isEnrollmentAvailable()
    {
        if ($this->enrollment_limit && $this->total_enrollments >= $this->enrollment_limit) {
            return false;
        }

        if ($this->ends_at && $this->ends_at->isPast()) {
            return false;
        }

        return true;
    }
}
