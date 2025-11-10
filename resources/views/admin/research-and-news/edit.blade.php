<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل مقال - منصة طبيب التعليمية</title>
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
                    <h1 class="text-xl font-bold">تعديل مقال</h1>
                </div>
                <div>
                    <a href="{{ route('admin.research-and-news.index') }}" class="bg-blue-700 hover:bg-blue-800 px-3 py-2 rounded">العودة</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-4xl mx-auto py-8 px-4">
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
            <form method="POST" action="{{ route('admin.research-and-news.update', $researchAndNews) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="space-y-6">
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-2">النوع</label>
                        <select id="type" name="type" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            <option value="medical_research" {{ old('type', $researchAndNews->type) == 'medical_research' ? 'selected' : '' }}>أبحاث طبية</option>
                            <option value="scientific_news" {{ old('type', $researchAndNews->type) == 'scientific_news' ? 'selected' : '' }}>أخبار علمية</option>
                            <option value="medical_news" {{ old('type', $researchAndNews->type) == 'medical_news' ? 'selected' : '' }}>أخبار طبية</option>
                        </select>
                    </div>
                    <div>
                        <label for="title_ar" class="block text-sm font-medium text-gray-700 mb-2">العنوان (عربي) *</label>
                        <input type="text" id="title_ar" name="title_ar" value="{{ old('title_ar', $researchAndNews->title_ar) }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label for="title_en" class="block text-sm font-medium text-gray-700 mb-2">العنوان (إنجليزي)</label>
                        <input type="text" id="title_en" name="title_en" value="{{ old('title_en', $researchAndNews->title_en) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label for="summary_ar" class="block text-sm font-medium text-gray-700 mb-2">الملخص (عربي)</label>
                        <textarea id="summary_ar" name="summary_ar" rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">{{ old('summary_ar', $researchAndNews->summary_ar) }}</textarea>
                    </div>
                    <div>
                        <label for="content_ar" class="block text-sm font-medium text-gray-700 mb-2">المحتوى (عربي) *</label>
                        <textarea id="content_ar" name="content_ar" rows="10" required
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">{{ old('content_ar', $researchAndNews->content_ar) }}</textarea>
                    </div>
                    <div>
                        <label for="content_en" class="block text-sm font-medium text-gray-700 mb-2">المحتوى (إنجليزي)</label>
                        <textarea id="content_en" name="content_en" rows="10"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">{{ old('content_en', $researchAndNews->content_en) }}</textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="author" class="block text-sm font-medium text-gray-700 mb-2">المؤلف</label>
                            <input type="text" id="author" name="author" value="{{ old('author', $researchAndNews->author) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label for="source" class="block text-sm font-medium text-gray-700 mb-2">المصدر</label>
                            <input type="text" id="source" name="source" value="{{ old('source', $researchAndNews->source) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                    <div>
                        <label for="published_date" class="block text-sm font-medium text-gray-700 mb-2">تاريخ النشر</label>
                        <input type="date" id="published_date" name="published_date" value="{{ old('published_date', $researchAndNews->published_date?->format('Y-m-d')) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label for="tags" class="block text-sm font-medium text-gray-700 mb-2">الوسوم (مفصولة بفواصل)</label>
                        <input type="text" id="tags" name="tags" value="{{ old('tags', is_array($researchAndNews->tags) ? implode(', ', $researchAndNews->tags) : '') }}"
                               placeholder="طبي، بحث، علمي"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label for="image" class="block text-sm font-medium text-gray-700 mb-2">الصورة</label>
                        @if($researchAndNews->image)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $researchAndNews->image) }}" alt="Current image" class="w-32 h-32 object-cover rounded">
                        </div>
                        @endif
                        <input type="file" id="image" name="image" accept="image/*"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="flex items-center space-x-4 space-x-reverse">
                        <label class="flex items-center">
                            <input type="checkbox" name="is_published" value="1" {{ old('is_published', $researchAndNews->is_published) ? 'checked' : '' }}
                                   class="ml-2 w-5 h-5 text-blue-600 border-gray-300 rounded">
                            <span class="text-sm text-gray-700">نشر مباشرة</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $researchAndNews->is_featured) ? 'checked' : '' }}
                                   class="ml-2 w-5 h-5 text-blue-600 border-gray-300 rounded">
                            <span class="text-sm text-gray-700">مميز</span>
                        </label>
                    </div>
                    <div class="flex justify-end space-x-4 space-x-reverse">
                        <a href="{{ route('admin.research-and-news.index') }}" class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg">إلغاء</a>
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

