<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم - منصة طبيب التعليمية</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Alpine.js -->
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- Google Fonts for Arabic -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <style>
        body { 
            font-family: 'Cairo', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
        }
        .rtl { direction: rtl; }
        .space-x-reverse > :not([hidden]) ~ :not([hidden]) {
            --tw-space-x-reverse: 1;
            margin-right: calc(1rem * var(--tw-space-x-reverse));
            margin-left: calc(1rem * calc(1 - var(--tw-space-x-reverse)));
        }
    </style>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'cairo': ['Cairo', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 font-cairo">
    <!-- Navigation -->
    <nav class="bg-blue-600 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-4 space-x-reverse">
                    <i class="fas fa-stethoscope text-2xl"></i>
                    <h1 class="text-xl font-bold">منصة طبيب التعليمية</h1>
                </div>
                <div class="flex items-center space-x-4 space-x-reverse">
                    <span class="hidden md:inline">مرحباً، <?php echo e(auth()->user()->name); ?></span>
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center space-x-2 space-x-reverse bg-blue-700 hover:bg-blue-800 px-3 py-2 rounded">
                            <i class="fas fa-user-circle"></i>
                            <span class="hidden md:inline"><?php echo e(auth()->user()->name); ?></span>
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                        <div x-show="open" @click.away="open = false" x-transition class="absolute left-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-50">
                            <a href="<?php echo e(route('profile')); ?>" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">
                                <i class="fas fa-user ml-2"></i>
                                الملف الشخصي
                            </a>
                            <form method="POST" action="<?php echo e(route('logout')); ?>" class="block">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="w-full text-right px-4 py-2 text-red-600 hover:bg-gray-100">
                                    <i class="fas fa-sign-out-alt ml-2"></i>
                                    تسجيل الخروج
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto py-8 px-4">
        <!-- Error Message -->
        <?php if(isset($error)): ?>
        <div class="bg-red-50 border border-red-200 rounded-lg p-6 mb-8">
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle text-red-500 text-2xl ml-4"></i>
                <div>
                    <h3 class="text-lg font-bold text-red-800 mb-2">خطأ في النظام</h3>
                    <p class="text-red-700"><?php echo e($error); ?></p>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Welcome Section -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <div class="flex items-center mb-4">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center ml-4">
                    <?php if(auth()->user()->hasRole('super-admin')): ?>
                        <i class="fas fa-crown text-2xl text-yellow-600"></i>
                    <?php elseif(auth()->user()->hasRole('admin')): ?>
                        <i class="fas fa-user-shield text-2xl text-blue-600"></i>
                    <?php elseif(auth()->user()->hasRole('instructor')): ?>
                        <i class="fas fa-chalkboard-teacher text-2xl text-green-600"></i>
                    <?php elseif(auth()->user()->hasRole('student')): ?>
                        <i class="fas fa-user-graduate text-2xl text-purple-600"></i>
                    <?php else: ?>
                        <i class="fas fa-user text-2xl text-gray-600"></i>
                    <?php endif; ?>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">
                        مرحباً بك، <?php echo e(auth()->user()->name); ?>!
                    </h2>
                    <p class="text-gray-600">
                        <?php if(auth()->user()->hasRole('super-admin')): ?>
                            أنت مدير النظام الرئيسي. يمكنك إدارة جميع جوانب المنصة.
                        <?php elseif(auth()->user()->hasRole('admin')): ?>
                            أنت مدير المنصة. يمكنك إدارة المحتوى والمستخدمين.
                        <?php elseif(auth()->user()->hasRole('instructor')): ?>
                            أنت محاضر في المنصة. يمكنك إنشاء الكورسات والأسئلة.
                        <?php elseif(auth()->user()->hasRole('student')): ?>
                            أنت طالب في المنصة. يمكنك الوصول للكورسات والاختبارات.
                        <?php else: ?>
                            مرحباً بك في منصة طبيب التعليمية.
                        <?php endif; ?>
                    </p>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600 ml-4">
                        <i class="fas fa-book text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">الكورسات</h3>
                        <p class="text-3xl font-bold text-blue-600"><?php echo e($stats['total_courses'] ?? 0); ?></p>
                        <p class="text-xs text-gray-500">إجمالي الكورسات</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600 ml-4">
                        <i class="fas fa-question-circle text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">الأسئلة</h3>
                        <p class="text-3xl font-bold text-green-600"><?php echo e($stats['total_questions'] ?? 0); ?></p>
                        <p class="text-xs text-gray-500">بنك الأسئلة</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 text-purple-600 ml-4">
                        <i class="fas fa-stethoscope text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">التخصصات</h3>
                        <p class="text-3xl font-bold text-purple-600"><?php echo e($stats['total_specializations'] ?? 0); ?></p>
                        <p class="text-xs text-gray-500">التخصصات الطبية</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-orange-100 text-orange-600 ml-4">
                        <i class="fas fa-users text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">الطلاب</h3>
                        <p class="text-3xl font-bold text-orange-600"><?php echo e($stats['total_students'] ?? 0); ?></p>
                        <p class="text-xs text-gray-500">المسجلين</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Student Enrolled Courses -->
            <?php if(auth()->user()->hasRole('student') && isset($enrolledCourses) && $enrolledCourses->count() > 0): ?>
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-bookmark text-blue-600 ml-2"></i>
                    الكورسات المسجل بها
                </h3>
                <div class="space-y-4">
                    <?php $__currentLoopData = $enrolledCourses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="border-r-4 border-blue-500 pr-4 hover:bg-gray-50 p-2 rounded transition-colors">
                        <h4 class="font-semibold text-gray-800"><?php echo e($course->title); ?></h4>
                        <p class="text-sm text-gray-600"><?php echo e($course->specialization->name_ar ?? 'عام'); ?></p>
                        <p class="text-xs text-gray-500">المدة: <?php echo e($course->duration ?? 'غير محدد'); ?></p>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Instructor Teaching Courses -->
            <?php if(auth()->user()->hasRole('instructor') && isset($teachingCourses) && $teachingCourses->count() > 0): ?>
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-chalkboard text-green-600 ml-2"></i>
                    الكورسات التي تدرسها
                </h3>
                <div class="space-y-4">
                    <?php $__currentLoopData = $teachingCourses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="border-r-4 border-green-500 pr-4 hover:bg-gray-50 p-2 rounded transition-colors">
                        <h4 class="font-semibold text-gray-800"><?php echo e($course->title); ?></h4>
                        <p class="text-sm text-gray-600"><?php echo e($course->specialization->name_ar ?? 'عام'); ?></p>
                        <div class="flex items-center justify-between">
                            <p class="text-xs text-gray-500">الحالة: <?php echo e($course->is_active ? 'نشط' : 'غير نشط'); ?></p>
                            <?php if($course->is_active): ?>
                                <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">نشط</span>
                            <?php else: ?>
                                <span class="bg-gray-100 text-gray-800 text-xs px-2 py-1 rounded-full">غير نشط</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Latest Courses -->
            <?php if(isset($latestCourses) && $latestCourses->count() > 0): ?>
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-clock text-purple-600 ml-2"></i>
                    أحدث الكورسات
                </h3>
                <div class="space-y-4">
                    <?php $__currentLoopData = $latestCourses->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="border-r-4 border-purple-500 pr-4 hover:bg-gray-50 p-2 rounded transition-colors">
                        <h4 class="font-semibold text-gray-800"><?php echo e($course->title); ?></h4>
                        <p class="text-sm text-gray-600"><?php echo e($course->specialization->name_ar ?? 'عام'); ?></p>
                        <p class="text-xs text-gray-500"><?php echo e($course->created_at ? $course->created_at->diffForHumans() : 'غير محدد'); ?></p>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-bolt text-yellow-600 ml-2"></i>
                    إجراءات سريعة
                </h3>
                <div class="grid grid-cols-2 gap-4">
                    <?php if(auth()->user()->hasRole(['super-admin', 'admin'])): ?>
                    <a href="<?php echo e(route('admin.dashboard')); ?>" class="bg-blue-500 hover:bg-blue-600 text-white p-4 rounded-lg text-center transition-colors group">
                        <i class="fas fa-users-cog block text-2xl mb-2 group-hover:scale-110 transition-transform"></i>
                        <span class="text-sm">لوحة الإدارة</span>
                    </a>
                    <?php endif; ?>
                    
                    <?php if(auth()->user()->hasRole(['instructor', 'admin', 'super-admin'])): ?>
                    <a href="<?php echo e(route('instructor.dashboard')); ?>" class="bg-green-500 hover:bg-green-600 text-white p-4 rounded-lg text-center transition-colors group">
                        <i class="fas fa-chalkboard-teacher block text-2xl mb-2 group-hover:scale-110 transition-transform"></i>
                        <span class="text-sm">لوحة المحاضر</span>
                    </a>
                    <?php endif; ?>
                    
                    <a href="<?php echo e(route('questions.index')); ?>" class="bg-purple-500 hover:bg-purple-600 text-white p-4 rounded-lg text-center transition-colors group">
                        <i class="fas fa-book-open block text-2xl mb-2 group-hover:scale-110 transition-transform"></i>
                        <span class="text-sm">بنك الأسئلة</span>
                    </a>
                    
                    <a href="<?php echo e(route('profile')); ?>" class="bg-orange-500 hover:bg-orange-600 text-white p-4 rounded-lg text-center transition-colors group">
                        <i class="fas fa-user-edit block text-2xl mb-2 group-hover:scale-110 transition-transform"></i>
                        <span class="text-sm">الملف الشخصي</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Success Message for Database Setup -->
        <?php if(($stats['total_courses'] ?? 0) > 0 || ($stats['total_questions'] ?? 0) > 0): ?>
        <div class="bg-green-50 border border-green-200 rounded-lg p-6 text-center mt-8">
            <i class="fas fa-check-circle text-green-500 text-4xl mb-4"></i>
            <h3 class="text-xl font-bold text-green-800 mb-2">المنصة جاهزة للاستخدام!</h3>
            <p class="text-green-700 mb-4">
                تم إعداد قاعدة البيانات بنجاح. يمكنك الآن استخدام جميع ميزات المنصة.
            </p>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4">
                <div class="bg-white rounded-lg p-4 shadow-sm">
                    <i class="fas fa-book text-blue-500 text-2xl mb-2"></i>
                    <p class="text-sm font-semibold"><?php echo e($stats['total_courses']); ?> كورس</p>
                </div>
                <div class="bg-white rounded-lg p-4 shadow-sm">
                    <i class="fas fa-question-circle text-green-500 text-2xl mb-2"></i>
                    <p class="text-sm font-semibold"><?php echo e($stats['total_questions']); ?> سؤال</p>
                </div>
                <div class="bg-white rounded-lg p-4 shadow-sm">
                    <i class="fas fa-stethoscope text-purple-500 text-2xl mb-2"></i>
                    <p class="text-sm font-semibold"><?php echo e($stats['total_specializations']); ?> تخصص</p>
                </div>
                <div class="bg-white rounded-lg p-4 shadow-sm">
                    <i class="fas fa-users text-orange-500 text-2xl mb-2"></i>
                    <p class="text-sm font-semibold"><?php echo e($stats['total_students']); ?> طالب</p>
                </div>
            </div>
        </div>
        <?php else: ?>
        <!-- Database Setup Instructions -->
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 text-center mt-8">
            <i class="fas fa-database text-yellow-500 text-4xl mb-4"></i>
            <h3 class="text-xl font-bold text-yellow-800 mb-2">قاعدة البيانات فارغة</h3>
            <p class="text-yellow-700 mb-4">
                يبدو أن قاعدة البيانات لم يتم إعدادها بعد. يرجى تشغيل الأوامر التالية لإعداد البيانات:
            </p>
            <div class="bg-gray-100 rounded p-4 text-right space-y-2">
                <div class="text-sm">
                    <strong>1. تشغيل الهجرات:</strong>
                    <code class="block bg-gray-800 text-green-400 p-2 rounded mt-1">php artisan migrate</code>
                </div>
                <div class="text-sm">
                    <strong>2. إعداد الأدوار والصلاحيات:</strong>
                    <code class="block bg-gray-800 text-green-400 p-2 rounded mt-1">php artisan db:seed --class=RolePermissionSeeder</code>
                </div>
                <div class="text-sm">
                    <strong>3. إضافة المستخدمين:</strong>
                    <code class="block bg-gray-800 text-green-400 p-2 rounded mt-1">php artisan db:seed --class=UserSeeder</code>
                </div>
                <div class="text-sm">
                    <strong>4. إضافة التخصصات:</strong>
                    <code class="block bg-gray-800 text-green-400 p-2 rounded mt-1">php artisan db:seed --class=SpecializationSeeder</code>
                </div>
                <div class="text-sm">
                    <strong>5. إضافة الكورسات:</strong>
                    <code class="block bg-gray-800 text-green-400 p-2 rounded mt-1">php artisan db:seed --class=CourseSeeder</code>
                </div>
                <div class="text-sm">
                    <strong>6. إضافة الأسئلة:</strong>
                    <code class="block bg-gray-800 text-green-400 p-2 rounded mt-1">php artisan db:seed --class=QuestionSeeder</code>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-12">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p>&copy; 2024 منصة طبيب التعليمية. جميع الحقوق محفوظة.</p>
        </div>
    </footer>
</body>
</html>
<?php /**PATH /home/abedkh/Documents/tabib/resources/views/dashboard.blade.php ENDPATH**/ ?>