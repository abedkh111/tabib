<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id',
        'option_text',
        'option_image',
        'is_correct',
        'sort_order',
        'explanation',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get the question that this option belongs to.
     */
    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    /**
     * Get the quiz attempt answers that selected this option.
     */
    public function selectedAnswers()
    {
        return $this->hasMany(QuizAttemptAnswer::class, 'selected_option_id');
    }

    /**
     * Scope to get only correct options.
     */
    public function scopeCorrect($query)
    {
        return $query->where('is_correct', true);
    }

    /**
     * Scope to get only incorrect options.
     */
    public function scopeIncorrect($query)
    {
        return $query->where('is_correct', false);
    }

    /**
     * Get the option image URL.
     */
    public function getOptionImageUrlAttribute()
    {
        if ($this->option_image) {
            return asset('storage/question-options/' . $this->option_image);
        }
        
        return null;
    }

    /**
     * Check if this option has an image.
     */
    public function hasImage()
    {
        return !empty($this->option_image);
    }

    /**
     * Get the selection count for this option.
     */
    public function getSelectionCountAttribute()
    {
        return $this->selectedAnswers()->count();
    }

    /**
     * Get the selection percentage for this option.
     */
    public function getSelectionPercentageAttribute()
    {
        $totalSelections = $this->question->quizAttempts()->count();
        
        if ($totalSelections == 0) {
            return 0;
        }
        
        return round(($this->selection_count / $totalSelections) * 100, 2);
    }
}
