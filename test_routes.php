<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\Route;

echo "🛣️ اختبار المسارات (Routes)\n";
echo "============================\n\n";

try {
    // Load Laravel application
    $app = require_once __DIR__ . '/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();

    echo "✅ تم تحميل Laravel بنجاح\n";

    // Test key routes
    $testRoutes = [
        'dashboard' => 'لوحة التحكم الرئيسية',
        'admin.dashboard' => 'لوحة إدارة النظام',
        'instructor.dashboard' => 'لوحة المحاضر',
        'questions.index' => 'بنك الأسئلة',
        'profile' => 'الملف الشخصي',
        'login' => 'تسجيل الدخول',
        'logout' => 'تسجيل الخروج'
    ];

    echo "\n🔍 فحص المسارات المهمة:\n";
    foreach ($testRoutes as $routeName => $description) {
        try {
            $url = route($routeName);
            echo "✅ {$description} ({$routeName}): {$url}\n";
        } catch (Exception $e) {
            echo "❌ {$description} ({$routeName}): غير موجود\n";
        }
    }

    // Test middleware routes
    echo "\n🔒 فحص مسارات الأدوار:\n";
    $roleRoutes = [
        'admin.users.index' => 'إدارة المستخدمين',
        'admin.courses.index' => 'إدارة الكورسات (إدارة)',
        'instructor.courses.index' => 'إدارة الكورسات (محاضر)',
        'admin.analytics' => 'التحليلات (إدارة)',
        'instructor.analytics' => 'التحليلات (محاضر)'
    ];

    foreach ($roleRoutes as $routeName => $description) {
        try {
            $url = route($routeName);
            echo "✅ {$description} ({$routeName}): {$url}\n";
        } catch (Exception $e) {
            echo "❌ {$description} ({$routeName}): غير موجود\n";
        }
    }

    echo "\n🎉 اختبار المسارات مكتمل!\n";
    echo "💡 يمكنك الآن:\n";
    echo "   1. تشغيل الخادم: php artisan serve\n";
    echo "   2. زيارة http://localhost:8000/dashboard\n";
    echo "   3. تسجيل الدخول واختبار جميع الروابط\n";

} catch (Exception $e) {
    echo "❌ خطأ في اختبار المسارات: " . $e->getMessage() . "\n";
}

echo "\n";
