<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\CourseController as AdminCourseController;
use App\Http\Controllers\Admin\QuestionController as AdminQuestionController;
use App\Http\Controllers\Instructor\InstructorController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Public routes
Route::get('/', function () {
    return redirect('/login');
})->name('home');

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Authenticated user routes
Route::middleware(['auth'])->group(function () {
    // Dashboard - Main route
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
    
    // Profile management
    Route::get('/profile', [HomeController::class, 'profile'])->name('profile');
    Route::put('/profile', [HomeController::class, 'updateProfile'])->name('profile.update');
    
    // Course enrollment and learning
    Route::post('/courses/{course}/enroll', [CourseController::class, 'enroll'])->name('courses.enroll');
    Route::get('/courses/{course}/learn', [CourseController::class, 'learn'])->name('courses.learn')->middleware('subscription');
    Route::post('/courses/{course}/lessons/{lesson}/complete', [CourseController::class, 'completeLesson'])->name('courses.lessons.complete');
    
    // Quiz taking
    Route::get('/quizzes/{quiz}/start', [QuizController::class, 'start'])->name('quizzes.start');
    Route::post('/quizzes/{quiz}/submit', [QuizController::class, 'submit'])->name('quizzes.submit');
    Route::get('/quizzes/{quiz}/results/{attempt}', [QuizController::class, 'results'])->name('quizzes.results');
    Route::get('/quiz-attempts', [QuizController::class, 'attempts'])->name('quiz-attempts.index');
    
    // My courses and progress
    Route::get('/my-courses', [CourseController::class, 'myCourses'])->name('courses.my');
    Route::get('/my-progress', [HomeController::class, 'myProgress'])->name('progress.my');
    
    // Question bank
    Route::get('/questions', [QuestionController::class, 'index'])->name('questions.index');
    Route::get('/questions/practice', [QuestionController::class, 'practice'])->name('questions.practice');
    Route::post('/questions/{question}/answer', [QuestionController::class, 'answer'])->name('questions.answer');
    
    // Notifications
    Route::get('/notifications', [HomeController::class, 'notifications'])->name('notifications');
    Route::post('/notifications/{id}/read', [HomeController::class, 'markNotificationAsRead'])->name('notifications.read');
});

// Admin routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');
    
    // User management
    Route::resource('users', AdminUserController::class);
    Route::post('/users/{user}/activate', [AdminUserController::class, 'activate'])->name('users.activate');
    Route::post('/users/{user}/deactivate', [AdminUserController::class, 'deactivate'])->name('users.deactivate');
    Route::post('/users/{user}/assign-role', [AdminUserController::class, 'assignRole'])->name('users.assign-role');
    
    // Course management
    Route::resource('courses', AdminCourseController::class);
    Route::post('/courses/{course}/approve', [AdminCourseController::class, 'approve'])->name('courses.approve');
    Route::post('/courses/{course}/reject', [AdminCourseController::class, 'reject'])->name('courses.reject');
    
    // Question management
    Route::resource('questions', AdminQuestionController::class);
    Route::post('/questions/{question}/approve', [AdminQuestionController::class, 'approve'])->name('questions.approve');
    Route::post('/questions/{question}/reject', [AdminQuestionController::class, 'reject'])->name('questions.reject');
    Route::post('/questions/bulk-approve', [AdminQuestionController::class, 'bulkApprove'])->name('questions.bulk-approve');
    
    // Specialization management
    Route::resource('specializations', \App\Http\Controllers\Admin\SpecializationController::class);
    
    // Analytics and reports
    Route::get('/analytics', [AdminController::class, 'analytics'])->name('analytics');
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
    Route::get('/reports/users', [AdminController::class, 'userReports'])->name('reports.users');
    Route::get('/reports/courses', [AdminController::class, 'courseReports'])->name('reports.courses');
    Route::get('/reports/quizzes', [AdminController::class, 'quizReports'])->name('reports.quizzes');
    
    // System settings
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
    Route::post('/settings', [AdminController::class, 'updateSettings'])->name('settings.update');
    
    // Research and News management
    Route::resource('research-and-news', \App\Http\Controllers\Admin\ResearchAndNewsController::class);
    
    // Section Titles management
    Route::resource('section-titles', \App\Http\Controllers\Admin\SectionTitleController::class);
    
    // Payment management
    Route::get('/payments', [AdminController::class, 'payments'])->name('payments');
    Route::get('/subscriptions', [AdminController::class, 'subscriptions'])->name('subscriptions');
});

// Instructor routes  
Route::middleware(['auth', 'role:instructor|admin|super-admin'])->prefix('instructor')->name('instructor.')->group(function () {
    Route::get('/dashboard', function () {
        return view('instructor.dashboard');
    })->name('dashboard');
    
    // Course management
    Route::resource('courses', \App\Http\Controllers\Instructor\CourseController::class);
    Route::post('/courses/{course}/publish', [\App\Http\Controllers\Instructor\CourseController::class, 'publish'])->name('courses.publish');
    Route::post('/courses/{course}/unpublish', [\App\Http\Controllers\Instructor\CourseController::class, 'unpublish'])->name('courses.unpublish');
    
    // Lesson management
    Route::resource('courses.lessons', \App\Http\Controllers\Instructor\LessonController::class);
    
    // Question management
    Route::resource('questions', \App\Http\Controllers\Instructor\QuestionController::class);
    Route::post('/questions/{question}/approve', [\App\Http\Controllers\Instructor\QuestionController::class, 'approve'])->name('questions.approve');
    
    // Quiz management
    Route::resource('quizzes', \App\Http\Controllers\Instructor\QuizController::class);
    Route::get('/quizzes/{quiz}/statistics', [\App\Http\Controllers\Instructor\QuizController::class, 'statistics'])->name('quizzes.statistics');
    
    // Analytics
    Route::get('/analytics', [InstructorController::class, 'analytics'])->name('analytics');
    Route::get('/students', [InstructorController::class, 'students'])->name('students');
});

// API routes for AJAX requests
Route::middleware(['auth'])->prefix('api')->name('api.')->group(function () {
    Route::get('/notifications', [HomeController::class, 'notifications'])->name('notifications');
    Route::post('/notifications/{notification}/read', [HomeController::class, 'markNotificationRead'])->name('notifications.read');
    
    Route::get('/search/suggestions', [HomeController::class, 'searchSuggestions'])->name('search.suggestions');
    
    Route::get('/courses/{course}/progress', [CourseController::class, 'getProgress'])->name('courses.progress');
    Route::post('/courses/{course}/progress', [CourseController::class, 'updateProgress'])->name('courses.progress.update');
    
    Route::get('/quizzes/{quiz}/questions', [QuizController::class, 'getQuestions'])->name('quizzes.questions');
    Route::post('/quizzes/{quiz}/save-progress', [QuizController::class, 'saveProgress'])->name('quizzes.save-progress');
    
    Route::get('/specializations/{specialization}/questions', [QuestionController::class, 'getBySpecialization'])->name('questions.by-specialization');
});

// Subscription and payment routes
Route::middleware(['auth'])->group(function () {
    Route::get('/subscription', [HomeController::class, 'subscription'])->name('subscription');
    Route::post('/subscription/upgrade', [HomeController::class, 'upgradeSubscription'])->name('subscription.upgrade');
    Route::post('/subscription/cancel', [HomeController::class, 'cancelSubscription'])->name('subscription.cancel');
    
    Route::get('/payment/success', [HomeController::class, 'paymentSuccess'])->name('payment.success');
    Route::get('/payment/cancel', [HomeController::class, 'paymentCancel'])->name('payment.cancel');
    Route::post('/payment/webhook', [HomeController::class, 'paymentWebhook'])->name('payment.webhook');
});

// Certificate routes
Route::middleware(['auth'])->group(function () {
    Route::get('/certificates', [HomeController::class, 'certificates'])->name('certificates.index');
    Route::get('/certificates/{certificate}', [HomeController::class, 'showCertificate'])->name('certificates.show');
    Route::get('/certificates/{certificate}/download', [HomeController::class, 'downloadCertificate'])->name('certificates.download');
});

// Public certificate verification
Route::get('/verify-certificate/{certificate}', [HomeController::class, 'verifyCertificate'])->name('certificates.verify');

// Sitemap and SEO
Route::get('/sitemap.xml', [HomeController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [HomeController::class, 'robots'])->name('robots');

Auth::routes();

Route::get('/home', function () {
    return redirect('/dashboard');
})->name('home.redirect');
