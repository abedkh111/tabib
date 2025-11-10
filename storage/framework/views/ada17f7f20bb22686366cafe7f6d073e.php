<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة الأسئلة - منصة طبيب التعليمية</title>
    
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
    </style>
</head>
<body class="bg-gray-50 font-cairo">
    <!-- Navigation -->
    <nav class="bg-red-600 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-4 space-x-reverse">
                    <i class="fas fa-question-circle text-2xl"></i>
                    <h1 class="text-xl font-bold">إدارة الأسئلة</h1>
                </div>
                <div class="flex items-center space-x-4 space-x-reverse">
                    <a href="<?php echo e(route('admin.questions.create')); ?>" class="bg-blue-600 hover:bg-blue-700 px-3 py-2 rounded">
                        <i class="fas fa-plus ml-2"></i>
                        إضافة سؤال جديد
                    </a>
                    <a href="<?php echo e(route('admin.dashboard')); ?>" class="bg-red-700 hover:bg-red-800 px-3 py-2 rounded">
                        <i class="fas fa-arrow-right ml-2"></i>
                        العودة للوحة الإدارة
                    </a>
                    <a href="<?php echo e(route('dashboard')); ?>" class="bg-red-700 hover:bg-red-800 px-3 py-2 rounded">
                        <i class="fas fa-home ml-2"></i>
                        الرئيسية
                    </a>
                    <span class="hidden md:inline"><?php echo e(auth()->user()->name); ?></span>
                    <form method="POST" action="<?php echo e(route('logout')); ?>" class="inline">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="bg-red-700 hover:bg-red-800 px-3 py-2 rounded">
                            <i class="fas fa-sign-out-alt ml-2"></i>
                            خروج
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto py-8 px-4">
        <!-- Success Message -->
        <?php if(session('success')): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6" role="alert">
            <div class="flex items-center">
                <i class="fas fa-check-circle ml-2"></i>
                <span><?php echo e(session('success')); ?></span>
            </div>
        </div>
        <?php endif; ?>

        <!-- Error Message -->
        <?php if(session('error')): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6" role="alert">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle ml-2"></i>
                <span><?php echo e(session('error')); ?></span>
            </div>
        </div>
        <?php endif; ?>

        <!-- Header -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center ml-4">
                        <i class="fas fa-question-circle text-2xl text-purple-600"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">إدارة الأسئلة</h2>
                        <p class="text-gray-600">مراجعة وإدارة جميع الأسئلة في المنصة</p>
                    </div>
                </div>
                <div class="text-sm text-gray-600">
                    <span class="font-semibold">إجمالي الأسئلة:</span> <?php echo e($questions->total()); ?>

                </div>
            </div>
        </div>

        <!-- Bulk Actions and Questions Table -->
        <form method="POST" action="<?php echo e(route('admin.questions.bulk-approve')); ?>" id="bulkForm">
            <?php echo csrf_field(); ?>
            <!-- Bulk Actions Bar -->
            <div class="bg-white rounded-lg shadow-md p-4 mb-4 flex items-center justify-between">
                <div class="flex items-center space-x-4 space-x-reverse">
                    <button type="button" onclick="selectAll()" class="text-sm text-blue-600 hover:text-blue-800">
                        <i class="fas fa-check-square ml-1"></i>
                        تحديد الكل
                    </button>
                    <button type="button" onclick="deselectAll()" class="text-sm text-gray-600 hover:text-gray-800">
                        <i class="fas fa-square ml-1"></i>
                        إلغاء التحديد
                    </button>
                </div>
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    <i class="fas fa-check ml-2"></i>
                    الموافقة على المحدد
                </button>
            </div>

            <!-- Questions Table -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <input type="checkbox" id="selectAllCheckbox" onchange="toggleAll(this)">
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                السؤال
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                التخصص
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                الصعوبة
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                المنشئ
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                الحالة
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                الإجراءات
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php $__empty_1 = true; $__currentLoopData = $questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="checkbox" name="question_ids[]" value="<?php echo e($question->id); ?>" class="question-checkbox">
                            </td>
                            <td class="px-6 py-4">
                                <div class="max-w-md">
                                    <div class="text-sm font-medium text-gray-900">
                                        <?php echo e(Str::limit($question->question_text, 100)); ?>

                                    </div>
                                    <?php if($question->question_image): ?>
                                    <div class="mt-1">
                                        <i class="fas fa-image text-gray-400"></i>
                                        <span class="text-xs text-gray-500">يحتوي على صورة</span>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-900">
                                    <?php echo e($question->specialization->name_ar ?? 'غير محدد'); ?>

                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php
                                    $difficultyColors = [
                                        'easy' => 'bg-green-100 text-green-800',
                                        'medium' => 'bg-yellow-100 text-yellow-800',
                                        'hard' => 'bg-orange-100 text-orange-800',
                                        'expert' => 'bg-red-100 text-red-800',
                                    ];
                                    $color = $difficultyColors[$question->difficulty_level] ?? 'bg-gray-100 text-gray-800';
                                ?>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo e($color); ?>">
                                    <?php echo e($question->difficulty_label); ?>

                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900"><?php echo e($question->user->name ?? 'غير محدد'); ?></div>
                                <div class="text-sm text-gray-500"><?php echo e($question->user->email ?? ''); ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php if($question->is_approved): ?>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle ml-1"></i>
                                        معتمد
                                    </span>
                                <?php else: ?>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-clock ml-1"></i>
                                        قيد المراجعة
                                    </span>
                                <?php endif; ?>
                                <?php if(!$question->is_active): ?>
                                    <span class="px-2 mr-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        <i class="fas fa-ban ml-1"></i>
                                        غير نشط
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2 space-x-reverse">
                                    <a href="<?php echo e(route('admin.questions.show', $question)); ?>" 
                                       class="text-blue-600 hover:text-blue-900" 
                                       title="عرض التفاصيل">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?php echo e(route('admin.questions.edit', $question)); ?>" 
                                       class="text-blue-600 hover:text-blue-900" 
                                       title="تعديل">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <?php if(!$question->is_approved): ?>
                                        <form method="POST" action="<?php echo e(route('admin.questions.approve', $question)); ?>" class="inline approve-form">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" 
                                                    class="text-green-600 hover:text-green-900" 
                                                    title="الموافقة"
                                                    onclick="return confirm('هل أنت متأكد من الموافقة على هذا السؤال؟')">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <form method="POST" action="<?php echo e(route('admin.questions.reject', $question)); ?>" class="inline reject-form">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" 
                                                    class="text-yellow-600 hover:text-yellow-900" 
                                                    title="رفض"
                                                    onclick="return confirm('هل أنت متأكد من رفض هذا السؤال؟')">
                                                <i class="fas fa-ban"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                    <form method="POST" action="<?php echo e(route('admin.questions.destroy', $question)); ?>" class="inline">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" 
                                                class="text-red-600 hover:text-red-900" 
                                                title="حذف"
                                                onclick="return confirm('هل أنت متأكد من حذف هذا السؤال؟ هذا الإجراء لا يمكن التراجع عنه.')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-inbox text-4xl mb-4"></i>
                                <p>لا توجد أسئلة حالياً</p>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if($questions->hasPages()): ?>
            <div class="bg-gray-50 px-4 py-3 border-t border-gray-200 sm:px-6">
                <div class="flex items-center justify-between">
                    <div class="flex-1 flex justify-between sm:hidden">
                        <?php if($questions->onFirstPage()): ?>
                            <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-300 bg-white cursor-not-allowed">
                                السابق
                            </span>
                        <?php else: ?>
                            <a href="<?php echo e($questions->previousPageUrl()); ?>" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                السابق
                            </a>
                        <?php endif; ?>

                        <?php if($questions->hasMorePages()): ?>
                            <a href="<?php echo e($questions->nextPageUrl()); ?>" class="mr-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                التالي
                            </a>
                        <?php else: ?>
                            <span class="mr-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-300 bg-white cursor-not-allowed">
                                التالي
                            </span>
                        <?php endif; ?>
                    </div>

                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-gray-700">
                                عرض
                                <span class="font-medium"><?php echo e($questions->firstItem()); ?></span>
                                إلى
                                <span class="font-medium"><?php echo e($questions->lastItem()); ?></span>
                                من
                                <span class="font-medium"><?php echo e($questions->total()); ?></span>
                                سؤال
                            </p>
                        </div>
                        <div>
                            <?php echo e($questions->links()); ?>

                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
        </form>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-12">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p>&copy; 2024 منصة طبيب التعليمية - لوحة الإدارة. جميع الحقوق محفوظة.</p>
        </div>
    </footer>

    <script>
        function toggleAll(checkbox) {
            const checkboxes = document.querySelectorAll('.question-checkbox');
            checkboxes.forEach(cb => cb.checked = checkbox.checked);
        }

        function selectAll() {
            const checkboxes = document.querySelectorAll('.question-checkbox');
            checkboxes.forEach(cb => cb.checked = true);
            document.getElementById('selectAllCheckbox').checked = true;
        }

        function deselectAll() {
            const checkboxes = document.querySelectorAll('.question-checkbox');
            checkboxes.forEach(cb => cb.checked = false);
            document.getElementById('selectAllCheckbox').checked = false;
        }

        // Update select all checkbox when individual checkboxes change
        document.querySelectorAll('.question-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const allChecked = Array.from(document.querySelectorAll('.question-checkbox')).every(cb => cb.checked);
                const noneChecked = Array.from(document.querySelectorAll('.question-checkbox')).every(cb => !cb.checked);
                const selectAllCheckbox = document.getElementById('selectAllCheckbox');
                if (allChecked) {
                    selectAllCheckbox.checked = true;
                    selectAllCheckbox.indeterminate = false;
                } else if (noneChecked) {
                    selectAllCheckbox.checked = false;
                    selectAllCheckbox.indeterminate = false;
                } else {
                    selectAllCheckbox.checked = false;
                    selectAllCheckbox.indeterminate = true;
                }
            });
        });
    </script>
</body>
</html>

<?php /**PATH /home/abedkh/Documents/tabib/resources/views/admin/questions/index.blade.php ENDPATH**/ ?>