<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\DB;

echo "🔍 تشخيص مشاكل لوحة التحكم\n";
echo "================================\n\n";

try {
    // Load Laravel application
    $app = require_once __DIR__ . '/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();

    echo "✅ تم تحميل Laravel بنجاح\n";

    // Test database connection
    try {
        DB::connection()->getPdo();
        echo "✅ الاتصال بقاعدة البيانات يعمل\n";
        
        // Check if tables exist
        $tables = [
            'users' => 'جدول المستخدمين',
            'roles' => 'جدول الأدوار',
            'model_has_roles' => 'جدول ربط المستخدمين بالأدوار',
            'specializations' => 'جدول التخصصات',
            'courses' => 'جدول الكورسات',
            'questions' => 'جدول الأسئلة',
            'course_enrollments' => 'جدول تسجيل الكورسات'
        ];

        echo "\n📋 فحص الجداول:\n";
        foreach ($tables as $table => $description) {
            if (DB::getSchemaBuilder()->hasTable($table)) {
                $count = DB::table($table)->count();
                echo "✅ {$description} ({$table}): {$count} سجل\n";
            } else {
                echo "❌ {$description} ({$table}): غير موجود\n";
            }
        }

        // Check user roles
        echo "\n👥 فحص أدوار المستخدمين:\n";
        if (DB::getSchemaBuilder()->hasTable('users') && DB::getSchemaBuilder()->hasTable('roles')) {
            $users = DB::table('users')->get();
            foreach ($users as $user) {
                $roles = DB::table('model_has_roles')
                    ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
                    ->where('model_has_roles.model_id', $user->id)
                    ->where('model_has_roles.model_type', 'App\\Models\\User')
                    ->pluck('roles.name')
                    ->toArray();
                
                $rolesList = empty($roles) ? 'بدون دور' : implode(', ', $roles);
                echo "👤 {$user->name} ({$user->email}): {$rolesList}\n";
            }
        }

        // Test specific queries from HomeController
        echo "\n🧮 اختبار الاستعلامات:\n";
        
        try {
            $courseCount = DB::table('courses')->count();
            echo "✅ عدد الكورسات: {$courseCount}\n";
        } catch (Exception $e) {
            echo "❌ خطأ في عد الكورسات: " . $e->getMessage() . "\n";
        }

        try {
            $questionCount = DB::table('questions')->count();
            echo "✅ عدد الأسئلة: {$questionCount}\n";
        } catch (Exception $e) {
            echo "❌ خطأ في عد الأسئلة: " . $e->getMessage() . "\n";
        }

        try {
            $specializationCount = DB::table('specializations')->count();
            echo "✅ عدد التخصصات: {$specializationCount}\n";
        } catch (Exception $e) {
            echo "❌ خطأ في عد التخصصات: " . $e->getMessage() . "\n";
        }

    } catch (Exception $e) {
        echo "❌ خطأ في الاتصال بقاعدة البيانات: " . $e->getMessage() . "\n";
        echo "💡 تأكد من:\n";
        echo "   - إعدادات قاعدة البيانات في ملف .env\n";
        echo "   - تشغيل خادم قاعدة البيانات\n";
        echo "   - صحة بيانات الاتصال\n";
    }

} catch (Exception $e) {
    echo "❌ خطأ في تحميل Laravel: " . $e->getMessage() . "\n";
}

echo "\n🔧 الحلول المقترحة:\n";
echo "1. تشغيل الهجرات: php artisan migrate\n";
echo "2. تشغيل البذور: php artisan db:seed\n";
echo "3. مسح الكاش: php artisan cache:clear\n";
echo "4. مسح كاش التكوين: php artisan config:clear\n";
echo "5. إعادة تحميل الكلاسات: composer dump-autoload\n";
echo "\n";
