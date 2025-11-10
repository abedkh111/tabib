<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $user->name }} - منصة طبيب التعليمية</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- Google Fonts for Arabic -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Cairo', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
    </style>
</head>
<body class="bg-gray-50 font-cairo">
    <nav class="bg-red-600 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-4 space-x-reverse">
                    <i class="fas fa-user text-2xl"></i>
                    <h1 class="text-xl font-bold">تفاصيل المستخدم</h1>
                </div>
                <div class="flex items-center space-x-4 space-x-reverse">
                    <a href="{{ route('admin.users.index') }}" class="bg-red-700 hover:bg-red-800 px-3 py-2 rounded">
                        <i class="fas fa-arrow-right ml-2"></i>
                        العودة
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto py-8 px-4">
        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            {{ session('error') }}
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <div class="flex items-center mb-6">
                        <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-24 h-24 rounded-full ml-4">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">{{ $user->name }}</h2>
                            <p class="text-gray-600">{{ $user->email }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="text-sm text-gray-500">البريد الإلكتروني</span>
                            <p class="text-gray-900">{{ $user->email }}</p>
                        </div>
                        @if($user->phone)
                        <div>
                            <span class="text-sm text-gray-500">رقم الهاتف</span>
                            <p class="text-gray-900">{{ $user->phone }}</p>
                        </div>
                        @endif
                        @if($user->specialization)
                        <div>
                            <span class="text-sm text-gray-500">التخصص</span>
                            <p class="text-gray-900">{{ $user->specialization->name_ar }}</p>
                        </div>
                        @endif
                        <div>
                            <span class="text-sm text-gray-500">تاريخ التسجيل</span>
                            <p class="text-gray-900">{{ $user->created_at->format('Y/m/d') }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-bold mb-4">الأدوار</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($user->roles as $role)
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                                {{ $role->name }}
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>

            <div>
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h3 class="text-lg font-bold mb-4">الحالة</h3>
                    @if($user->is_active)
                        <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-green-100 text-green-800">
                            نشط
                        </span>
                    @else
                        <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-red-100 text-red-800">
                            غير نشط
                        </span>
                    @endif
                </div>

                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-bold mb-4">الإجراءات</h3>
                    <div class="space-y-2">
                        <a href="{{ route('admin.users.edit', $user) }}" 
                           class="block w-full text-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            <i class="fas fa-edit ml-2"></i>
                            تعديل
                        </a>
                        @if($user->is_active)
                            <form method="POST" action="{{ route('admin.users.deactivate', $user) }}">
                                @csrf
                                <button type="submit" class="w-full px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700">
                                    <i class="fas fa-ban ml-2"></i>
                                    إلغاء التفعيل
                                </button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('admin.users.activate', $user) }}">
                                @csrf
                                <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                                    <i class="fas fa-check ml-2"></i>
                                    تفعيل
                                </button>
                            </form>
                        @endif
                        @if(!$user->hasRole('super-admin'))
                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('هل أنت متأكد؟')" 
                                        class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                                    <i class="fas fa-trash ml-2"></i>
                                    حذف
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>


