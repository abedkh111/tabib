<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>أسئلتي - منصة طبيب التعليمية</title>
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
                    <h1 class="text-xl font-bold">أسئلتي</h1>
                </div>
                <div class="flex items-center space-x-4 space-x-reverse">
                    <a href="{{ route('instructor.dashboard') }}" class="bg-green-700 hover:bg-green-800 px-3 py-2 rounded">العودة</a>
                    <a href="{{ route('dashboard') }}" class="bg-green-700 hover:bg-green-800 px-3 py-2 rounded">الرئيسية</a>
                    <span>{{ auth()->user()->name }}</span>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto py-8 px-4">
        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">{{ session('success') }}</div>
        @endif

        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-bold">أسئلتي</h2>
                <a href="{{ route('instructor.questions.create') }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    <i class="fas fa-plus ml-2"></i>إضافة سؤال جديد
                </a>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">السؤال</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">التخصص</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الصعوبة</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الحالة</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($questions as $question)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="max-w-md">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ Str::limit($question->question_text, 80) }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-900">{{ $question->specialization->name_ar ?? 'غير محدد' }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $difficultyColors = [
                                        'easy' => 'bg-green-100 text-green-800',
                                        'medium' => 'bg-yellow-100 text-yellow-800',
                                        'hard' => 'bg-orange-100 text-orange-800',
                                        'expert' => 'bg-red-100 text-red-800',
                                    ];
                                    $color = $difficultyColors[$question->difficulty_level] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $color }}">
                                    {{ $question->difficulty_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($question->is_approved)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle ml-1"></i>معتمد
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-clock ml-1"></i>قيد المراجعة
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2 space-x-reverse">
                                    <a href="{{ route('instructor.questions.show', $question) }}" class="text-blue-600 hover:text-blue-900">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('instructor.questions.edit', $question) }}" class="text-green-600 hover:text-green-900">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('instructor.questions.destroy', $question) }}" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" onclick="return confirm('هل أنت متأكد؟')" class="text-red-600 hover:text-red-900">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-inbox text-4xl mb-4"></i>
                                <p>لا توجد أسئلة بعد</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($questions->hasPages())
                <div class="bg-gray-50 px-4 py-3">{{ $questions->links() }}</div>
            @endif
        </div>
    </div>
</body>
</html>

