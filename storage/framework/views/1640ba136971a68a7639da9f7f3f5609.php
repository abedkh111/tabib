<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>أبحاث وأخبار - منصة طبيب التعليمية</title>
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
                    <i class="fas fa-newspaper text-2xl"></i>
                    <h1 class="text-xl font-bold">أبحاث وأخبار</h1>
                </div>
                <div class="flex items-center space-x-4 space-x-reverse">
                    <a href="<?php echo e(route('admin.dashboard')); ?>" class="bg-blue-700 hover:bg-blue-800 px-3 py-2 rounded">العودة</a>
                    <a href="<?php echo e(route('admin.research-and-news.create')); ?>" class="bg-green-600 hover:bg-green-700 px-4 py-2 rounded">
                        <i class="fas fa-plus ml-2"></i>إضافة مقال
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto py-8 px-4">
        <?php if(session('success')): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6"><?php echo e(session('success')); ?></div>
        <?php endif; ?>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-md p-4 mb-6">
            <form method="GET" action="<?php echo e(route('admin.research-and-news.index')); ?>" class="flex items-center space-x-4 space-x-reverse">
                <select name="type" class="px-4 py-2 border border-gray-300 rounded-lg">
                    <option value="">جميع الأنواع</option>
                    <option value="medical_research" <?php echo e(request('type') == 'medical_research' ? 'selected' : ''); ?>>أبحاث طبية</option>
                    <option value="scientific_news" <?php echo e(request('type') == 'scientific_news' ? 'selected' : ''); ?>>أخبار علمية</option>
                    <option value="medical_news" <?php echo e(request('type') == 'medical_news' ? 'selected' : ''); ?>>أخبار طبية</option>
                </select>
                <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="بحث..." class="flex-1 px-4 py-2 border border-gray-300 rounded-lg">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <i class="fas fa-search ml-2"></i>بحث
                </button>
            </form>
        </div>

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">العنوان</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">النوع</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الحالة</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">تاريخ النشر</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">المشاهدات</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900"><?php echo e($item->title_ar); ?></div>
                            <?php if($item->summary_ar): ?>
                            <div class="text-sm text-gray-500 mt-1"><?php echo e(Str::limit($item->summary_ar, 50)); ?></div>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800"><?php echo e($item->type_label); ?></span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php if($item->is_published): ?>
                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">منشور</span>
                            <?php else: ?>
                                <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">مسودة</span>
                            <?php endif; ?>
                            <?php if($item->is_featured): ?>
                                <span class="px-2 py-1 text-xs rounded-full bg-purple-100 text-purple-800 mr-2">مميز</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?php echo e($item->published_date ? $item->published_date->format('Y-m-d') : '-'); ?>

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?php echo e($item->views_count); ?>

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <a href="<?php echo e(route('admin.research-and-news.show', $item)); ?>" class="text-blue-600 hover:text-blue-900 ml-4"><i class="fas fa-eye"></i></a>
                            <a href="<?php echo e(route('admin.research-and-news.edit', $item)); ?>" class="text-green-600 hover:text-green-900 ml-4"><i class="fas fa-edit"></i></a>
                            <form method="POST" action="<?php echo e(route('admin.research-and-news.destroy', $item)); ?>" class="inline">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button type="submit" onclick="return confirm('هل أنت متأكد؟')" class="text-red-600 hover:text-red-900"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="6" class="px-6 py-8 text-center">لا توجد مقالات</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <?php if($items->hasPages()): ?>
                <div class="bg-gray-50 px-4 py-3"><?php echo e($items->links()); ?></div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

<?php /**PATH /home/abedkh/Documents/tabib/resources/views/admin/research-and-news/index.blade.php ENDPATH**/ ?>