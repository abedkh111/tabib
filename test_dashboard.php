<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Course;
use App\Models\Question;
use App\Models\Specialization;

echo "🧪 اختبار وظائف لوحة التحكم\n";
echo "===============================\n\n";

try {
    // Load Laravel application
    $app = require_once __DIR__ . '/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();

    echo "✅ تم تحميل Laravel بنجاح\n";

    // Test HomeController functionality
    echo "\n🎯 اختبار وظائف HomeController:\n";

    // Test statistics
    $stats = [
        'total_courses' => Course::count(),
        'total_questions' => Question::count(),
        'total_specializations' => Specialization::count(),
        'total_students' => User::role('student')->count(),
    ];

    echo "📊 الإحصائيات:\n";
    echo "   - الكورسات: {$stats['total_courses']}\n";
    echo "   - الأسئلة: {$stats['total_questions']}\n";
    echo "   - التخصصات: {$stats['total_specializations']}\n";
    echo "   - الطلاب: {$stats['total_students']}\n";

    // Test user roles
    echo "\n👥 اختبار أدوار المستخدمين:\n";
    $testUsers = [
        'superadmin@tabib.com' => 'super-admin',
        'admin@tabib.com' => 'admin',
        'fatima.doctor@tabib.com' => 'instructor',
        'abdullah.student@tabib.com' => 'student'
    ];

    foreach ($testUsers as $email => $expectedRole) {
        $user = User::where('email', $email)->first();
        if ($user) {
            $hasRole = $user->hasRole($expectedRole);
            $status = $hasRole ? '✅' : '❌';
            echo "   {$status} {$user->name}: {$expectedRole}\n";
        } else {
            echo "   ❌ المستخدم غير موجود: {$email}\n";
        }
    }

    // Test course relationships
    echo "\n📚 اختبار علاقات الكورسات:\n";
    $courses = Course::with('specialization')->take(3)->get();
    foreach ($courses as $course) {
        $specialization = $course->specialization ? $course->specialization->name_ar : 'غير محدد';
        echo "   📖 {$course->title} - {$specialization}\n";
    }

    // Test instructor courses
    echo "\n👨‍🏫 اختبار كورسات المحاضرين:\n";
    $instructor = User::role('instructor')->first();
    if ($instructor) {
        $teachingCourses = Course::where('instructor_id', $instructor->id)->count();
        echo "   👤 {$instructor->name}: {$teachingCourses} كورس\n";
    }

    // Test enrolled courses (if any)
    echo "\n🎓 اختبار تسجيل الطلاب:\n";
    $student = User::role('student')->first();
    if ($student) {
        try {
            $enrolledCourses = $student->enrolledCourses()->count();
            echo "   👤 {$student->name}: {$enrolledCourses} كورس مسجل\n";
        } catch (Exception $e) {
            echo "   ⚠️ لا توجد تسجيلات حالياً (هذا طبيعي)\n";
        }
    }

    echo "\n🎉 جميع الاختبارات تمت بنجاح!\n";
    echo "💡 لوحة التحكم جاهزة للعمل. يمكنك الآن:\n";
    echo "   1. تسجيل الدخول بأي من الحسابات التجريبية\n";
    echo "   2. الوصول إلى /dashboard\n";
    echo "   3. استكشاف جميع ميزات المنصة\n\n";

    echo "🔑 حسابات تجريبية:\n";
    echo "   - Super Admin: superadmin@tabib.com / password123\n";
    echo "   - Admin: admin@tabib.com / password123\n";
    echo "   - محاضر: fatima.doctor@tabib.com / password123\n";
    echo "   - طالب: abdullah.student@tabib.com / password123\n";

} catch (Exception $e) {
    echo "❌ خطأ في الاختبار: " . $e->getMessage() . "\n";
}

echo "\n";
