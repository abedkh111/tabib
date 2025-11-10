<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quiz extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'specialization_id',
        'course_id',
        'created_by',
        'time_limit',
        'total_questions',
        'passing_score',
        'max_attempts',
        'shuffle_questions',
        'shuffle_options',
        'show_results_immediately',
        'show_correct_answers',
        'is_active',
        'is_public',
        'difficulty_level',
        'instructions',
        'starts_at',
        'ends_at',
    ];

    protected $casts = [
        'time_limit' => 'integer',
        'total_questions' => 'integer',
        'passing_score' => 'integer',
        'max_attempts' => 'integer',
        'shuffle_questions' => 'boolean',
        'shuffle_options' => 'boolean',
        'show_results_immediately' => 'boolean',
        'show_correct_answers' => 'boolean',
        'is_active' => 'boolean',
        'is_public' => 'boolean',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    const DIFFICULTY_LEVELS = [
        'easy' => 'سهل',
        'medium' => 'متوسط',
        'hard' => 'صعب',
        'mixed' => 'مختلط',
    ];

    /**
     * Get the specialization that the quiz belongs to.
     */
    public function specialization()
    {
        return $this->belongsTo(Specialization::class);
    }

    /**
     * Get the course that the quiz belongs to (optional).
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the user who created the quiz.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the questions associated with this quiz.
     */
    public function questions()
    {
        return $this->belongsToMany(Question::class, 'quiz_questions')
                    ->withPivot(['sort_order'])
                    ->orderBy('quiz_questions.sort_order');
    }

    /**
     * Get the quiz attempts.
     */
    public function attempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }

    /**
     * Scope to get only active quizzes.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get only public quizzes.
     */
    public function scopePublic($query)
    {
        return $query->where('is_public', true);
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
     * Check if the quiz is available for taking.
     */
    public function isAvailable()
    {
        if (!$this->is_active) {
            return false;
        }

        $now = now();

        if ($this->starts_at && $this->starts_at->isFuture()) {
            return false;
        }

        if ($this->ends_at && $this->ends_at->isPast()) {
            return false;
        }

        return true;
    }

    /**
     * Check if a user can take this quiz.
     */
    public function canUserTake(User $user)
    {
        if (!$this->isAvailable()) {
            return false;
        }

        if ($this->max_attempts > 0) {
            $userAttempts = $this->attempts()
                                ->where('user_id', $user->id)
                                ->count();
            
            if ($userAttempts >= $this->max_attempts) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get the difficulty level in Arabic.
     */
    public function getDifficultyLabelAttribute()
    {
        return self::DIFFICULTY_LEVELS[$this->difficulty_level] ?? $this->difficulty_level;
    }

    /**
     * Get the total attempts count.
     */
    public function getTotalAttemptsAttribute()
    {
        return $this->attempts()->count();
    }

    /**
     * Get the average score.
     */
    public function getAverageScoreAttribute()
    {
        return $this->attempts()->avg('score') ?: 0;
    }

    /**
     * Get the pass rate percentage.
     */
    public function getPassRateAttribute()
    {
        $total = $this->total_attempts;
        if ($total == 0) {
            return 0;
        }

        $passed = $this->attempts()
                      ->where('score', '>=', $this->passing_score)
                      ->count();

        return round(($passed / $total) * 100, 2);
    }

    /**
     * Generate random questions for the quiz.
     */
    public function generateRandomQuestions($count = null)
    {
        $count = $count ?: $this->total_questions;
        
        $query = Question::active()
                        ->where('specialization_id', $this->specialization_id);

        if ($this->difficulty_level !== 'mixed') {
            $query->where('difficulty_level', $this->difficulty_level);
        }

        $questions = $query->inRandomOrder()
                          ->limit($count)
                          ->get();

        // Attach questions to quiz with sort order
        $this->questions()->detach();
        foreach ($questions as $index => $question) {
            $this->questions()->attach($question->id, [
                'sort_order' => $index + 1
            ]);
        }

        return $questions;
    }

    /**
     * Get questions for a specific attempt (with shuffling if enabled).
     */
    public function getQuestionsForAttempt()
    {
        $questions = $this->questions;

        if ($this->shuffle_questions) {
            $questions = $questions->shuffle();
        }

        return $questions;
    }
}
