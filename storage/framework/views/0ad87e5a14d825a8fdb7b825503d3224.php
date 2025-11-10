<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>التحليلات - منصة طبيب التعليمية</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>body { font-family: 'Cairo', sans-serif; }</style>
</head>
<body class="bg-gray-50">
    <nav class="bg-red-600 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-4 space-x-reverse">
                    <i class="fas fa-chart-bar text-2xl"></i>
                    <h1 class="text-xl font-bold">التحليلات</h1>
                </div>
                <div class="flex items-center space-x-4 space-x-reverse">
                    <a href="<?php echo e(route('admin.dashboard')); ?>" class="bg-red-700 hover:bg-red-800 px-3 py-2 rounded">العودة</a>
                    <span><?php echo e(auth()->user()->name); ?></span>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto py-8 px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">إجمالي المستخدمين</p>
                        <p class="text-3xl font-bold text-gray-800"><?php echo e($stats['total_users']); ?></p>
                    </div>
                    <i class="fas fa-users text-4xl text-blue-500"></i>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">إجمالي الكورسات</p>
                        <p class="text-3xl font-bold text-gray-800"><?php echo e($stats['total_courses']); ?></p>
                    </div>
                    <i class="fas fa-book text-4xl text-green-500"></i>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">إجمالي الأسئلة</p>
                        <p class="text-3xl font-bold text-gray-800"><?php echo e($stats['total_questions']); ?></p>
                    </div>
                    <i class="fas fa-question-circle text-4xl text-purple-500"></i>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">إجمالي الاختبارات</p>
                        <p class="text-3xl font-bold text-gray-800"><?php echo e($stats['total_quizzes']); ?></p>
                    </div>
                    <i class="fas fa-clipboard-list text-4xl text-orange-500"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold mb-4">التسجيلات الشهرية</h2>
            <div class="space-y-2">
                <?php $__currentLoopData = range(1, 12); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $month): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php $count = $monthlyUsers[$month] ?? 0; ?>
                    <div class="flex items-center">
                        <span class="w-20 text-sm"><?php echo e(date('F', mktime(0, 0, 0, $month, 1))); ?></span>
                        <div class="flex-1 bg-gray-200 rounded-full h-6 mr-4">
                            <div class="bg-blue-600 h-6 rounded-full" style="width: <?php echo e(min(100, ($count / max(1, max($monthlyUsers))) * 100)); ?>%"></div>
                        </div>
                        <span class="w-12 text-right"><?php echo e($count); ?></span>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
</body>
</html>

<?php /**PATH /home/abedkh/Documents/tabib/resources/views/admin/analytics.blade.php ENDPATH**/ ?>