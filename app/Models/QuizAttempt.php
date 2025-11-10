<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'quiz_id',
        'started_at',
        'completed_at',
        'score',
        'total_questions',
        'correct_answers',
        'wrong_answers',
        'skipped_answers',
        'time_taken',
        'is_passed',
        'attempt_number',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'score' => 'integer',
        'total_questions' => 'integer',
        'correct_answers' => 'integer',
        'wrong_answers' => 'integer',
        'skipped_answers' => 'integer',
        'time_taken' => 'integer',
        'is_passed' => 'boolean',
        'attempt_number' => 'integer',
    ];

    /**
     * Get the user who made this attempt.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the quiz for this attempt.
     */
    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    /**
     * Get the answers for this attempt.
     */
    public function answers()
    {
        return $this->hasMany(QuizAttemptAnswer::class);
    }

    /**
     * Get the questions answered in this attempt.
     */
    public function questions()
    {
        return $this->belongsToMany(Question::class, 'quiz_attempt_answers')
                    ->withPivot(['selected_option_id', 'is_correct', 'time_taken'])
                    ->withTimestamps();
    }

    /**
     * Scope to get only completed attempts.
     */
    public function scopeCompleted($query)
    {
        return $query->whereNotNull('completed_at');
    }

    /**
     * Scope to get only passed attempts.
     */
    public function scopePassed($query)
    {
        return $query->where('is_passed', true);
    }

    /**
     * Scope to get only failed attempts.
     */
    public function scopeFailed($query)
    {
        return $query->where('is_passed', false);
    }

    /**
     * Check if the attempt is completed.
     */
    public function isCompleted()
    {
        return !is_null($this->completed_at);
    }

    /**
     * Check if the attempt is in progress.
     */
    public function isInProgress()
    {
        return !is_null($this->started_at) && is_null($this->completed_at);
    }

    /**
     * Get the percentage score.
     */
    public function getPercentageScoreAttribute()
    {
        if ($this->total_questions == 0) {
            return 0;
        }

        return round(($this->correct_answers / $this->total_questions) * 100, 2);
    }

    /**
     * Get the time taken in human readable format.
     */
    public function getFormattedTimeAttribute()
    {
        if (!$this->time_taken) {
            return '0 ثانية';
        }

        $hours = floor($this->time_taken / 3600);
        $minutes = floor(($this->time_taken % 3600) / 60);
        $seconds = $this->time_taken % 60;

        $formatted = '';
        
        if ($hours > 0) {
            $formatted .= $hours . ' ساعة ';
        }
        
        if ($minutes > 0) {
            $formatted .= $minutes . ' دقيقة ';
        }
        
        if ($seconds > 0 || empty($formatted)) {
            $formatted .= $seconds . ' ثانية';
        }

        return trim($formatted);
    }

    /**
     * Get the grade based on score.
     */
    public function getGradeAttribute()
    {
        $percentage = $this->percentage_score;

        if ($percentage >= 90) {
            return 'ممتاز';
        } elseif ($percentage >= 80) {
            return 'جيد جداً';
        } elseif ($percentage >= 70) {
            return 'جيد';
        } elseif ($percentage >= 60) {
            return 'مقبول';
        } else {
            return 'ضعيف';
        }
    }

    /**
     * Calculate and update the attempt score.
     */
    public function calculateScore()
    {
        $correctAnswers = $this->answers()->where('is_correct', true)->count();
        $totalQuestions = $this->answers()->count();
        $wrongAnswers = $totalQuestions - $correctAnswers;

        $this->update([
            'correct_answers' => $correctAnswers,
            'wrong_answers' => $wrongAnswers,
            'total_questions' => $totalQuestions,
            'score' => $this->percentage_score,
            'is_passed' => $this->percentage_score >= $this->quiz->passing_score,
        ]);

        return $this;
    }

    /**
     * Complete the attempt.
     */
    public function complete()
    {
        if ($this->isCompleted()) {
            return $this;
        }

        $timeTaken = now()->diffInSeconds($this->started_at);

        $this->update([
            'completed_at' => now(),
            'time_taken' => $timeTaken,
        ]);

        $this->calculateScore();

        return $this;
    }

    /**
     * Get the remaining time for this attempt.
     */
    public function getRemainingTime()
    {
        if ($this->isCompleted() || !$this->quiz->time_limit) {
            return 0;
        }

        $elapsed = now()->diffInSeconds($this->started_at);
        $remaining = ($this->quiz->time_limit * 60) - $elapsed;

        return max(0, $remaining);
    }

    /**
     * Check if the attempt has expired.
     */
    public function hasExpired()
    {
        return $this->getRemainingTime() <= 0;
    }
}
