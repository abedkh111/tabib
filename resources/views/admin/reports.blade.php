<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>التقارير - منصة طبيب التعليمية</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>body { font-family: 'Cairo', sans-serif; }</style>
</head>
<body class="bg-gray-50">
    <nav class="bg-red-600 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-4 space-x-reverse">
                    <i class="fas fa-file-alt text-2xl"></i>
                    <h1 class="text-xl font-bold">التقارير</h1>
                </div>
                <div class="flex items-center space-x-4 space-x-reverse">
                    <a href="{{ route('admin.dashboard') }}" class="bg-red-700 hover:bg-red-800 px-3 py-2 rounded">العودة</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto py-8 px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <a href="{{ route('admin.reports.users') }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                <i class="fas fa-users text-4xl text-blue-600 mb-4"></i>
                <h3 class="text-xl font-bold">تقارير المستخدمين</h3>
                <p class="text-gray-600 mt-2">إحصائيات المستخدمين والتسجيلات</p>
            </a>
            <a href="{{ route('admin.reports.courses') }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                <i class="fas fa-book text-4xl text-green-600 mb-4"></i>
                <h3 class="text-xl font-bold">تقارير الكورسات</h3>
                <p class="text-gray-600 mt-2">إحصائيات الكورسات والاشتراكات</p>
            </a>
            <a href="{{ route('admin.reports.quizzes') }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                <i class="fas fa-clipboard-list text-4xl text-purple-600 mb-4"></i>
                <h3 class="text-xl font-bold">تقارير الاختبارات</h3>
                <p class="text-gray-600 mt-2">إحصائيات الاختبارات والأداء</p>
            </a>
        </div>
    </div>
</body>
</html>

