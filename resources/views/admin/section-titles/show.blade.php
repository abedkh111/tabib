<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $sectionTitle->title_ar }} - منصة طبيب التعليمية</title>
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
                    <h1 class="text-xl font-bold">عرض عنوان القسم</h1>
                </div>
                <div>
                    <a href="{{ route('admin.section-titles.index') }}" class="bg-blue-700 hover:bg-blue-800 px-3 py-2 rounded">العودة</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-3xl mx-auto py-8 px-4">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="mb-6">
                @if($sectionTitle->is_active)
                    <span class="px-3 py-1 text-sm rounded-full bg-green-100 text-green-800">نشط</span>
                @else
                    <span class="px-3 py-1 text-sm rounded-full bg-red-100 text-red-800">غير نشط</span>
                @endif
            </div>
            <div class="space-y-4">
                <div>
                    <label class="text-sm font-medium text-gray-500">المفتاح:</label>
                    <p class="mt-1 text-lg font-mono text-gray-900">{{ $sectionTitle->key }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">العنوان (عربي):</label>
                    <p class="mt-1 text-lg text-gray-900">{{ $sectionTitle->title_ar }}</p>
                </div>
                @if($sectionTitle->title_en)
                <div>
                    <label class="text-sm font-medium text-gray-500">العنوان (إنجليزي):</label>
                    <p class="mt-1 text-lg text-gray-900">{{ $sectionTitle->title_en }}</p>
                </div>
                @endif
                @if($sectionTitle->description_ar)
                <div>
                    <label class="text-sm font-medium text-gray-500">الوصف (عربي):</label>
                    <p class="mt-1 text-gray-700">{{ $sectionTitle->description_ar }}</p>
                </div>
                @endif
                @if($sectionTitle->description_en)
                <div>
                    <label class="text-sm font-medium text-gray-500">الوصف (إنجليزي):</label>
                    <p class="mt-1 text-gray-700">{{ $sectionTitle->description_en }}</p>
                </div>
                @endif
                <div>
                    <label class="text-sm font-medium text-gray-500">ترتيب العرض:</label>
                    <p class="mt-1 text-gray-900">{{ $sectionTitle->sort_order }}</p>
                </div>
            </div>
            <div class="mt-6 flex justify-end space-x-4 space-x-reverse">
                <a href="{{ route('admin.section-titles.edit', $sectionTitle) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <i class="fas fa-edit ml-2"></i>تعديل
                </a>
            </div>
        </div>
    </div>
</body>
</html>

