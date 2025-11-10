<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\CourseEnrollment;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class CourseController extends Controller
{
    /**
     * Enroll user in a course.
     */
    public function enroll(Request $request, Course $course): RedirectResponse
    {
        // Check if user is already enrolled
        $existingEnrollment = CourseEnrollment::where('user_id', auth()->id())
                                            ->where('course_id', $course->id)
                                            ->first();

        if ($existingEnrollment) {
            return redirect()->route('courses.learn', $course)
                           ->with('info', 'You are already enrolled in this course.');
        }

        // Create enrollment
        CourseEnrollment::create([
            'user_id' => auth()->id(),
            'course_id' => $course->id,
            'enrolled_at' => now(),
        ]);

        return redirect()->route('courses.learn', $course)
                       ->with('success', 'Successfully enrolled in the course!');
    }

    /**
     * Show the course learning page.
     */
    public function learn(Course $course): View
    {
        // Check if user is enrolled
        $enrollment = CourseEnrollment::where('user_id', auth()->id())
                                    ->where('course_id', $course->id)
                                    ->first();

        if (!$enrollment) {
            return redirect()->route('courses.my')
                           ->with('error', 'You must enroll in this course first.');
        }

        $course->load(['lessons' => function ($query) {
            $query->orderBy('order');
        }]);

        return view('courses.learn', compact('course', 'enrollment'));
    }

    /**
     * Mark a lesson as completed.
     */
    public function completeLesson(Request $request, Course $course, Lesson $lesson): JsonResponse
    {
        // Verify lesson belongs to course
        if ($lesson->course_id !== $course->id) {
            return response()->json([
                'success' => false,
                'message' => 'Lesson does not belong to this course.',
            ], 400);
        }

        // Update enrollment progress
        $enrollment = CourseEnrollment::where('user_id', auth()->id())
                                    ->where('course_id', $course->id)
                                    ->first();

        if (!$enrollment) {
            return response()->json([
                'success' => false,
                'message' => 'You are not enrolled in this course.',
            ], 403);
        }

        $enrollment->update([
            'completed_at' => now(),
            'progress' => 100, // For simplicity, mark as complete
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Lesson completed successfully!',
        ]);
    }

    /**
     * Show user's enrolled courses.
     */
    public function myCourses(): View
    {
        $enrollments = CourseEnrollment::where('user_id', auth()->id())
                                     ->with(['course' => function ($query) {
                                         $query->with('instructor');
                                     }])
                                     ->get();

        return view('courses.my', compact('enrollments'));
    }

    /**
     * Get course progress (API).
     */
    public function getProgress(Course $course): JsonResponse
    {
        $enrollment = CourseEnrollment::where('user_id', auth()->id())
                                    ->where('course_id', $course->id)
                                    ->first();

        if (!$enrollment) {
            return response()->json([
                'enrolled' => false,
                'progress' => 0,
            ]);
        }

        return response()->json([
            'enrolled' => true,
            'progress' => $enrollment->progress ?? 0,
            'current_lesson' => $enrollment->current_lesson ?? 0,
            'completed' => $enrollment->completed_at !== null,
        ]);
    }

    /**
     * Update course progress (API).
     */
    public function updateProgress(Request $request, Course $course): JsonResponse
    {
        $request->validate([
            'progress' => 'required|integer|min:0|max:100',
            'current_lesson' => 'nullable|integer|min:0',
        ]);

        $enrollment = CourseEnrollment::where('user_id', auth()->id())
                                    ->where('course_id', $course->id)
                                    ->first();

        if (!$enrollment) {
            return response()->json([
                'success' => false,
                'message' => 'You are not enrolled in this course.',
            ], 403);
        }

        $enrollment->update([
            'progress' => $request->progress,
            'current_lesson' => $request->current_lesson,
        ]);

        return response()->json([
            'success' => true,
            'progress' => $enrollment->progress,
        ]);
    }
}