<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تفاصيل السؤال - منصة طبيب التعليمية</title>
    
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
                    <i class="fas fa-question-circle text-2xl"></i>
                    <h1 class="text-xl font-bold">تفاصيل السؤال</h1>
                </div>
                <div class="flex items-center space-x-4 space-x-reverse">
                    <a href="{{ route('admin.questions.index') }}" class="bg-red-700 hover:bg-red-800 px-3 py-2 rounded">
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

        <!-- Question Header -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <div class="flex items-center space-x-4 space-x-reverse mb-4">
                        @if($question->is_approved)
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                <i class="fas fa-check-circle ml-1"></i>
                                معتمد
                            </span>
                        @else
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                <i class="fas fa-clock ml-1"></i>
                                قيد المراجعة
                            </span>
                        @endif
                        @if(!$question->is_active)
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                <i class="fas fa-ban ml-1"></i>
                                غير نشط
                            </span>
                        @endif
                        @php
                            $difficultyColors = [
                                'easy' => 'bg-green-100 text-green-800',
                                'medium' => 'bg-yellow-100 text-yellow-800',
                                'hard' => 'bg-orange-100 text-orange-800',
                                'expert' => 'bg-red-100 text-red-800',
                            ];
                            $color = $difficultyColors[$question->difficulty_level] ?? 'bg-gray-100 text-gray-800';
                        @endphp
                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full {{ $color }}">
                            {{ $question->difficulty_label }}
                        </span>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-800 mb-4">{{ $question->question_text }}</h1>
                    @if($question->question_image)
                        <div class="mb-4">
                            <img src="{{ $question->question_image_url }}" 
                                 alt="صورة السؤال" 
                                 class="max-w-md rounded-lg shadow-md">
                        </div>
                    @endif
                </div>
                <div class="flex space-x-2 space-x-reverse mr-6">
                    <a href="{{ route('admin.questions.edit', $question) }}" 
                       class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        <i class="fas fa-edit ml-2"></i>
                        تعديل
                    </a>
                    @if(!$question->is_approved)
                        <form method="POST" action="{{ route('admin.questions.approve', $question) }}" class="inline">
                            @csrf
                            <button type="submit" 
                                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                                <i class="fas fa-check ml-2"></i>
                                الموافقة
                            </button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('admin.questions.reject', $question) }}" class="inline">
                            @csrf
                            <button type="submit" 
                                    class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700">
                                <i class="fas fa-ban ml-2"></i>
                                رفض
                            </button>
                        </form>
                    @endif
                    <form method="POST" action="{{ route('admin.questions.destroy', $question) }}" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700"
                                onclick="return confirm('هل أنت متأكد من حذف هذا السؤال؟')">
                            <i class="fas fa-trash ml-2"></i>
                            حذف
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Question Details -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Answer Options -->
                @if($question->options && $question->options->count() > 0)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">
                        <i class="fas fa-list-ul text-blue-600 ml-2"></i>
                        خيارات الإجابة
                    </h2>
                    <div class="space-y-3">
                        @foreach($question->options as $option)
                        <div class="flex items-start p-4 rounded-lg {{ $option->is_correct ? 'bg-green-50 border-2 border-green-300' : 'bg-gray-50 border border-gray-200' }}">
                            <div class="flex-shrink-0 mr-4">
                                @if($option->is_correct)
                                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                                @else
                                    <i class="fas fa-circle text-gray-400 text-xl"></i>
                                @endif
                            </div>
                            <div class="flex-1">
                                <div class="text-gray-900 font-medium">{{ $option->option_text }}</div>
                                @if($option->is_correct)
                                    <span class="mt-2 inline-block px-2 py-1 text-xs font-semibold rounded bg-green-100 text-green-800">
                                        الإجابة الصحيحة
                                    </span>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Explanation -->
                @if($question->explanation)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">
                        <i class="fas fa-lightbulb text-yellow-600 ml-2"></i>
                        الشرح
                    </h2>
                    <div class="prose max-w-none text-gray-700">
                        {{ $question->explanation }}
                    </div>
                </div>
                @endif

                <!-- Reference -->
                @if($question->reference)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">
                        <i class="fas fa-book text-purple-600 ml-2"></i>
                        المراجع
                    </h2>
                    <div class="text-gray-700">
                        {{ $question->reference }}
                    </div>
                </div>
                @endif

                <!-- Tags -->
                @if($question->tags && count($question->tags) > 0)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">
                        <i class="fas fa-tags text-indigo-600 ml-2"></i>
                        الوسوم
                    </h2>
                    <div class="flex flex-wrap gap-2">
                        @foreach($question->tags as $tag)
                        <span class="px-3 py-1 bg-indigo-100 text-indigo-800 rounded-full text-sm">
                            {{ $tag }}
                        </span>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Question Info -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">معلومات السؤال</h3>
                    <div class="space-y-4">
                        <div>
                            <span class="text-sm text-gray-500 block">التخصص</span>
                            <span class="text-gray-900 font-medium">{{ $question->specialization->name_ar ?? 'غير محدد' }}</span>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500 block">المستوى</span>
                            <span class="text-gray-900 font-medium">{{ $question->difficulty_label }}</span>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500 block">النقاط</span>
                            <span class="text-gray-900 font-medium">{{ $question->points }}</span>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500 block">الوقت المحدد</span>
                            <span class="text-gray-900 font-medium">{{ $question->time_limit }} ثانية</span>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500 block">المنشئ</span>
                            <span class="text-gray-900 font-medium">{{ $question->user->name ?? 'غير محدد' }}</span>
                            @if($question->user)
                            <span class="text-xs text-gray-500 block">{{ $question->user->email }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">الإحصائيات</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between">
                            <span class="text-gray-600">عدد المحاولات</span>
                            <span class="font-semibold">{{ $question->total_attempts }}</span>
                        </div>
                        @if($question->total_attempts > 0)
                        <div class="flex justify-between">
                            <span class="text-gray-600">معدل النجاح</span>
                            <span class="font-semibold">{{ $question->success_rate }}%</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">متوسط الوقت</span>
                            <span class="font-semibold">{{ round($question->average_time, 1) }} ثانية</span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Dates -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">التواريخ</h3>
                    <div class="space-y-4">
                        <div>
                            <span class="text-sm text-gray-500 block">تاريخ الإنشاء</span>
                            <span class="text-gray-900">{{ $question->created_at->format('Y/m/d H:i') }}</span>
                        </div>
                        @if($question->updated_at != $question->created_at)
                        <div>
                            <span class="text-sm text-gray-500 block">آخر تحديث</span>
                            <span class="text-gray-900">{{ $question->updated_at->format('Y/m/d H:i') }}</span>
                        </div>
                        @endif
                    </div>
                </div>
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

