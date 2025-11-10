<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>التحليلات - منصة طبيب التعليمية</title>
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
                    <i class="fas fa-chart-bar text-2xl"></i>
                    <h1 class="text-xl font-bold">التحليلات</h1>
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
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <div class="flex items-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center ml-4">
                    <i class="fas fa-chart-bar text-2xl text-green-600"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">التحليلات والإحصائيات</h2>
                    <p class="text-gray-600">نظرة شاملة على أداءك في المنصة</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">إجمالي الكورسات</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $stats['total_courses'] }}</p>
                    </div>
                    <i class="fas fa-book text-4xl text-blue-500"></i>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">الكورسات المنشورة</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $stats['published_courses'] }}</p>
                    </div>
                    <i class="fas fa-check-circle text-4xl text-green-500"></i>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">إجمالي الطلاب</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $stats['total_students'] }}</p>
                    </div>
                    <i class="fas fa-users text-4xl text-purple-500"></i>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">إجمالي الأسئلة</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $stats['total_questions'] }}</p>
                    </div>
                    <i class="fas fa-question-circle text-4xl text-orange-500"></i>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">الأسئلة المعتمدة</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $stats['approved_questions'] }}</p>
                    </div>
                    <i class="fas fa-check-double text-4xl text-indigo-500"></i>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-bold mb-4">نسبة الكورسات المنشورة</h3>
                @php
                    $publishedPercentage = $stats['total_courses'] > 0 
                        ? round(($stats['published_courses'] / $stats['total_courses']) * 100, 1) 
                        : 0;
                @endphp
                <div class="relative pt-1">
                    <div class="flex mb-2 items-center justify-between">
                        <div>
                            <span class="text-xs font-semibold inline-block text-gray-600">النسبة</span>
                        </div>
                        <div class="text-right">
                            <span class="text-xs font-semibold inline-block text-gray-600">{{ $publishedPercentage }}%</span>
                        </div>
                    </div>
                    <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-gray-200">
                        <div style="width:{{ $publishedPercentage }}%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-green-500"></div>
                    </div>
                </div>
                <p class="text-sm text-gray-600">{{ $stats['published_courses'] }} من {{ $stats['total_courses'] }} كورس منشور</p>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-bold mb-4">نسبة الأسئلة المعتمدة</h3>
                @php
                    $approvedPercentage = $stats['total_questions'] > 0 
                        ? round(($stats['approved_questions'] / $stats['total_questions']) * 100, 1) 
                        : 0;
                @endphp
                <div class="relative pt-1">
                    <div class="flex mb-2 items-center justify-between">
                        <div>
                            <span class="text-xs font-semibold inline-block text-gray-600">النسبة</span>
                        </div>
                        <div class="text-right">
                            <span class="text-xs font-semibold inline-block text-gray-600">{{ $approvedPercentage }}%</span>
                        </div>
                    </div>
                    <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-gray-200">
                        <div style="width:{{ $approvedPercentage }}%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-blue-500"></div>
                    </div>
                </div>
                <p class="text-sm text-gray-600">{{ $stats['approved_questions'] }} من {{ $stats['total_questions'] }} سؤال معتمد</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 mt-6">
            <h3 class="text-xl font-bold mb-4">روابط سريعة</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('instructor.courses.index') }}" class="p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                    <i class="fas fa-book text-2xl text-green-600 mb-2"></i>
                    <p class="font-semibold">كورساتي</p>
                </a>
                <a href="{{ route('instructor.questions.index') }}" class="p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                    <i class="fas fa-question-circle text-2xl text-blue-600 mb-2"></i>
                    <p class="font-semibold">أسئلتي</p>
                </a>
                <a href="{{ route('instructor.students') }}" class="p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                    <i class="fas fa-users text-2xl text-purple-600 mb-2"></i>
                    <p class="font-semibold">طلابي</p>
                </a>
            </div>
        </div>
    </div>
</body>
</html>

