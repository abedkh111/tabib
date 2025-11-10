<?php $__env->startSection('content'); ?>
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

    <!-- Header -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center ml-4">
                    <i class="fas fa-edit text-2xl text-blue-600"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">تعديل الكورس</h2>
                    <p class="text-gray-600">تعديل معلومات الكورس: <?php echo e($course->title); ?></p>
                </div>
            </div>
            <div class="flex items-center space-x-4 space-x-reverse">
                <a href="<?php echo e(route('admin.courses.show', $course)); ?>" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                    <i class="fas fa-eye ml-2"></i>عرض التفاصيل
                </a>
                <a href="<?php echo e(route('admin.courses.index')); ?>" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                    <i class="fas fa-arrow-right ml-2"></i>العودة
                </a>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <?php if($errors->any()): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <ul class="list-disc list-inside">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('admin.courses.update', $course)); ?>">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            <div class="space-y-6">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">عنوان الكورس <span class="text-red-500">*</span></label>
                    <input type="text" id="title" name="title" value="<?php echo e(old('title', $course->title)); ?>" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label for="short_description" class="block text-sm font-medium text-gray-700 mb-2">الوصف المختصر</label>
                    <textarea id="short_description" name="short_description" rows="3"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"><?php echo e(old('short_description', $course->short_description)); ?></textarea>
                    <p class="text-sm text-gray-500 mt-1">وصف مختصر للكورس (حد أقصى 500 حرف)</p>
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">الوصف الكامل <span class="text-red-500">*</span></label>
                    <textarea id="description" name="description" rows="6" required
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"><?php echo e(old('description', $course->description)); ?></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="specialization_id" class="block text-sm font-medium text-gray-700 mb-2">التخصص <span class="text-red-500">*</span></label>
                        <select id="specialization_id" name="specialization_id" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            <option value="">اختر التخصص</option>
                            <?php $__currentLoopData = $specializations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $spec): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($spec->id); ?>" 
                                        <?php echo e(($course->specialization_id == $spec->id || old('specialization_id') == $spec->id) ? 'selected' : ''); ?>>
                                    <?php echo e($spec->name_ar); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div>
                        <label for="instructor_id" class="block text-sm font-medium text-gray-700 mb-2">المحاضر <span class="text-red-500">*</span></label>
                        <select id="instructor_id" name="instructor_id" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            <option value="">اختر المحاضر</option>
                            <?php $__currentLoopData = $instructors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $instructor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($instructor->id); ?>" 
                                        <?php echo e(($course->instructor_id == $instructor->id || old('instructor_id') == $instructor->id) ? 'selected' : ''); ?>>
                                    <?php echo e($instructor->name); ?> (<?php echo e($instructor->email); ?>)
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="duration_hours" class="block text-sm font-medium text-gray-700 mb-2">المدة (ساعات)</label>
                        <input type="number" id="duration_hours" name="duration_hours" 
                               value="<?php echo e(old('duration_hours', $course->duration_hours)); ?>" min="1"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-2">السعر (ريال)</label>
                        <input type="number" id="price" name="price" 
                               value="<?php echo e(old('price', $course->price)); ?>" step="0.01" min="0"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label for="discounted_price" class="block text-sm font-medium text-gray-700 mb-2">السعر المخفض (ريال)</label>
                        <input type="number" id="discounted_price" name="discounted_price" 
                               value="<?php echo e(old('discounted_price', $course->discounted_price)); ?>" step="0.01" min="0"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="level" class="block text-sm font-medium text-gray-700 mb-2">المستوى</label>
                        <select id="level" name="level"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            <option value="">اختر المستوى</option>
                            <option value="beginner" <?php echo e(($course->level == 'beginner' || old('level') == 'beginner') ? 'selected' : ''); ?>>مبتدئ</option>
                            <option value="intermediate" <?php echo e(($course->level == 'intermediate' || old('level') == 'intermediate') ? 'selected' : ''); ?>>متوسط</option>
                            <option value="advanced" <?php echo e(($course->level == 'advanced' || old('level') == 'advanced') ? 'selected' : ''); ?>>متقدم</option>
                        </select>
                    </div>
                    <div>
                        <label for="language" class="block text-sm font-medium text-gray-700 mb-2">اللغة</label>
                        <input type="text" id="language" name="language" 
                               value="<?php echo e(old('language', $course->language)); ?>"
                               placeholder="مثال: العربية"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <div class="flex items-center space-x-6 space-x-reverse">
                    <div class="flex items-center">
                        <input type="checkbox" id="is_published" name="is_published" value="1" 
                               <?php echo e(($course->is_published || old('is_published')) ? 'checked' : ''); ?>

                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="is_published" class="mr-2 block text-sm text-gray-700">
                            نشر الكورس مباشرة
                        </label>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" id="is_featured" name="is_featured" value="1" 
                               <?php echo e(($course->is_featured || old('is_featured')) ? 'checked' : ''); ?>

                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="is_featured" class="mr-2 block text-sm text-gray-700">
                            كورس مميز
                        </label>
                    </div>
                </div>

                <div class="flex justify-end space-x-4 space-x-reverse pt-4 border-t">
                    <a href="<?php echo e(route('admin.courses.index')); ?>" class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                        إلغاء
                    </a>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        <i class="fas fa-save ml-2"></i>حفظ التغييرات
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/abedkh/Documents/tabib/resources/views/admin/courses/edit.blade.php ENDPATH**/ ?>