<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $course->title }} - منصة طبيب التعليمية</title>
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
                    <h1 class="text-xl font-bold">{{ $course->title }}</h1>
                </div>
                <div class="flex items-center space-x-4 space-x-reverse">
                    <a href="{{ route('instructor.courses.index') }}" class="bg-green-700 hover:bg-green-800 px-3 py-2 rounded">العودة</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto py-8 px-4">
        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">{{ session('success') }}</div>
        @endif

        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h2 class="text-3xl font-bold mb-2">{{ $course->title }}</h2>
                    <p class="text-gray-600">{{ $course->description }}</p>
                </div>
                <div class="flex space-x-2 space-x-reverse">
                    @if(!$course->is_published)
                        <form method="POST" action="{{ route('instructor.courses.publish', $course) }}" class="inline">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg">نشر</button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('instructor.courses.unpublish', $course) }}" class="inline">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-yellow-600 text-white rounded-lg">إلغاء النشر</button>
                        </form>
                    @endif
                    <a href="{{ route('instructor.courses.edit', $course) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg">
                        <i class="fas fa-edit ml-2"></i>تعديل
                    </a>
                </div>
            </div>
            <div class="flex items-center space-x-4 space-x-reverse">
                @if($course->is_published)
                    <span class="px-3 py-1 rounded-full bg-green-100 text-green-800">منشور</span>
                @else
                    <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-800">مسودة</span>
                @endif
                <span class="text-gray-600">{{ $course->specialization->name_ar ?? '' }}</span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h3 class="text-xl font-bold mb-4">الدروس ({{ $course->lessons->count() }})</h3>
                    <div class="space-y-2">
                        @forelse($course->lessons as $lesson)
                            <div class="p-3 bg-gray-50 rounded-lg flex justify-between items-center">
                                <span>{{ $lesson->title }}</span>
                                <span class="text-sm text-gray-500">{{ $lesson->video_duration ?? 'غير محدد' }}</span>
                            </div>
                        @empty
                            <p class="text-gray-500">لا توجد دروس بعد</p>
                        @endforelse
                    </div>
                </div>
            </div>
            <div>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-bold mb-4">الإحصائيات</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between">
                            <span class="text-gray-600">عدد المسجلين</span>
                            <span class="font-semibold">{{ $course->enrolledStudents->count() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">عدد الدروس</span>
                            <span class="font-semibold">{{ $course->lessons->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

