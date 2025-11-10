<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تقارير المستخدمين - منصة طبيب التعليمية</title>
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
                    <i class="fas fa-users text-2xl"></i>
                    <h1 class="text-xl font-bold">تقارير المستخدمين</h1>
                </div>
                <div class="flex items-center space-x-4 space-x-reverse">
                    <a href="{{ route('admin.reports') }}" class="bg-red-700 hover:bg-red-800 px-3 py-2 rounded">العودة</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto py-8 px-4">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-bold mb-6">إحصائيات المستخدمين</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="p-4 bg-blue-50 rounded-lg">
                    <p class="text-gray-600">إجمالي المستخدمين</p>
                    <p class="text-3xl font-bold">{{ $userStats['total_users'] }}</p>
                </div>
                <div class="p-4 bg-green-50 rounded-lg">
                    <p class="text-gray-600">المستخدمون النشطون</p>
                    <p class="text-3xl font-bold">{{ $userStats['active_users'] }}</p>
                </div>
                <div class="p-4 bg-red-50 rounded-lg">
                    <p class="text-gray-600">المستخدمون غير النشطين</p>
                    <p class="text-3xl font-bold">{{ $userStats['inactive_users'] }}</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

