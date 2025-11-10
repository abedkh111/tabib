<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الملف الشخصي - منصة طبيب التعليمية</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>body { font-family: 'Cairo', sans-serif; }</style>
</head>
<body class="bg-gray-50">
    <nav class="bg-blue-600 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-4 space-x-reverse">
                    <i class="fas fa-user-circle text-2xl"></i>
                    <h1 class="text-xl font-bold">الملف الشخصي</h1>
                </div>
                <div class="flex items-center space-x-4 space-x-reverse">
                    <a href="{{ route('dashboard') }}" class="bg-blue-700 hover:bg-blue-800 px-3 py-2 rounded">العودة</a>
                    <span>{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="bg-blue-700 hover:bg-blue-800 px-3 py-2 rounded">
                            <i class="fas fa-sign-out-alt ml-2"></i>خروج
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto py-8 px-4">
        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">{{ session('success') }}</div>
        @endif

        @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">{{ session('error') }}</div>
        @endif

        @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Profile Picture and Basic Info -->
                    <div class="md:col-span-1">
                        <div class="bg-gray-50 rounded-lg p-6 text-center">
                            <div class="mb-4">
                                @if($user->avatar)
                                    <img src="{{ $user->avatar_url }}" alt="Profile Picture" class="w-24 h-24 rounded-full mx-auto object-cover">
                                @else
                                    <div class="w-24 h-24 bg-blue-500 rounded-full mx-auto flex items-center justify-center">
                                        <span class="text-white text-2xl font-bold">{{ substr($user->name, 0, 1) }}</span>
                                    </div>
                                @endif
                            </div>
                            <h2 class="text-xl font-semibold">{{ $user->name }}</h2>
                            <p class="text-gray-600">{{ $user->email }}</p>
                            <div class="mt-4">
                                @if($user->hasRole('admin'))
                                    <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-sm">مدير</span>
                                @elseif($user->hasRole('instructor'))
                                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-sm">محاضر</span>
                                @elseif($user->hasRole('super-admin'))
                                    <span class="bg-purple-100 text-purple-800 px-2 py-1 rounded text-sm">مدير عام</span>
                                @else
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-sm">طالب</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Profile Details -->
                    <div class="md:col-span-2">
                        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="space-y-6">
                                <!-- Personal Information -->
                                <div class="bg-gray-50 rounded-lg p-6">
                                    <h3 class="text-lg font-semibold mb-4">المعلومات الشخصية</h3>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">الاسم</label>
                                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            @error('name')
                                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">البريد الإلكتروني</label>
                                            <input type="email" value="{{ $user->email }}" readonly
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">رقم الهاتف</label>
                                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            @error('phone')
                                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">الجامعة</label>
                                            <input type="text" name="university" value="{{ old('university', $user->university) }}"
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            @error('university')
                                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">سنة التخرج</label>
                                            <input type="number" name="graduation_year" value="{{ old('graduation_year', $user->graduation_year) }}"
                                                   min="1950" max="{{ date('Y') + 10 }}"
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            @error('graduation_year')
                                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">الصورة الشخصية</label>
                                            <input type="file" name="avatar" accept="image/*"
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            @error('avatar')
                                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="mt-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">نبذة عني</label>
                                        <textarea name="bio" rows="4"
                                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('bio', $user->bio) }}</textarea>
                                        @error('bio')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Account Information -->
                                <div class="bg-gray-50 rounded-lg p-6">
                                    <h3 class="text-lg font-semibold mb-4">معلومات الحساب</h3>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">تاريخ التسجيل</label>
                                            <p class="text-gray-600">{{ $user->created_at->format('Y-m-d') }}</p>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">آخر دخول</label>
                                            <p class="text-gray-600">{{ $user->last_login_at ? $user->last_login_at->format('Y-m-d H:i') : 'لم يتم تسجيل الدخول بعد' }}</p>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">حالة الحساب</label>
                                            <span class="px-2 py-1 rounded text-sm {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $user->is_active ? 'نشط' : 'غير نشط' }}
                                            </span>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">نوع الاشتراك</label>
                                            <p class="text-gray-600">{{ $user->subscription_type ?? 'مجاني' }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="flex justify-end">
                                    <button type="submit" class="bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600 transition-colors">
                                        <i class="fas fa-save ml-2"></i>حفظ التغييرات
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
