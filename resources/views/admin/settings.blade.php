<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إعدادات النظام - منصة طبيب التعليمية</title>
    
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
    <nav class="bg-red-600 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-4 space-x-reverse">
                    <i class="fas fa-cog text-2xl"></i>
                    <h1 class="text-xl font-bold">إعدادات النظام</h1>
                </div>
                <div class="flex items-center space-x-4 space-x-reverse">
                    <a href="{{ route('admin.dashboard') }}" class="bg-red-700 hover:bg-red-800 px-3 py-2 rounded">
                        <i class="fas fa-arrow-right ml-2"></i>
                        العودة للوحة الإدارة
                    </a>
                    <a href="{{ route('dashboard') }}" class="bg-red-700 hover:bg-red-800 px-3 py-2 rounded">
                        <i class="fas fa-home ml-2"></i>
                        الرئيسية
                    </a>
                    <span class="hidden md:inline">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="bg-red-700 hover:bg-red-800 px-3 py-2 rounded">
                            <i class="fas fa-sign-out-alt ml-2"></i>
                            خروج
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto py-8 px-4">
        <!-- Success Message -->
        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6" role="alert">
            <div class="flex items-center">
                <i class="fas fa-check-circle ml-2"></i>
                <span>{{ session('success') }}</span>
            </div>
        </div>
        @endif

        <!-- Error Messages -->
        @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6" role="alert">
            <div class="flex items-center mb-2">
                <i class="fas fa-exclamation-circle ml-2"></i>
                <span class="font-bold">حدثت الأخطاء التالية:</span>
            </div>
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Settings Form -->
        <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
            @csrf

            <!-- General Settings -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center ml-4">
                        <i class="fas fa-cog text-xl text-blue-600"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800">الإعدادات العامة</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="site_name" class="block text-sm font-medium text-gray-700 mb-2">
                            اسم الموقع
                        </label>
                        <input type="text" 
                               id="site_name" 
                               name="site_name" 
                               value="{{ old('site_name', 'منصة طبيب التعليمية') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    </div>

                    <div>
                        <label for="site_email" class="block text-sm font-medium text-gray-700 mb-2">
                            البريد الإلكتروني للموقع
                        </label>
                        <input type="email" 
                               id="site_email" 
                               name="site_email" 
                               value="{{ old('site_email', 'info@tabib.com') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    </div>

                    <div>
                        <label for="site_phone" class="block text-sm font-medium text-gray-700 mb-2">
                            رقم الهاتف
                        </label>
                        <input type="text" 
                               id="site_phone" 
                               name="site_phone" 
                               value="{{ old('site_phone', '+966501234567') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    </div>

                    <div>
                        <label for="timezone" class="block text-sm font-medium text-gray-700 mb-2">
                            المنطقة الزمنية
                        </label>
                        <select id="timezone" 
                                name="timezone" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                            <option value="Asia/Riyadh" selected>الرياض (GMT+3)</option>
                            <option value="UTC">UTC</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Course Settings -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center ml-4">
                        <i class="fas fa-book text-xl text-green-600"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800">إعدادات الكورسات</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="auto_approve_courses" 
                                   value="1"
                                   {{ old('auto_approve_courses') ? 'checked' : '' }}
                                   class="ml-2 w-5 h-5 text-red-600 border-gray-300 rounded focus:ring-red-500">
                            <span class="text-sm font-medium text-gray-700">الموافقة التلقائية على الكورسات</span>
                        </label>
                        <p class="text-xs text-gray-500 mt-1">سيتم الموافقة على الكورسات الجديدة تلقائياً</p>
                    </div>

                    <div>
                        <label for="max_courses_per_instructor" class="block text-sm font-medium text-gray-700 mb-2">
                            الحد الأقصى للكورسات لكل محاضر
                        </label>
                        <input type="number" 
                               id="max_courses_per_instructor" 
                               name="max_courses_per_instructor" 
                               value="{{ old('max_courses_per_instructor', '10') }}"
                               min="1"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    </div>
                </div>
            </div>

            <!-- Question Settings -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center ml-4">
                        <i class="fas fa-question-circle text-xl text-purple-600"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800">إعدادات الأسئلة</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="auto_approve_questions" 
                                   value="1"
                                   {{ old('auto_approve_questions') ? 'checked' : '' }}
                                   class="ml-2 w-5 h-5 text-red-600 border-gray-300 rounded focus:ring-red-500">
                            <span class="text-sm font-medium text-gray-700">الموافقة التلقائية على الأسئلة</span>
                        </label>
                        <p class="text-xs text-gray-500 mt-1">سيتم الموافقة على الأسئلة الجديدة تلقائياً</p>
                    </div>

                    <div>
                        <label for="questions_per_page" class="block text-sm font-medium text-gray-700 mb-2">
                            عدد الأسئلة لكل صفحة
                        </label>
                        <input type="number" 
                               id="questions_per_page" 
                               name="questions_per_page" 
                               value="{{ old('questions_per_page', '20') }}"
                               min="10"
                               max="100"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    </div>
                </div>
            </div>

            <!-- Subscription Settings -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center ml-4">
                        <i class="fas fa-credit-card text-xl text-orange-600"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800">إعدادات الاشتراكات</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="free_subscription_courses_limit" class="block text-sm font-medium text-gray-700 mb-2">
                            حد الكورسات للاشتراك المجاني
                        </label>
                        <input type="number" 
                               id="free_subscription_courses_limit" 
                               name="free_subscription_courses_limit" 
                               value="{{ old('free_subscription_courses_limit', '3') }}"
                               min="0"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    </div>

                    <div>
                        <label for="basic_subscription_courses_limit" class="block text-sm font-medium text-gray-700 mb-2">
                            حد الكورسات للاشتراك الأساسي
                        </label>
                        <input type="number" 
                               id="basic_subscription_courses_limit" 
                               name="basic_subscription_courses_limit" 
                               value="{{ old('basic_subscription_courses_limit', '10') }}"
                               min="0"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    </div>
                </div>
            </div>

            <!-- Notification Settings -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center ml-4">
                        <i class="fas fa-bell text-xl text-indigo-600"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800">إعدادات الإشعارات</h2>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="email_notifications" 
                                   value="1"
                                   {{ old('email_notifications', true) ? 'checked' : '' }}
                                   class="ml-2 w-5 h-5 text-red-600 border-gray-300 rounded focus:ring-red-500">
                            <span class="text-sm font-medium text-gray-700">تفعيل الإشعارات عبر البريد الإلكتروني</span>
                        </label>
                    </div>

                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="sms_notifications" 
                                   value="1"
                                   {{ old('sms_notifications') ? 'checked' : '' }}
                                   class="ml-2 w-5 h-5 text-red-600 border-gray-300 rounded focus:ring-red-500">
                            <span class="text-sm font-medium text-gray-700">تفعيل الإشعارات عبر الرسائل النصية</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-4 space-x-reverse">
                <a href="{{ route('admin.dashboard') }}" 
                   class="px-6 py-3 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors">
                    <i class="fas fa-times ml-2"></i>
                    إلغاء
                </a>
                <button type="submit" 
                        class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                    <i class="fas fa-save ml-2"></i>
                    حفظ الإعدادات
                </button>
            </div>
        </form>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-12">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p>&copy; 2024 منصة طبيب التعليمية - لوحة الإدارة. جميع الحقوق محفوظة.</p>
        </div>
    </footer>
</body>
</html>

