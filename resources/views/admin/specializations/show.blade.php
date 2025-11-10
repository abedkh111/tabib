<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $specialization->name_ar }} - منصة طبيب التعليمية</title>
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
                    <i class="fas fa-stethoscope text-2xl"></i>
                    <h1 class="text-xl font-bold">{{ $specialization->name_ar }}</h1>
                </div>
                <div>
                    <a href="{{ route('admin.specializations.index') }}" class="bg-red-700 hover:bg-red-800 px-3 py-2 rounded">العودة</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto py-8 px-4">
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-2xl font-bold mb-4">{{ $specialization->name_ar }}</h2>
            <p class="text-gray-600 mb-4">{{ $specialization->name_en }}</p>
            @if($specialization->description)
                <p class="text-gray-700">{{ $specialization->description }}</p>
            @endif
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold mb-4">الكورسات ({{ $specialization->courses->count() }})</h3>
                <div class="space-y-2">
                    @forelse($specialization->courses as $course)
                        <div class="p-2 bg-gray-50 rounded">{{ $course->title }}</div>
                    @empty
                        <p class="text-gray-500">لا توجد كورسات</p>
                    @endforelse
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold mb-4">الأسئلة ({{ $specialization->questions->count() }})</h3>
                <p class="text-gray-600">عدد الأسئلة في هذا التخصص</p>
            </div>
        </div>
    </div>
</body>
</html>

