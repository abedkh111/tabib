@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-6">دوراتي</h1>

            @if(isset($enrollments) && $enrollments->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($enrollments as $enrollment)
                        <div class="bg-gray-50 rounded-lg p-4 border">
                            <h3 class="text-lg font-semibold mb-2">{{ $enrollment->course->title }}</h3>
                            <p class="text-sm text-gray-600 mb-2">{{ $enrollment->course->description }}</p>
                            <p class="text-sm text-gray-500 mb-4">المدرب: {{ $enrollment->course->instructor->name ?? 'غير محدد' }}</p>

                            <div class="mb-4">
                                <div class="flex justify-between text-sm mb-1">
                                    <span>التقدم</span>
                                    <span>{{ $enrollment->progress ?? 0 }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $enrollment->progress ?? 0 }}%"></div>
                                </div>
                            </div>

                            <div class="flex space-x-2 space-x-reverse">
                                <a href="{{ route('courses.learn', $enrollment->course) }}" class="bg-blue-500 text-white px-4 py-2 rounded text-sm flex-1 text-center">
                                    متابعة الدورة
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <p class="text-gray-500 mb-4">لم تسجل في أي دورة بعد</p>
                    <a href="{{ route('home') }}" class="bg-blue-500 text-white px-6 py-3 rounded">
                        تصفح الدورات
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection