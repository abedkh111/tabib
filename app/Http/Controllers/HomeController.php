<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Question;
use App\Models\Specialization;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     */
    public function index()
    {
        try {
            $user = auth()->user();
            
            // إحصائيات عامة
            $stats = [
                'total_courses' => Course::count(),
                'total_questions' => Question::count(),
                'total_specializations' => Specialization::count(),
                'total_students' => User::role('student')->count(),
            ];

            // الكورسات المسجل بها المستخدم
            $enrolledCourses = collect();
            if ($user && $user->hasRole('student')) {
                try {
                    $enrolledCourses = $user->enrolledCourses()
                        ->with('specialization')
                        ->latest()
                        ->take(5)
                        ->get();
                } catch (\Exception $e) {
                    Log::warning('Error fetching enrolled courses: ' . $e->getMessage());
                }
            }

            // الكورسات التي يدرسها المستخدم (إذا كان محاضر)
            $teachingCourses = collect();
            if ($user && $user->hasRole('instructor')) {
                try {
                    $teachingCourses = Course::where('instructor_id', $user->id)
                        ->with('specialization')
                        ->latest()
                        ->take(5)
                        ->get();
                } catch (\Exception $e) {
                    Log::warning('Error fetching teaching courses: ' . $e->getMessage());
                }
            }

            // أحدث الكورسات
            $latestCourses = Course::with('specialization')
                ->latest()
                ->take(6)
                ->get();

            return view('dashboard', compact(
                'stats',
                'enrolledCourses',
                'teachingCourses',
                'latestCourses'
            ));
            
        } catch (\Exception $e) {
            Log::error('Dashboard error: ' . $e->getMessage());
            
            return view('dashboard', [
                'stats' => [
                    'total_courses' => 0,
                    'total_questions' => 0,
                    'total_specializations' => 0,
                    'total_students' => 0,
                ],
                'enrolledCourses' => collect(),
                'teachingCourses' => collect(),
                'latestCourses' => collect(),
                'error' => 'حدث خطأ في تحميل البيانات. يرجى المحاولة مرة أخرى.'
            ]);
        }
    }

    /**
     * Show user profile
     */
    public function profile()
    {
        $user = auth()->user();
        return view('profile.show', compact('user'));
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'university' => 'nullable|string|max:255',
            'graduation_year' => 'nullable|integer|min:1950|max:' . (date('Y') + 10),
            'bio' => 'nullable|string|max:1000',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['name', 'phone', 'university', 'graduation_year', 'bio']);

        // رفع الصورة الشخصية
        if ($request->hasFile('avatar')) {
            try {
                // Delete old avatar if exists
                if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                    Storage::disk('public')->delete($user->avatar);
                }
                
                // Store new avatar
                $avatarPath = $request->file('avatar')->store('avatars', 'public');
                // Store just the path relative to storage/app/public
                $data['avatar'] = $avatarPath;
            } catch (\Exception $e) {
                return redirect()->route('profile')
                    ->with('error', 'حدث خطأ أثناء رفع الصورة: ' . $e->getMessage());
            }
        }

        $user->update($data);

        return redirect()->route('profile')
            ->with('success', 'تم تحديث الملف الشخصي بنجاح');
    }

    /**
     * Show notifications
     */
    public function notifications()
    {
        $notifications = auth()->user()->notifications()->paginate(20);
        return view('notifications.index', compact('notifications'));
    }

    /**
     * Mark notification as read
     */
    public function markNotificationAsRead($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        
        return response()->json(['success' => true]);
    }
}
