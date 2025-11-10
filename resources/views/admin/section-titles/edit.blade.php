<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل عنوان قسم - منصة طبيب التعليمية</title>
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
                    <i class="fas fa-edit text-2xl"></i>
                    <h1 class="text-xl font-bold">تعديل عنوان قسم</h1>
                </div>
                <div>
                    <a href="{{ route('admin.section-titles.index') }}" class="bg-blue-700 hover:bg-blue-800 px-3 py-2 rounded">العودة</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-3xl mx-auto py-8 px-4">
        @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="bg-white rounded-lg shadow-md p-6">
            <form method="POST" action="{{ route('admin.section-titles.update', $sectionTitle) }}">
                @csrf
                @method('PUT')
                <div class="space-y-6">
                    <div>
                        <label for="key" class="block text-sm font-medium text-gray-700 mb-2">المفتاح (يستخدم في الكود) *</label>
                        <input type="text" id="key" name="key" value="{{ old('key', $sectionTitle->key) }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <p class="mt-1 text-sm text-gray-500">يستخدم هذا المفتاح في الكود للوصول إلى العنوان</p>
                    </div>
                    <div>
                        <label for="title_ar" class="block text-sm font-medium text-gray-700 mb-2">العنوان (عربي) *</label>
                        <input type="text" id="title_ar" name="title_ar" value="{{ old('title_ar', $sectionTitle->title_ar) }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label for="title_en" class="block text-sm font-medium text-gray-700 mb-2">العنوان (إنجليزي)</label>
                        <input type="text" id="title_en" name="title_en" value="{{ old('title_en', $sectionTitle->title_en) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label for="description_ar" class="block text-sm font-medium text-gray-700 mb-2">الوصف (عربي)</label>
                        <textarea id="description_ar" name="description_ar" rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">{{ old('description_ar', $sectionTitle->description_ar) }}</textarea>
                    </div>
                    <div>
                        <label for="description_en" class="block text-sm font-medium text-gray-700 mb-2">الوصف (إنجليزي)</label>
                        <textarea id="description_en" name="description_en" rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">{{ old('description_en', $sectionTitle->description_en) }}</textarea>
                    </div>
                    <div>
                        <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">ترتيب العرض</label>
                        <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $sectionTitle->sort_order) }}" min="0"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="flex items-center">
                        <label class="flex items-center">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $sectionTitle->is_active) ? 'checked' : '' }}
                                   class="ml-2 w-5 h-5 text-blue-600 border-gray-300 rounded">
                            <span class="text-sm text-gray-700">نشط</span>
                        </label>
                    </div>
                    <div class="flex justify-end space-x-4 space-x-reverse">
                        <a href="{{ route('admin.section-titles.index') }}" class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg">إلغاء</a>
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            <i class="fas fa-save ml-2"></i>حفظ
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

