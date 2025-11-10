@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-6">تدريب على الأسئلة</h1>

            <div id="quiz-container">
                @if(isset($questions) && $questions->count() > 0)
                    <div class="space-y-6">
                        @foreach($questions as $index => $question)
                            <div class="question-item {{ $index === 0 ? '' : 'hidden' }}" data-question-id="{{ $question->id }}">
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <h3 class="text-lg font-semibold mb-4">{{ $question->question_text }}</h3>

                                    @if($question->hasImage())
                                        <img src="{{ $question->question_image_url }}" alt="Question Image" class="mb-4 max-w-full h-auto">
                                    @endif

                                    <div class="space-y-2">
                                        @foreach($question->options as $option)
                                            <label class="flex items-center space-x-3 space-x-reverse">
                                                <input type="radio" name="answer[{{ $question->id }}]" value="{{ $option->id }}" class="form-radio">
                                                <span>{{ $option->option_text }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="mt-4 flex justify-between">
                                    <button class="bg-blue-500 text-white px-4 py-2 rounded prev-btn" {{ $index === 0 ? 'disabled' : '' }}>السابق</button>
                                    <button class="bg-green-500 text-white px-4 py-2 rounded submit-answer-btn">إرسال الإجابة</button>
                                    <button class="bg-blue-500 text-white px-4 py-2 rounded next-btn" {{ $index === $questions->count() - 1 ? 'disabled' : '' }}>التالي</button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500">لا توجد أسئلة متاحة للتدريب</p>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add JavaScript for quiz functionality here
    console.log('Practice page loaded');
});
</script>
@endsection