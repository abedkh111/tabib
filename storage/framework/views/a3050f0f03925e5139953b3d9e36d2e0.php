<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>عناوين الأقسام - منصة طبيب التعليمية</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>body { font-family: 'Cairo', sans-serif; }</style>
</head>
<body class="bg-gray-50">
    <nav class="bg-blue-600 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-4 space-x-reverse">
                    <i class="fas fa-heading text-2xl"></i>
                    <h1 class="text-xl font-bold">عناوين الأقسام</h1>
                </div>
                <div class="flex items-center space-x-4 space-x-reverse">
                    <a href="<?php echo e(route('admin.dashboard')); ?>" class="bg-blue-700 hover:bg-blue-800 px-3 py-2 rounded">العودة</a>
                    <a href="<?php echo e(route('admin.section-titles.create')); ?>" class="bg-green-600 hover:bg-green-700 px-4 py-2 rounded">
                        <i class="fas fa-plus ml-2"></i>إضافة عنوان
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto py-8 px-4">
        <?php if(session('success')): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6"><?php echo e(session('success')); ?></div>
        <?php endif; ?>

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">المفتاح</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">العنوان (عربي)</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">العنوان (إنجليزي)</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">ترتيب</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الحالة</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $__empty_1 = true; $__currentLoopData = $titles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $title): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-600"><?php echo e($title->key); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap"><?php echo e($title->title_ar); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-500"><?php echo e($title->title_en ?? '-'); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($title->sort_order); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php if($title->is_active): ?>
                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">نشط</span>
                            <?php else: ?>
                                <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">غير نشط</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <a href="<?php echo e(route('admin.section-titles.show', $title)); ?>" class="text-blue-600 hover:text-blue-900 ml-4"><i class="fas fa-eye"></i></a>
                            <a href="<?php echo e(route('admin.section-titles.edit', $title)); ?>" class="text-green-600 hover:text-green-900 ml-4"><i class="fas fa-edit"></i></a>
                            <form method="POST" action="<?php echo e(route('admin.section-titles.destroy', $title)); ?>" class="inline">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button type="submit" onclick="return confirm('هل أنت متأكد؟')" class="text-red-600 hover:text-red-900"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="6" class="px-6 py-8 text-center">لا توجد عناوين</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <?php if($titles->hasPages()): ?>
                <div class="bg-gray-50 px-4 py-3"><?php echo e($titles->links()); ?></div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

<?php /**PATH /home/abedkh/Documents/tabib/resources/views/admin/section-titles/index.blade.php ENDPATH**/ ?>