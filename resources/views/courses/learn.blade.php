@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-6">{{ $course->title }}</h1>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Course Content -->
                <div class="lg:col-span-2">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h2 class="text-xl font-semibold mb-4">محتوى الدورة</h2>

                        @if(isset($course->lessons) && $course->lessons->count() > 0)
                            <div class="space-y-2">
                                @foreach($course->lessons as $lesson)
                                    <div class="flex items-center justify-between p-3 bg-white rounded border">
                                        <div>
                                            <h3 class="font-medium">{{ $lesson->title }}</h3>
                                            <p class="text-sm text-gray-600">{{ $lesson->description }}</p>
                                        </div>
                                        <button class="bg-blue-500 text-white px-4 py-2 rounded text-sm">
                                            عرض الدرس
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500">لا توجد دروس متاحة لهذه الدورة</p>
                        @endif
                    </div>
                </div>

                <!-- Course Info -->
                <div class="lg:col-span-1">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="font-semibold mb-4">معلومات الدورة</h3>
                        <div class="space-y-2 text-sm">
                            <p><strong>المدرب:</strong> {{ $course->instructor->name ?? 'غير محدد' }}</p>
                            <p><strong>التخصص:</strong> {{ $course->specialization->name ?? 'غير محدد' }}</p>
                            <p><strong>المستوى:</strong> {{ $course->level ?? 'غير محدد' }}</p>
                            <p><strong>المدة:</strong> {{ $course->duration ?? 'غير محدد' }}</p>
                        </div>

                        @if(isset($enrollment))
                            <div class="mt-4">
                                <div class="bg-green-100 text-green-800 px-3 py-2 rounded text-sm">
                                    مسجل في الدورة
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection