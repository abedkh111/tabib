<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة كورس جديد - منصة طبيب التعليمية</title>
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
                    <i class="fas fa-plus text-2xl"></i>
                    <h1 class="text-xl font-bold">إضافة كورس جديد</h1>
                </div>
                <div>
                    <a href="{{ route('instructor.courses.index') }}" class="bg-green-700 hover:bg-green-800 px-3 py-2 rounded">العودة</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-3xl mx-auto py-8 px-4">
        @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="bg-white rounded-lg shadow-md p-6">
            <form method="POST" action="{{ route('instructor.courses.store') }}">
                @csrf
                <div class="space-y-6">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">عنوان الكورس</label>
                        <input type="text" id="title" name="title" value="{{ old('title') }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">الوصف</label>
                        <textarea id="description" name="description" rows="6" required
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg">{{ old('description') }}</textarea>
                    </div>
                    <div>
                        <label for="specialization_id" class="block text-sm font-medium text-gray-700 mb-2">التخصص</label>
                        <select id="specialization_id" name="specialization_id" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                            <option value="">اختر التخصص</option>
                            @foreach($specializations as $spec)
                                <option value="{{ $spec->id }}" {{ old('specialization_id') == $spec->id ? 'selected' : '' }}>
                                    {{ $spec->name_ar }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="duration_hours" class="block text-sm font-medium text-gray-700 mb-2">المدة (ساعات)</label>
                            <input type="number" id="duration_hours" name="duration_hours" value="{{ old('duration_hours') }}"
                                   min="1" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        </div>
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700 mb-2">السعر (ريال)</label>
                            <input type="number" id="price" name="price" value="{{ old('price') }}" step="0.01" min="0"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        </div>
                    </div>
                    <div class="flex justify-end space-x-4 space-x-reverse">
                        <a href="{{ route('instructor.courses.index') }}" class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg">إلغاء</a>
                        <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                            <i class="fas fa-save ml-2"></i>حفظ
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

