<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Medical Education Platform API Routes
Route::prefix('v1')->group(function () {
    
    // Authentication Routes
    Route::post('/login', function () {
        return response()->json(['message' => 'API Login endpoint']);
    });
    
    Route::post('/register', function () {
        return response()->json(['message' => 'API Register endpoint']);
    });
    
    // Protected Routes
    Route::middleware('auth:sanctum')->group(function () {
        
        // Specializations
        Route::get('/specializations', function () {
            return response()->json(['message' => 'Get all specializations']);
        });
        
        // Courses
        Route::get('/courses', function () {
            return response()->json(['message' => 'Get all courses']);
        });
        
        Route::get('/courses/{id}', function ($id) {
            return response()->json(['message' => "Get course {$id}"]);
        });
        
        // Questions
        Route::get('/questions', function () {
            return response()->json(['message' => 'Get questions']);
        });
        
        // Quizzes
        Route::get('/quizzes', function () {
            return response()->json(['message' => 'Get quizzes']);
        });
        
        Route::post('/quizzes/{id}/attempt', function ($id) {
            return response()->json(['message' => "Start quiz {$id} attempt"]);
        });
        
        // User Profile
        Route::get('/profile', function (Request $request) {
            return response()->json(['user' => $request->user()]);
        });
        
        Route::put('/profile', function () {
            return response()->json(['message' => 'Update profile']);
        });
    });
});
