@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-6">نتائج الاختبار: {{ $quiz->title }}</h1>

            <!-- Score Summary -->
            <div class="bg-gray-50 rounded-lg p-6 mb-6">
                <div class="text-center">
                    <div class="text-4xl font-bold text-blue-600 mb-2">{{ number_format($attempt->score, 1) }}%</div>
                    <p class="text-gray-600">
                        @if($attempt->score >= ($quiz->passing_score ?? 50))
                            <span class="text-green-600 font-semibold">ناجح</span>
                        @else
                            <span class="text-red-600 font-semibold">راسب</span>
                        @endif
                    </p>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6 text-center">
                    <div>
                        <div class="text-2xl font-bold text-green-600">{{ $attempt->answers->where('is_correct', true)->count() }}</div>
                        <div class="text-sm text-gray-600">إجابات صحيحة</div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-red-600">{{ $attempt->answers->where('is_correct', false)->count() }}</div>
                        <div class="text-sm text-gray-600">إجابات خاطئة</div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-blue-600">{{ $attempt->answers->count() }}</div>
                        <div class="text-sm text-gray-600">إجمالي الأسئلة</div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-purple-600">{{ $attempt->time_taken ? round($attempt->time_taken / 60, 1) : 0 }}</div>
                        <div class="text-sm text-gray-600">الوقت المستغرق (دقيقة)</div>
                    </div>
                </div>
            </div>

            <!-- Detailed Results -->
            <div class="space-y-4">
                <h2 class="text-xl font-semibold">تفاصيل الإجابات</h2>

                @foreach($attempt->answers as $answer)
                    <div class="border rounded-lg p-4 {{ $answer->is_correct ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200' }}">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h3 class="font-medium mb-2">{{ $answer->question->question_text }}</h3>

                                <div class="space-y-1 text-sm">
                                    <p>
                                        <span class="font-medium">إجابتك:</span>
                                        <span class="{{ $answer->is_correct ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $answer->selectedOption->option_text ?? 'لم يتم الاختيار' }}
                                        </span>
                                    </p>

                                    @if(!$answer->is_correct)
                                        <p>
                                            <span class="font-medium">الإجابة الصحيحة:</span>
                                            <span class="text-green-600">
                                                {{ $answer->question->correctOption->option_text ?? 'غير محدد' }}
                                            </span>
                                        </p>
                                    @endif

                                    @if($answer->question->explanation)
                                        <div class="mt-2 p-2 bg-blue-50 rounded text-sm">
                                            <strong>الشرح:</strong> {{ $answer->question->explanation }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="ml-4">
                                @if($answer->is_correct)
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-sm">صحيح</span>
                                @else
                                    <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-sm">خطأ</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Actions -->
            <div class="mt-8 text-center space-x-4 space-x-reverse">
                <a href="{{ route('quizzes.start', $quiz) }}" class="bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600 transition-colors">
                    إعادة الاختبار
                </a>
                <a href="{{ route('quiz-attempts.index') }}" class="bg-gray-500 text-white px-6 py-3 rounded-lg hover:bg-gray-600 transition-colors">
                    عرض جميع المحاولات
                </a>
            </div>
        </div>
    </div>
</div>
@endsection