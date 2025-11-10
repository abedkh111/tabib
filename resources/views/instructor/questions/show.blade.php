<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تفاصيل السؤال - منصة طبيب التعليمية</title>
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
                    <i class="fas fa-question-circle text-2xl"></i>
                    <h1 class="text-xl font-bold">تفاصيل السؤال</h1>
                </div>
                <div>
                    <a href="{{ route('instructor.questions.index') }}" class="bg-green-700 hover:bg-green-800 px-3 py-2 rounded">العودة</a>
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
                    <h2 class="text-2xl font-bold mb-2">{{ $question->question_text }}</h2>
                    <div class="flex items-center space-x-4 space-x-reverse mt-4">
                        @if($question->is_approved)
                            <span class="px-3 py-1 rounded-full bg-green-100 text-green-800">معتمد</span>
                        @else
                            <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-800">قيد المراجعة</span>
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
                        <span class="px-3 py-1 rounded-full {{ $color }}">{{ $question->difficulty_label }}</span>
                    </div>
                </div>
                <div class="flex space-x-2 space-x-reverse">
                    <a href="{{ route('instructor.questions.edit', $question) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg">
                        <i class="fas fa-edit ml-2"></i>تعديل
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h3 class="text-xl font-bold mb-4">خيارات الإجابة</h3>
                    <div class="space-y-3">
                        @forelse($question->options as $option)
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
                        @empty
                        <p class="text-gray-500">لا توجد خيارات</p>
                        @endforelse
                    </div>
                </div>

                @if($question->explanation)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-xl font-bold mb-4">الشرح</h3>
                    <p class="text-gray-700">{{ $question->explanation }}</p>
                </div>
                @endif
            </div>

            <div>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-bold mb-4">معلومات السؤال</h3>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

