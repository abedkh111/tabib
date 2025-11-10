<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $researchAndNews->title_ar }} - منصة طبيب التعليمية</title>
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
                    <h1 class="text-xl font-bold">عرض المقال</h1>
                </div>
                <div>
                    <a href="{{ route('admin.research-and-news.index') }}" class="bg-blue-700 hover:bg-blue-800 px-3 py-2 rounded">العودة</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-4xl mx-auto py-8 px-4">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="mb-6">
                <span class="px-3 py-1 text-sm rounded-full bg-blue-100 text-blue-800">{{ $researchAndNews->type_label }}</span>
                @if($researchAndNews->is_published)
                    <span class="px-3 py-1 text-sm rounded-full bg-green-100 text-green-800 mr-2">منشور</span>
                @endif
                @if($researchAndNews->is_featured)
                    <span class="px-3 py-1 text-sm rounded-full bg-purple-100 text-purple-800 mr-2">مميز</span>
                @endif
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $researchAndNews->title_ar }}</h1>
            @if($researchAndNews->image)
            <div class="mb-6">
                <img src="{{ asset('storage/' . $researchAndNews->image) }}" alt="{{ $researchAndNews->title_ar }}" class="w-full h-64 object-cover rounded-lg">
            </div>
            @endif
            @if($researchAndNews->summary_ar)
            <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                <p class="text-lg text-gray-700">{{ $researchAndNews->summary_ar }}</p>
            </div>
            @endif
            <div class="prose max-w-none mb-6">
                <p class="text-gray-700 whitespace-pre-line">{{ $researchAndNews->content_ar }}</p>
            </div>
            <div class="border-t pt-4 mt-6">
                <div class="grid grid-cols-2 gap-4 text-sm text-gray-600">
                    @if($researchAndNews->author)
                    <div><strong>المؤلف:</strong> {{ $researchAndNews->author }}</div>
                    @endif
                    @if($researchAndNews->source)
                    <div><strong>المصدر:</strong> {{ $researchAndNews->source }}</div>
                    @endif
                    @if($researchAndNews->published_date)
                    <div><strong>تاريخ النشر:</strong> {{ $researchAndNews->published_date->format('Y-m-d') }}</div>
                    @endif
                    <div><strong>المشاهدات:</strong> {{ $researchAndNews->views_count }}</div>
                </div>
                @if($researchAndNews->tags && count($researchAndNews->tags) > 0)
                <div class="mt-4">
                    <strong class="text-sm text-gray-700">الوسوم:</strong>
                    @foreach($researchAndNews->tags as $tag)
                        <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-700 mr-2">{{ $tag }}</span>
                    @endforeach
                </div>
                @endif
            </div>
            <div class="mt-6 flex justify-end space-x-4 space-x-reverse">
                <a href="{{ route('admin.research-and-news.edit', $researchAndNews) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <i class="fas fa-edit ml-2"></i>تعديل
                </a>
            </div>
        </div>
    </div>
</body>
</html>

