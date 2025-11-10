<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $course->title }} - منصة طبيب التعليمية</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Alpine.js -->
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- Google Fonts for Arabic -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <style>
        body { 
            font-family: 'Cairo', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
        }
        .rtl { direction: rtl; }
    </style>
</head>
<body class="bg-gray-50 font-cairo">
    <!-- Navigation -->
    <nav class="bg-red-600 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-4 space-x-reverse">
                    <i class="fas fa-book text-2xl"></i>
                    <h1 class="text-xl font-bold">تفاصيل الكورس</h1>
                </div>
                <div class="flex items-center space-x-4 space-x-reverse">
                    <a href="{{ route('admin.courses.edit', $course) }}" class="bg-blue-600 hover:bg-blue-700 px-3 py-2 rounded">
                        <i class="fas fa-edit ml-2"></i>
                        تعديل الكورس
                    </a>
                    <a href="{{ route('admin.courses.index') }}" class="bg-red-700 hover:bg-red-800 px-3 py-2 rounded">
                        <i class="fas fa-arrow-right ml-2"></i>
                        العودة للقائمة
                    </a>
                    <a href="{{ route('admin.dashboard') }}" class="bg-red-700 hover:bg-red-800 px-3 py-2 rounded">
                        <i class="fas fa-home ml-2"></i>
                        الرئيسية
                    </a>
                    <span class="hidden md:inline">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="bg-red-700 hover:bg-red-800 px-3 py-2 rounded">
                            <i class="fas fa-sign-out-alt ml-2"></i>
                            خروج
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto py-8 px-4">
        <!-- Success Message -->
        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6" role="alert">
            <div class="flex items-center">
                <i class="fas fa-check-circle ml-2"></i>
                <span>{{ session('success') }}</span>
            </div>
        </div>
        @endif

        <!-- Course Header -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex items-start justify-between">
                <div class="flex items-start">
                    <img src="{{ $course->thumbnail_url }}" 
                         alt="{{ $course->title }}" 
                         class="w-32 h-32 rounded-lg object-cover ml-6">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $course->title }}</h1>
                        <p class="text-gray-600 mb-4">{{ $course->short_description ?? $course->description }}</p>
                        <div class="flex items-center space-x-4 space-x-reverse">
                            @if($course->is_published)
                                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle ml-1"></i>
                                    منشور
                                </span>
                            @else
                                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-clock ml-1"></i>
                                    قيد المراجعة
                                </span>
                            @endif
                            @if($course->is_featured)
                                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                    <i class="fas fa-star ml-1"></i>
                                    مميز
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="flex space-x-2 space-x-reverse">
                    @if(!$course->is_published)
                        <form method="POST" action="{{ route('admin.courses.approve', $course) }}" class="inline">
                            @csrf
                            <button type="submit" 
                                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                                <i class="fas fa-check ml-2"></i>
                                الموافقة
                            </button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('admin.courses.reject', $course) }}" class="inline">
                            @csrf
                            <button type="submit" 
                                    class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700">
                                <i class="fas fa-ban ml-2"></i>
                                إلغاء النشر
                            </button>
                        </form>
                    @endif
                    <form method="POST" action="{{ route('admin.courses.destroy', $course) }}" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700"
                                onclick="return confirm('هل أنت متأكد من حذف هذا الكورس؟')">
                            <i class="fas fa-trash ml-2"></i>
                            حذف
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Course Details -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Description -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">
                        <i class="fas fa-info-circle text-red-600 ml-2"></i>
                        الوصف
                    </h2>
                    <div class="prose max-w-none text-gray-700">
                        {{ $course->description }}
                    </div>
                </div>

                <!-- What You'll Learn -->
                @if($course->what_you_learn && is_array($course->what_you_learn) && count($course->what_you_learn) > 0)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">
                        <i class="fas fa-graduation-cap text-green-600 ml-2"></i>
                        ما سوف تتعلمه
                    </h2>
                    <ul class="space-y-2">
                        @foreach($course->what_you_learn as $item)
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-600 ml-2 mt-1"></i>
                            <span>{{ $item }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- Requirements -->
                @if($course->requirements && is_array($course->requirements) && count($course->requirements) > 0)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">
                        <i class="fas fa-list-check text-blue-600 ml-2"></i>
                        المتطلبات
                    </h2>
                    <ul class="space-y-2">
                        @foreach($course->requirements as $requirement)
                        <li class="flex items-start">
                            <i class="fas fa-circle text-gray-400 ml-2 mt-1 text-xs"></i>
                            <span>{{ $requirement }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- Lessons -->
                @if($course->lessons && $course->lessons->count() > 0)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">
                        <i class="fas fa-play-circle text-purple-600 ml-2"></i>
                        الدروس ({{ $course->lessons->count() }})
                    </h2>
                    <div class="space-y-2">
                        @foreach($course->lessons as $lesson)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-video text-gray-400 ml-2"></i>
                                <span>{{ $lesson->title }}</span>
                            </div>
                            <span class="text-sm text-gray-500">{{ $lesson->duration ?? 'غير محدد' }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Course Info -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">معلومات الكورس</h3>
                    <div class="space-y-4">
                        <div>
                            <span class="text-sm text-gray-500 block">المحاضر</span>
                            <span class="text-gray-900 font-medium">{{ $course->instructor->name ?? 'غير محدد' }}</span>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500 block">التخصص</span>
                            <span class="text-gray-900 font-medium">{{ $course->specialization->name_ar ?? 'غير محدد' }}</span>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500 block">المستوى</span>
                            <span class="text-gray-900 font-medium">{{ $course->level ?? 'غير محدد' }}</span>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500 block">اللغة</span>
                            <span class="text-gray-900 font-medium">{{ $course->language ?? 'عربي' }}</span>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500 block">المدة</span>
                            <span class="text-gray-900 font-medium">{{ $course->duration_hours ?? 0 }} ساعة</span>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500 block">السعر</span>
                            <span class="text-gray-900 font-bold text-lg">
                                @if($course->isFree())
                                    مجاني
                                @else
                                    @if($course->discounted_price)
                                        <span class="text-gray-400 line-through text-sm">{{ number_format($course->price, 2) }} ريال</span>
                                        <span class="text-red-600">{{ number_format($course->discounted_price, 2) }} ريال</span>
                                    @else
                                        {{ number_format($course->price, 2) }} ريال
                                    @endif
                                @endif
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">الإحصائيات</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between">
                            <span class="text-gray-600">عدد الدروس</span>
                            <span class="font-semibold">{{ $course->total_lessons }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">عدد المسجلين</span>
                            <span class="font-semibold">{{ $course->total_enrollments }}</span>
                        </div>
                        @if($course->enrollment_limit)
                        <div class="flex justify-between">
                            <span class="text-gray-600">الحد الأقصى</span>
                            <span class="font-semibold">{{ $course->enrollment_limit }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Dates -->
                @if($course->starts_at || $course->ends_at)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">التواريخ</h3>
                    <div class="space-y-4">
                        @if($course->starts_at)
                        <div>
                            <span class="text-sm text-gray-500 block">تاريخ البدء</span>
                            <span class="text-gray-900">{{ $course->starts_at->format('Y/m/d') }}</span>
                        </div>
                        @endif
                        @if($course->ends_at)
                        <div>
                            <span class="text-sm text-gray-500 block">تاريخ الانتهاء</span>
                            <span class="text-gray-900">{{ $course->ends_at->format('Y/m/d') }}</span>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-12">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p>&copy; 2024 منصة طبيب التعليمية - لوحة الإدارة. جميع الحقوق محفوظة.</p>
        </div>
    </footer>
</body>
</html>

