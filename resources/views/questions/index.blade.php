@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 mb-2">بنك الأسئلة</h1>
                <p class="text-gray-600">تصفح الأسئلة المتاحة للتدريب</p>
            </div>
            <div class="flex items-center space-x-4 space-x-reverse">
                @if(request('show_all') == '1')
                    <a href="{{ route('questions.index') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        <i class="fas fa-check-circle ml-2"></i>عرض المعتمدة فقط
                    </a>
                @else
                    <a href="{{ route('questions.index', ['show_all' => 1]) }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                        <i class="fas fa-list ml-2"></i>عرض جميع الأسئلة
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Questions List -->
    <div class="space-y-4">
        @forelse($questions ?? [] as $question)
            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $question->question_text }}</h3>
                        <div class="flex items-center space-x-4 space-x-reverse text-sm text-gray-600">
                            @if($question->specialization)
                                <span class="flex items-center">
                                    <i class="fas fa-tag ml-2"></i>
                                    {{ $question->specialization->name_ar ?? 'غير محدد' }}
                                </span>
                            @endif
                            @if($question->difficulty_level)
                                <span class="px-2 py-1 rounded-full text-xs 
                                    @if($question->difficulty_level == 'easy') bg-green-100 text-green-800
                                    @elseif($question->difficulty_level == 'medium') bg-yellow-100 text-yellow-800
                                    @elseif($question->difficulty_level == 'hard') bg-orange-100 text-orange-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    {{ $question->difficulty_label }}
                                </span>
                            @endif
                            @if(!$question->is_approved)
                                <span class="px-2 py-1 rounded-full text-xs bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-clock ml-1"></i>قيد المراجعة
                                </span>
                            @endif
                        </div>
                        @if($question->explanation)
                            <p class="mt-3 text-sm text-gray-700 bg-gray-50 p-3 rounded-lg">
                                <strong>الشرح:</strong> {{ $question->explanation }}
                            </p>
                        @endif
                    </div>
                </div>
                @if($question->options && $question->options->count() > 0)
                    <div class="mt-4 pt-4 border-t">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">خيارات الإجابة:</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                            @foreach($question->options as $option)
                                <div class="flex items-center p-2 rounded 
                                    @if($option->is_correct) bg-green-50 border border-green-200
                                    @else bg-gray-50 border border-gray-200
                                    @endif">
                                    @if($option->is_correct)
                                        <i class="fas fa-check-circle text-green-600 ml-2"></i>
                                    @endif
                                    <span class="text-sm">{{ $option->option_text }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        @empty
            <div class="bg-white rounded-lg shadow-md p-12 text-center">
                <i class="fas fa-inbox text-4xl text-gray-400 mb-4"></i>
                <p class="text-gray-500 text-lg">لا توجد أسئلة متاحة حالياً</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if(isset($questions) && $questions->hasPages())
        <div class="mt-6">
            <div class="bg-white rounded-lg shadow-md p-4">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-700">
                        عرض 
                        <span class="font-medium">{{ $questions->firstItem() }}</span>
                        إلى 
                        <span class="font-medium">{{ $questions->lastItem() }}</span>
                        من 
                        <span class="font-medium">{{ $questions->total() }}</span>
                        سؤال
                    </div>
                    <div>
                        {{ $questions->links() }}
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection