<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Course;
use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin|super-admin']);
    }

    public function analytics()
    {
        $stats = [
            'total_users' => User::count(),
            'total_students' => User::role('student')->count(),
            'total_instructors' => User::role('instructor')->count(),
            'total_admins' => User::role(['admin', 'super-admin'])->count(),
            'total_courses' => Course::count(),
            'published_courses' => Course::where('is_published', true)->count(),
            'total_questions' => Question::count(),
            'approved_questions' => Question::where('is_approved', true)->count(),
            'total_quizzes' => Quiz::count(),
        ];

        // Monthly user registrations
        $monthlyUsers = User::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        // Course enrollments by specialization
        $enrollmentsBySpecialization = DB::table('course_enrollments')
            ->join('courses', 'course_enrollments.course_id', '=', 'courses.id')
            ->join('specializations', 'courses.specialization_id', '=', 'specializations.id')
            ->select('specializations.name_ar', DB::raw('COUNT(*) as count'))
            ->groupBy('specializations.name_ar')
            ->orderBy('count', 'desc')
            ->take(10)
            ->get();

        return view('admin.analytics', compact('stats', 'monthlyUsers', 'enrollmentsBySpecialization'));
    }

    public function reports()
    {
        return view('admin.reports');
    }

    public function userReports()
    {
        $userStats = [
            'total_users' => User::count(),
            'active_users' => User::where('is_active', true)->count(),
            'inactive_users' => User::where('is_active', false)->count(),
            'students_by_subscription' => User::role('student')
                ->select('subscription_type', DB::raw('COUNT(*) as count'))
                ->groupBy('subscription_type')
                ->pluck('count', 'subscription_type')
                ->toArray(),
        ];

        return view('admin.reports.users', compact('userStats'));
    }

    public function courseReports()
    {
        $courseStats = [
            'total_courses' => Course::count(),
            'published_courses' => Course::where('is_published', true)->count(),
            'draft_courses' => Course::where('is_published', false)->count(),
            'courses_by_specialization' => Course::join('specializations', 'courses.specialization_id', '=', 'specializations.id')
                ->select('specializations.name_ar', DB::raw('COUNT(*) as count'))
                ->groupBy('specializations.name_ar')
                ->orderBy('count', 'desc')
                ->take(10)
                ->get(),
        ];

        return view('admin.reports.courses', compact('courseStats'));
    }

    public function quizReports()
    {
        $quizStats = [
            'total_quizzes' => Quiz::count(),
            'total_attempts' => DB::table('quiz_attempts')->count(),
            'average_score' => DB::table('quiz_attempts')->avg('score'),
            'passed_attempts' => DB::table('quiz_attempts')->where('passed', true)->count(),
        ];

        return view('admin.reports.quizzes', compact('quizStats'));
    }

    public function settings()
    {
        return view('admin.settings');
    }

    public function updateSettings(Request $request)
    {
        // Implementation for updating system settings
        return redirect()->back()->with('success', 'تم حفظ الإعدادات بنجاح');
    }

    public function payments()
    {
        // Implementation for payment management
        return view('admin.payments');
    }

    public function subscriptions()
    {
        // Implementation for subscription management
        return view('admin.subscriptions');
    }
}