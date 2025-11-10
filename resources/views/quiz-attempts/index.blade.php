@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-6">محاولات الاختبارات</h1>

            @if(isset($attempts) && $attempts->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full table-auto">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-4 py-2 text-right">اسم الاختبار</th>
                                <th class="px-4 py-2 text-right">الدرجة</th>
                                <th class="px-4 py-2 text-right">الحالة</th>
                                <th class="px-4 py-2 text-right">تاريخ البدء</th>
                                <th class="px-4 py-2 text-right">تاريخ الانتهاء</th>
                                <th class="px-4 py-2 text-right">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($attempts as $attempt)
                                <tr class="border-t">
                                    <td class="px-4 py-2">{{ $attempt->quiz->title }}</td>
                                    <td class="px-4 py-2">
                                        <span class="font-semibold {{ $attempt->score >= 50 ? 'text-green-600' : 'text-red-600' }}">
                                            {{ number_format($attempt->score, 1) }}%
                                        </span>
                                    </td>
                                    <td class="px-4 py-2">
                                        @if($attempt->status === 'completed')
                                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-sm">مكتمل</span>
                                        @elseif($attempt->status === 'in_progress')
                                            <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-sm">قيد التنفيذ</span>
                                        @else
                                            <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded text-sm">{{ $attempt->status }}</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2">{{ $attempt->created_at->format('Y-m-d H:i') }}</td>
                                    <td class="px-4 py-2">
                                        {{ $attempt->completed_at ? $attempt->completed_at->format('Y-m-d H:i') : '-' }}
                                    </td>
                                    <td class="px-4 py-2">
                                        @if($attempt->status === 'completed')
                                            <a href="{{ route('quizzes.results', [$attempt->quiz, $attempt]) }}"
                                               class="bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600">
                                                عرض النتائج
                                            </a>
                                        @elseif($attempt->status === 'in_progress')
                                            <a href="{{ route('quizzes.start', $attempt->quiz) }}"
                                               class="bg-green-500 text-white px-3 py-1 rounded text-sm hover:bg-green-600">
                                                متابعة
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($attempts->hasPages())
                    <div class="mt-6">
                        {{ $attempts->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-12">
                    <p class="text-gray-500 mb-4">لم تحاول أي اختبار بعد</p>
                    <a href="{{ route('home') }}" class="bg-blue-500 text-white px-6 py-3 rounded">
                        تصفح الاختبارات
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection