<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\Specialization;

class QuestionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin|super-admin']);
    }

    public function index()
    {
        $questions = Question::with(['specialization', 'user'])->paginate(20);
        return view('admin.questions.index', compact('questions'));
    }

    public function create()
    {
        $specializations = Specialization::all();
        return view('admin.questions.create', compact('specializations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'question_text' => 'required|string',
            'specialization_id' => 'required|exists:specializations,id',
            'difficulty_level' => 'required|in:easy,medium,hard,expert',
            'explanation' => 'nullable|string',
            'time_limit' => 'nullable|integer|min:1',
            'points' => 'nullable|integer|min:1',
            'options' => 'required|array|min:2',
            'options.*.option_text' => 'required|string',
            'options.*.is_correct' => 'nullable|boolean',
        ]);

        // Validate that at least one option is correct
        $hasCorrectOption = false;
        if ($request->has('options')) {
            foreach ($request->options as $optionData) {
                if (isset($optionData['is_correct']) && $optionData['is_correct']) {
                    $hasCorrectOption = true;
                    break;
                }
            }
        }

        if (!$hasCorrectOption) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['options' => 'يجب تحديد خيار صحيح واحد على الأقل']);
        }

        $question = Question::create([
            'question_text' => $request->question_text,
            'specialization_id' => $request->specialization_id,
            'created_by' => auth()->id(),
            'difficulty_level' => $request->difficulty_level,
            'explanation' => $request->explanation,
            'time_limit' => $request->time_limit ?? 60,
            'points' => $request->points ?? 1,
            'is_approved' => true, // Admin questions are auto-approved
            'is_active' => true,
        ]);

        if ($request->has('options')) {
            foreach ($request->options as $index => $optionData) {
                QuestionOption::create([
                    'question_id' => $question->id,
                    'option_text' => $optionData['option_text'],
                    'is_correct' => isset($optionData['is_correct']) && $optionData['is_correct'] ? true : false,
                    'sort_order' => $index + 1,
                ]);
            }
        }

        return redirect()->route('admin.questions.index')->with('success', 'تم إنشاء السؤال بنجاح');
    }

    public function show(Question $question)
    {
        $question->load(['specialization', 'user', 'options']);
        return view('admin.questions.show', compact('question'));
    }

    public function edit(Question $question)
    {
        $specializations = Specialization::all();
        $question->load('options');
        return view('admin.questions.edit', compact('question', 'specializations'));
    }

    public function update(Request $request, Question $question)
    {
        $request->validate([
            'question_text' => 'required|string',
            'specialization_id' => 'required|exists:specializations,id',
            'difficulty_level' => 'required|in:easy,medium,hard,expert',
            'explanation' => 'nullable|string',
            'time_limit' => 'nullable|integer|min:1',
            'points' => 'nullable|integer|min:1',
            'options' => 'required|array|min:2',
            'options.*.option_text' => 'required|string',
            'options.*.is_correct' => 'nullable|boolean',
        ]);

        // Validate that at least one option is correct
        $hasCorrectOption = false;
        if ($request->has('options')) {
            foreach ($request->options as $optionData) {
                if (isset($optionData['is_correct']) && $optionData['is_correct']) {
                    $hasCorrectOption = true;
                    break;
                }
            }
        }

        if (!$hasCorrectOption) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['options' => 'يجب تحديد خيار صحيح واحد على الأقل']);
        }

        $question->update([
            'question_text' => $request->question_text,
            'specialization_id' => $request->specialization_id,
            'difficulty_level' => $request->difficulty_level,
            'explanation' => $request->explanation,
            'time_limit' => $request->time_limit ?? $question->time_limit,
            'points' => $request->points ?? $question->points,
            'is_approved' => $request->has('is_approved') ? (bool)$request->is_approved : $question->is_approved,
            'is_active' => $request->has('is_active') ? (bool)$request->is_active : $question->is_active,
        ]);

        if ($request->has('options')) {
            $question->options()->delete(); // Remove existing options
            foreach ($request->options as $index => $optionData) {
                QuestionOption::create([
                    'question_id' => $question->id,
                    'option_text' => $optionData['option_text'],
                    'is_correct' => isset($optionData['is_correct']) && $optionData['is_correct'] ? true : false,
                    'sort_order' => $index + 1,
                ]);
            }
        }

        return redirect()->route('admin.questions.index')->with('success', 'تم تحديث السؤال بنجاح');
    }

    public function approve(Question $question)
    {
        $question->update(['is_approved' => true]);
        return redirect()->route('admin.questions.index')->with('success', 'تم الموافقة على السؤال بنجاح');
    }

    public function reject(Question $question)
    {
        $question->update(['is_approved' => false]);
        return redirect()->route('admin.questions.index')->with('success', 'تم رفض السؤال بنجاح');
    }

    public function bulkApprove(Request $request)
    {
        $questionIds = $request->input('question_ids', []);
        
        if (empty($questionIds)) {
            return redirect()->route('admin.questions.index')->with('error', 'لم يتم تحديد أي أسئلة');
        }
        
        Question::whereIn('id', $questionIds)->update(['is_approved' => true]);
        return redirect()->route('admin.questions.index')->with('success', 'تم الموافقة على ' . count($questionIds) . ' سؤال بنجاح');
    }

    public function destroy(Question $question)
    {
        $question->delete();
        return redirect()->route('admin.questions.index')->with('success', 'تم حذف السؤال بنجاح');
    }
}