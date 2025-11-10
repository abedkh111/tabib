<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة المحاضر - منصة طبيب التعليمية</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Alpine.js -->
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- Google Fonts for Arabic -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <style>
        body { 
            font-family: 'Cairo', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
        }
        .rtl { direction: rtl; }
    </style>
</head>
<body class="bg-gray-50 font-cairo">
    <!-- Navigation -->
    <nav class="bg-green-600 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-4 space-x-reverse">
                    <i class="fas fa-chalkboard-teacher text-2xl"></i>
                    <h1 class="text-xl font-bold">لوحة المحاضر</h1>
                </div>
                <div class="flex items-center space-x-4 space-x-reverse">
                    <a href="{{ route('dashboard') }}" class="bg-green-700 hover:bg-green-800 px-3 py-2 rounded">
                        <i class="fas fa-home ml-2"></i>
                        الرئيسية
                    </a>
                    <span class="hidden md:inline">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="bg-green-700 hover:bg-green-800 px-3 py-2 rounded">
                            <i class="fas fa-sign-out-alt ml-2"></i>
                            خروج
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto py-8 px-4">
        <!-- Welcome Section -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <div class="flex items-center mb-4">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center ml-4">
                    <i class="fas fa-chalkboard-teacher text-2xl text-green-600"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">
                        مرحباً بك، {{ auth()->user()->name }}!
                    </h2>
                    <p class="text-gray-600">
                        يمكنك من هنا إدارة كورساتك وأسئلتك ومتابعة تقدم طلابك.
                    </p>
                </div>
            </div>
        </div>

        <!-- Instructor Actions -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <a href="{{ route('instructor.courses.index') }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow group">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600 ml-4 group-hover:scale-110 transition-transform">
                        <i class="fas fa-book text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">كورساتي</h3>
                        <p class="text-sm text-gray-600">إدارة وإنشاء الكورسات التعليمية</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('instructor.questions.index') }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow group">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 text-purple-600 ml-4 group-hover:scale-110 transition-transform">
                        <i class="fas fa-question-circle text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">بنك الأسئلة</h3>
                        <p class="text-sm text-gray-600">إنشاء وإدارة الأسئلة والاختبارات</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('instructor.analytics') }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow group">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-orange-100 text-orange-600 ml-4 group-hover:scale-110 transition-transform">
                        <i class="fas fa-chart-line text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">التحليلات</h3>
                        <p class="text-sm text-gray-600">متابعة أداء الطلاب والإحصائيات</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('questions.index') }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow group">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-indigo-100 text-indigo-600 ml-4 group-hover:scale-110 transition-transform">
                        <i class="fas fa-book-open text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">تصفح الأسئلة</h3>
                        <p class="text-sm text-gray-600">استعراض بنك الأسئلة العام</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('profile') }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow group">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-gray-100 text-gray-600 ml-4 group-hover:scale-110 transition-transform">
                        <i class="fas fa-user-edit text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">الملف الشخصي</h3>
                        <p class="text-sm text-gray-600">تحديث معلوماتك الشخصية</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('dashboard') }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow group">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600 ml-4 group-hover:scale-110 transition-transform">
                        <i class="fas fa-home text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">العودة للرئيسية</h3>
                        <p class="text-sm text-gray-600">الرجوع للوحة التحكم الرئيسية</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Coming Soon Notice -->
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 text-center">
            <i class="fas fa-construction text-yellow-500 text-4xl mb-4"></i>
            <h3 class="text-xl font-bold text-yellow-800 mb-2">قيد التطوير</h3>
            <p class="text-yellow-700">
                هذه الصفحات قيد التطوير حالياً. سيتم إضافة المزيد من الميزات قريباً.
            </p>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-12">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p>&copy; 2024 منصة طبيب التعليمية - لوحة المحاضر. جميع الحقوق محفوظة.</p>
        </div>
    </footer>
</body>
</html>
