<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>كورساتي - منصة طبيب التعليمية</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>body { font-family: 'Cairo', sans-serif; }</style>
</head>
<body class="bg-gray-50">
    <nav class="bg-green-600 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-4 space-x-reverse">
                    <i class="fas fa-book text-2xl"></i>
                    <h1 class="text-xl font-bold">كورساتي</h1>
                </div>
                <div class="flex items-center space-x-4 space-x-reverse">
                    <a href="{{ route('instructor.dashboard') }}" class="bg-green-700 hover:bg-green-800 px-3 py-2 rounded">العودة</a>
                    <a href="{{ route('dashboard') }}" class="bg-green-700 hover:bg-green-800 px-3 py-2 rounded">الرئيسية</a>
                    <span>{{ auth()->user()->name }}</span>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto py-8 px-4">
        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">{{ session('success') }}</div>
        @endif

        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-bold">كورساتي</h2>
                <a href="{{ route('instructor.courses.create') }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    <i class="fas fa-plus ml-2"></i>إضافة كورس جديد
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($courses as $course)
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <img src="{{ $course->thumbnail_url }}" alt="{{ $course->title }}" class="w-full h-48 object-cover">
                <div class="p-6">
                    <h3 class="text-xl font-bold mb-2">{{ $course->title }}</h3>
                    <p class="text-gray-600 text-sm mb-4">{{ Str::limit($course->description, 100) }}</p>
                    <div class="flex items-center justify-between mb-4">
                        @if($course->is_published)
                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">منشور</span>
                        @else
                            <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">مسودة</span>
                        @endif
                        <span class="text-sm text-gray-600">{{ $course->specialization->name_ar ?? '' }}</span>
                    </div>
                    <div class="flex space-x-2 space-x-reverse">
                        <a href="{{ route('instructor.courses.show', $course) }}" class="flex-1 text-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            <i class="fas fa-eye ml-2"></i>عرض
                        </a>
                        <a href="{{ route('instructor.courses.edit', $course) }}" class="flex-1 text-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                            <i class="fas fa-edit ml-2"></i>تعديل
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-12">
                <i class="fas fa-book text-4xl text-gray-400 mb-4"></i>
                <p class="text-gray-600">لا توجد كورسات بعد</p>
                <a href="{{ route('instructor.courses.create') }}" class="mt-4 inline-block px-6 py-2 bg-green-600 text-white rounded-lg">
                    إضافة كورس جديد
                </a>
            </div>
            @endforelse
        </div>

        @if($courses->hasPages())
            <div class="mt-6">{{ $courses->links() }}</div>
        @endif
    </div>
</body>
</html>

