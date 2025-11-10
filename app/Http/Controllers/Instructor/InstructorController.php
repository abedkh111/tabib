<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Course;
use App\Models\Question;
use Illuminate\Support\Facades\DB;

class InstructorController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:instructor|admin|super-admin']);
    }

    public function analytics()
    {
        $user = auth()->user();

        $stats = [
            'total_courses' => Course::where('instructor_id', $user->id)->count(),
            'published_courses' => Course::where('instructor_id', $user->id)->where('is_published', true)->count(),
            'total_students' => DB::table('course_enrollments')
                ->join('courses', 'course_enrollments.course_id', '=', 'courses.id')
                ->where('courses.instructor_id', $user->id)
                ->distinct('course_enrollments.user_id')
                ->count(),
            'total_questions' => Question::where('created_by', $user->id)->count(),
            'approved_questions' => Question::where('created_by', $user->id)->where('is_approved', true)->count(),
        ];

        return view('instructor.analytics', compact('stats'));
    }

    public function students()
    {
        $user = auth()->user();

        $students = User::whereHas('enrolledCourses', function($query) use ($user) {
            $query->where('courses.instructor_id', $user->id);
        })->with(['enrolledCourses' => function($query) use ($user) {
            $query->where('courses.instructor_id', $user->id);
        }])->paginate(20);

        return view('instructor.students', compact('students'));
    }
}