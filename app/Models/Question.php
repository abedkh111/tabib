<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'question_text',
        'question_image',
        'specialization_id',
        'difficulty_level',
        'explanation',
        'reference',
        'time_limit',
        'points',
        'is_active',
        'is_approved',
        'created_by',
        'tags',
        'meta_data',
    ];

    protected $casts = [
        'difficulty_level' => 'string',
        'time_limit' => 'integer',
        'points' => 'integer',
        'is_active' => 'boolean',
        'is_approved' => 'boolean',
        'tags' => 'array',
        'meta_data' => 'array',
    ];

    const DIFFICULTY_LEVELS = [
        'easy' => 'سهل',
        'medium' => 'متوسط',
        'hard' => 'صعب',
        'expert' => 'خبير',
    ];

    /**
     * Get the specialization that the question belongs to.
     */
    public function specialization()
    {
        return $this->belongsTo(Specialization::class);
    }

    /**
     * Get the user who created the question.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Alias for creator relationship.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the answer options for this question.
     */
    public function options()
    {
        return $this->hasMany(QuestionOption::class)->orderBy('sort_order');
    }

    /**
     * Get the correct answer option.
     */
    public function correctOption()
    {
        return $this->hasOne(QuestionOption::class)->where('is_correct', true);
    }

    /**
     * Get the quiz attempts that include this question.
     */
    public function quizAttempts()
    {
        return $this->belongsToMany(QuizAttempt::class, 'quiz_attempt_answers')
                    ->withPivot(['selected_option_id', 'is_correct', 'time_taken'])
                    ->withTimestamps();
    }

    /**
     * Get the quizzes that include this question.
     */
    public function quizzes()
    {
        return $this->belongsToMany(Quiz::class, 'quiz_questions')
                    ->withPivot(['sort_order'])
                    ->withTimestamps();
    }

    /**
     * Scope to get only active questions.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to filter by specialization.
     */
    public function scopeBySpecialization($query, $specializationId)
    {
        return $query->where('specialization_id', $specializationId);
    }

    /**
     * Scope to filter by difficulty level.
     */
    public function scopeByDifficulty($query, $difficulty)
    {
        return $query->where('difficulty_level', $difficulty);
    }

    /**
     * Scope to filter by tags.
     */
    public function scopeByTags($query, $tags)
    {
        if (is_string($tags)) {
            $tags = [$tags];
        }
        
        return $query->where(function ($q) use ($tags) {
            foreach ($tags as $tag) {
                $q->orWhereJsonContains('tags', $tag);
            }
        });
    }

    /**
     * Get the question image URL.
     */
    public function getQuestionImageUrlAttribute()
    {
        if ($this->question_image) {
            return asset('storage/questions/' . $this->question_image);
        }
        
        return null;
    }

    /**
     * Get the difficulty level in Arabic.
     */
    public function getDifficultyLabelAttribute()
    {
        return self::DIFFICULTY_LEVELS[$this->difficulty_level] ?? $this->difficulty_level;
    }

    /**
     * Get the total times this question was answered.
     */
    public function getTotalAttemptsAttribute()
    {
        return $this->quizAttempts()->count();
    }

    /**
     * Get the success rate for this question.
     */
    public function getSuccessRateAttribute()
    {
        $total = $this->total_attempts;
        if ($total == 0) {
            return 0;
        }
        
        $correct = $this->quizAttempts()
                       ->wherePivot('is_correct', true)
                       ->count();
        
        return round(($correct / $total) * 100, 2);
    }

    /**
     * Get the average time taken to answer this question.
     */
    public function getAverageTimeAttribute()
    {
        return $this->quizAttempts()
                   ->avg('quiz_attempt_answers.time_taken') ?: 0;
    }

    /**
     * Check if the question has an image.
     */
    public function hasImage()
    {
        return !empty($this->question_image);
    }

    /**
     * Shuffle the answer options.
     */
    public function getShuffledOptionsAttribute()
    {
        return $this->options->shuffle();
    }
}
