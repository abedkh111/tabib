@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-6">{{ $quiz->title }}</h1>

            @if(isset($quiz->description))
                <p class="text-gray-600 mb-6">{{ $quiz->description }}</p>
            @endif

            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <h3 class="font-semibold text-blue-900 mb-2">معلومات الاختبار</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                    <div>
                        <span class="font-medium">عدد الأسئلة:</span>
                        <span>{{ $quiz->questions->count() }}</span>
                    </div>
                    <div>
                        <span class="font-medium">الوقت المحدد:</span>
                        <span>{{ $quiz->time_limit ?? 'غير محدود' }} دقيقة</span>
                    </div>
                    <div>
                        <span class="font-medium">درجة النجاح:</span>
                        <span>{{ $quiz->passing_score ?? 50 }}%</span>
                    </div>
                    <div>
                        <span class="font-medium">عدد المحاولات:</span>
                        <span>{{ $quiz->max_attempts ?? 'غير محدود' }}</span>
                    </div>
                </div>
            </div>

            <div class="text-center">
                <form method="POST" action="{{ route('quizzes.start', $quiz) }}">
                    @csrf
                    <button type="submit" class="bg-green-500 text-white px-8 py-3 rounded-lg text-lg font-semibold hover:bg-green-600 transition-colors">
                        بدء الاختبار
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection