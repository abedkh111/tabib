<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة المستخدمين - منصة طبيب التعليمية</title>
    
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
                    <i class="fas fa-users text-2xl"></i>
                    <h1 class="text-xl font-bold">إدارة المستخدمين</h1>
                </div>
                <div class="flex items-center space-x-4 space-x-reverse">
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
        <!-- Messages -->
        <?php if(session('success')): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6" role="alert">
            <div class="flex items-center">
                <i class="fas fa-check-circle ml-2"></i>
                <span><?php echo e(session('success')); ?></span>
            </div>
        </div>
        <?php endif; ?>

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
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center ml-4">
                        <i class="fas fa-users text-2xl text-blue-600"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">إدارة المستخدمين</h2>
                        <p class="text-gray-600">إدارة جميع المستخدمين في المنصة</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4 space-x-reverse">
                    <div class="text-sm text-gray-600">
                        <span class="font-semibold">إجمالي المستخدمين:</span> <?php echo e($users->total()); ?>

                    </div>
                    <a href="<?php echo e(route('admin.users.create')); ?>" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        <i class="fas fa-plus ml-2"></i>
                        إضافة مستخدم جديد
                    </a>
                </div>
            </div>
        </div>

        <!-- Users Table -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                المستخدم
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                البريد الإلكتروني
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                الدور
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                الحالة
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                تاريخ التسجيل
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                الإجراءات
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <img class="h-10 w-10 rounded-full object-cover" 
                                             src="<?php echo e($user->avatar_url); ?>" 
                                             alt="<?php echo e($user->name); ?>">
                                    </div>
                                    <div class="mr-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            <?php echo e($user->name); ?>

                                        </div>
                                        <?php if($user->phone): ?>
                                        <div class="text-sm text-gray-500">
                                            <i class="fas fa-phone ml-1"></i>
                                            <?php echo e($user->phone); ?>

                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900"><?php echo e($user->email); ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-wrap gap-2">
                                    <?php $__currentLoopData = $user->roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $roleColors = [
                                                'super-admin' => 'bg-red-100 text-red-800',
                                                'admin' => 'bg-purple-100 text-purple-800',
                                                'instructor' => 'bg-blue-100 text-blue-800',
                                                'student' => 'bg-green-100 text-green-800',
                                                'moderator' => 'bg-yellow-100 text-yellow-800',
                                            ];
                                            $color = $roleColors[$role->name] ?? 'bg-gray-100 text-gray-800';
                                        ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo e($color); ?>">
                                            <?php echo e($role->name); ?>

                                        </span>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($user->roles->isEmpty()): ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                            بدون دور
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php if($user->is_active): ?>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle ml-1"></i>
                                        نشط
                                    </span>
                                <?php else: ?>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        <i class="fas fa-ban ml-1"></i>
                                        غير نشط
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?php echo e($user->created_at->format('Y/m/d')); ?>

                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2 space-x-reverse">
                                    <a href="<?php echo e(route('admin.users.show', $user)); ?>" 
                                       class="text-blue-600 hover:text-blue-900" 
                                       title="عرض التفاصيل">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?php echo e(route('admin.users.edit', $user)); ?>" 
                                       class="text-green-600 hover:text-green-900" 
                                       title="تعديل">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <?php if($user->is_active): ?>
                                        <form method="POST" action="<?php echo e(route('admin.users.deactivate', $user)); ?>" class="inline">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" 
                                                    class="text-yellow-600 hover:text-yellow-900" 
                                                    title="إلغاء التفعيل"
                                                    onclick="return confirm('هل أنت متأكد من إلغاء تفعيل هذا المستخدم؟')">
                                                <i class="fas fa-ban"></i>
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <form method="POST" action="<?php echo e(route('admin.users.activate', $user)); ?>" class="inline">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" 
                                                    class="text-green-600 hover:text-green-900" 
                                                    title="تفعيل">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                    <?php if(!$user->hasRole('super-admin')): ?>
                                        <form method="POST" action="<?php echo e(route('admin.users.destroy', $user)); ?>" class="inline">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" 
                                                    class="text-red-600 hover:text-red-900" 
                                                    title="حذف"
                                                    onclick="return confirm('هل أنت متأكد من حذف هذا المستخدم؟ هذا الإجراء لا يمكن التراجع عنه.')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-inbox text-4xl mb-4"></i>
                                <p>لا يوجد مستخدمون حالياً</p>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if($users->hasPages()): ?>
            <div class="bg-gray-50 px-4 py-3 border-t border-gray-200 sm:px-6">
                <div class="flex items-center justify-between">
                    <div class="flex-1 flex justify-between sm:hidden">
                        <?php if($users->onFirstPage()): ?>
                            <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-300 bg-white cursor-not-allowed">
                                السابق
                            </span>
                        <?php else: ?>
                            <a href="<?php echo e($users->previousPageUrl()); ?>" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                السابق
                            </a>
                        <?php endif; ?>

                        <?php if($users->hasMorePages()): ?>
                            <a href="<?php echo e($users->nextPageUrl()); ?>" class="mr-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
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
                                <span class="font-medium"><?php echo e($users->firstItem()); ?></span>
                                إلى
                                <span class="font-medium"><?php echo e($users->lastItem()); ?></span>
                                من
                                <span class="font-medium"><?php echo e($users->total()); ?></span>
                                مستخدم
                            </p>
                        </div>
                        <div>
                            <?php echo e($users->links()); ?>

                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-12">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p>&copy; 2024 منصة طبيب التعليمية - لوحة الإدارة. جميع الحقوق محفوظة.</p>
        </div>
    </footer>
</body>
</html>

<?php /**PATH /home/abedkh/Documents/tabib/resources/views/admin/users/index.blade.php ENDPATH**/ ?>