<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lesson extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'course_id',
        'title',
        'description',
        'content',
        'video_url',
        'video_duration',
        'attachments',
        'sort_order',
        'is_published',
        'is_free',
        'lesson_type',
    ];

    protected $casts = [
        'video_duration' => 'integer',
        'attachments' => 'array',
        'sort_order' => 'integer',
        'is_published' => 'boolean',
        'is_free' => 'boolean',
    ];

    const LESSON_TYPES = [
        'video' => 'فيديو',
        'text' => 'نص',
        'pdf' => 'ملف PDF',
        'quiz' => 'اختبار',
        'live' => 'بث مباشر',
    ];

    /**
     * Get the course that this lesson belongs to.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the lesson progress for users.
     */
    public function progress()
    {
        return $this->hasMany(LessonProgress::class);
    }

    /**
     * Scope to get only published lessons.
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Scope to get only free lessons.
     */
    public function scopeFree($query)
    {
        return $query->where('is_free', true);
    }

    /**
     * Scope to order by sort order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    /**
     * Get the lesson type in Arabic.
     */
    public function getTypeLabel()
    {
        return self::LESSON_TYPES[$this->lesson_type] ?? $this->lesson_type;
    }

    /**
     * Get the video duration in human readable format.
     */
    public function getFormattedDurationAttribute()
    {
        if (!$this->video_duration) {
            return null;
        }

        $hours = floor($this->video_duration / 3600);
        $minutes = floor(($this->video_duration % 3600) / 60);
        $seconds = $this->video_duration % 60;

        $formatted = '';
        
        if ($hours > 0) {
            $formatted .= $hours . ':';
        }
        
        $formatted .= str_pad($minutes, 2, '0', STR_PAD_LEFT) . ':';
        $formatted .= str_pad($seconds, 2, '0', STR_PAD_LEFT);

        return $formatted;
    }

    /**
     * Check if user has completed this lesson.
     */
    public function isCompletedBy(User $user)
    {
        return $this->progress()
                   ->where('user_id', $user->id)
                   ->where('is_completed', true)
                   ->exists();
    }

    /**
     * Get user's progress percentage for this lesson.
     */
    public function getProgressFor(User $user)
    {
        $progress = $this->progress()
                        ->where('user_id', $user->id)
                        ->first();

        return $progress ? $progress->progress_percentage : 0;
    }
}
