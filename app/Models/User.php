<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'avatar',
        'specialization_id',
        'university',
        'graduation_year',
        'bio',
        'is_active',
        'email_verified_at',
        'subscription_type',
        'subscription_expires_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'subscription_expires_at' => 'datetime',
        'graduation_year' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Get the specialization that the user belongs to.
     */
    public function specialization()
    {
        return $this->belongsTo(Specialization::class);
    }

    /**
     * Get the courses that the user is enrolled in.
     */
    public function enrolledCourses()
    {
        return $this->belongsToMany(Course::class, 'course_enrollments')
                    ->withPivot(['enrolled_at', 'completed_at', 'progress'])
                    ->withTimestamps();
    }

    /**
     * Get the courses created by the user (for instructors).
     */
    public function createdCourses()
    {
        return $this->hasMany(Course::class, 'instructor_id');
    }

    /**
     * Get the quiz attempts by the user.
     */
    public function quizAttempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }

    /**
     * Get the questions created by the user.
     */
    public function createdQuestions()
    {
        return $this->hasMany(Question::class, 'created_by');
    }

    /**
     * Get the user's subscription status.
     */
    public function hasActiveSubscription()
    {
        return $this->subscription_expires_at && 
               $this->subscription_expires_at->isFuture();
    }

    /**
     * Check if user is a student.
     */
    public function isStudent()
    {
        return $this->hasRole('student');
    }

    /**
     * Check if user is an instructor.
     */
    public function isInstructor()
    {
        return $this->hasRole('instructor');
    }

    /**
     * Check if user is an admin.
     */
    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    /**
     * Get user's full avatar URL.
     */
    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            // If avatar already contains 'avatars/', use it directly
            if (strpos($this->avatar, 'avatars/') === 0) {
                return asset('storage/' . $this->avatar);
            }
            // Otherwise, assume it's just the filename
            return asset('storage/avatars/' . $this->avatar);
        }
        
        return asset('images/default-avatar.png');
    }
}
