<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizAttemptAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_attempt_id',
        'question_id',
        'selected_option_id',
        'is_correct',
        'time_taken',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
        'time_taken' => 'integer',
    ];

    /**
     * Get the quiz attempt that this answer belongs to.
     */
    public function quizAttempt()
    {
        return $this->belongsTo(QuizAttempt::class);
    }

    /**
     * Get the question that was answered.
     */
    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    /**
     * Get the selected option.
     */
    public function selectedOption()
    {
        return $this->belongsTo(QuestionOption::class, 'selected_option_id');
    }

    /**
     * Scope to get only correct answers.
     */
    public function scopeCorrect($query)
    {
        return $query->where('is_correct', true);
    }

    /**
     * Scope to get only incorrect answers.
     */
    public function scopeIncorrect($query)
    {
        return $query->where('is_correct', false);
    }

    /**
     * Check if the answer was skipped.
     */
    public function isSkipped()
    {
        return is_null($this->selected_option_id);
    }

    /**
     * Get the time taken in human readable format.
     */
    public function getFormattedTimeAttribute()
    {
        if (!$this->time_taken) {
            return '0 ثانية';
        }

        $minutes = floor($this->time_taken / 60);
        $seconds = $this->time_taken % 60;

        if ($minutes > 0) {
            return $minutes . ' دقيقة ' . $seconds . ' ثانية';
        }

        return $seconds . ' ثانية';
    }
}
