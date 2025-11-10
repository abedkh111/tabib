<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل السؤال - منصة طبيب التعليمية</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>body { font-family: 'Cairo', sans-serif; }</style>
    <script>
        let optionIndex = {{ $question->options->count() }};
        function addOption() {
            const container = document.getElementById('options-container');
            const div = document.createElement('div');
            div.className = 'option-item flex items-center space-x-4 space-x-reverse p-3 bg-gray-50 rounded-lg';
            div.innerHTML = `
                <input type="text" name="options[${optionIndex}][option_text]" placeholder="نص الخيار" required
                       class="flex-1 px-4 py-2 border border-gray-300 rounded-lg">
                <label class="flex items-center">
                    <input type="checkbox" name="options[${optionIndex}][is_correct]" value="1"
                           class="ml-2 w-5 h-5 text-green-600 border-gray-300 rounded">
                    <span class="text-sm text-gray-700">صحيح</span>
                </label>
                <button type="button" onclick="removeOption(this)" class="text-red-600 hover:text-red-800">
                    <i class="fas fa-trash"></i>
                </button>
            `;
            container.appendChild(div);
            optionIndex++;
        }
        function removeOption(btn) {
            const container = document.getElementById('options-container');
            if (container.children.length > 1) {
                btn.closest('.option-item').remove();
            } else {
                alert('يجب أن يكون هناك خيار واحد على الأقل');
            }
        }
    </script>
</head>
<body class="bg-gray-50">
    <nav class="bg-green-600 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-4 space-x-reverse">
                    <i class="fas fa-edit text-2xl"></i>
                    <h1 class="text-xl font-bold">تعديل السؤال</h1>
                </div>
                <div>
                    <a href="{{ route('instructor.questions.index') }}" class="bg-green-700 hover:bg-green-800 px-3 py-2 rounded">العودة</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-4xl mx-auto py-8 px-4">
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
            <form method="POST" action="{{ route('instructor.questions.update', $question) }}">
                @csrf
                @method('PUT')
                <div class="space-y-6">
                    <div>
                        <label for="question_text" class="block text-sm font-medium text-gray-700 mb-2">نص السؤال</label>
                        <textarea id="question_text" name="question_text" rows="4" required
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg">{{ old('question_text', $question->question_text) }}</textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="specialization_id" class="block text-sm font-medium text-gray-700 mb-2">التخصص</label>
                            <select id="specialization_id" name="specialization_id" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                                <option value="">اختر التخصص</option>
                                @foreach($specializations as $spec)
                                    <option value="{{ $spec->id }}" 
                                            {{ ($question->specialization_id == $spec->id || old('specialization_id') == $spec->id) ? 'selected' : '' }}>
                                        {{ $spec->name_ar }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="difficulty_level" class="block text-sm font-medium text-gray-700 mb-2">مستوى الصعوبة</label>
                            <select id="difficulty_level" name="difficulty_level" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                                <option value="easy" {{ ($question->difficulty_level == 'easy' || old('difficulty_level') == 'easy') ? 'selected' : '' }}>سهل</option>
                                <option value="medium" {{ ($question->difficulty_level == 'medium' || old('difficulty_level') == 'medium') ? 'selected' : '' }}>متوسط</option>
                                <option value="hard" {{ ($question->difficulty_level == 'hard' || old('difficulty_level') == 'hard') ? 'selected' : '' }}>صعب</option>
                                <option value="expert" {{ ($question->difficulty_level == 'expert' || old('difficulty_level') == 'expert') ? 'selected' : '' }}>خبير</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="time_limit" class="block text-sm font-medium text-gray-700 mb-2">الوقت المحدد (ثانية)</label>
                            <input type="number" id="time_limit" name="time_limit" 
                                   value="{{ old('time_limit', $question->time_limit) }}" min="1"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        </div>
                        <div>
                            <label for="points" class="block text-sm font-medium text-gray-700 mb-2">النقاط</label>
                            <input type="number" id="points" name="points" 
                                   value="{{ old('points', $question->points) }}" min="1"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        </div>
                    </div>

                    <div>
                        <label for="explanation" class="block text-sm font-medium text-gray-700 mb-2">الشرح (اختياري)</label>
                        <textarea id="explanation" name="explanation" rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg">{{ old('explanation', $question->explanation) }}</textarea>
                    </div>

                    <div>
                        <div class="flex justify-between items-center mb-4">
                            <label class="block text-sm font-medium text-gray-700">خيارات الإجابة</label>
                            <button type="button" onclick="addOption()" class="text-sm text-green-600 hover:text-green-800">
                                <i class="fas fa-plus ml-1"></i>إضافة خيار
                            </button>
                        </div>
                        <div id="options-container" class="space-y-3">
                            @foreach($question->options as $index => $option)
                            <div class="option-item flex items-center space-x-4 space-x-reverse p-3 bg-gray-50 rounded-lg">
                                <input type="text" name="options[{{ $index }}][option_text]" 
                                       value="{{ old("options.$index.option_text", $option->option_text) }}" 
                                       placeholder="نص الخيار" required
                                       class="flex-1 px-4 py-2 border border-gray-300 rounded-lg">
                                <label class="flex items-center">
                                    <input type="checkbox" name="options[{{ $index }}][is_correct]" value="1"
                                           {{ old("options.$index.is_correct", $option->is_correct) ? 'checked' : '' }}
                                           class="ml-2 w-5 h-5 text-green-600 border-gray-300 rounded">
                                    <span class="text-sm text-gray-700">صحيح</span>
                                </label>
                                <button type="button" onclick="removeOption(this)" class="text-red-600 hover:text-red-800">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex justify-end space-x-4 space-x-reverse">
                        <a href="{{ route('instructor.questions.index') }}" class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg">إلغاء</a>
                        <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                            <i class="fas fa-save ml-2"></i>حفظ التغييرات
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

