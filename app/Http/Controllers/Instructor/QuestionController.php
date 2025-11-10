<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\Specialization;

class QuestionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:instructor|admin|super-admin']);
    }

    public function index()
    {
        $questions = Question::where('created_by', auth()->id())
            ->with('specialization')
            ->paginate(20);
        return view('instructor.questions.index', compact('questions'));
    }

    public function create()
    {
        $specializations = Specialization::all();
        return view('instructor.questions.create', compact('specializations'));
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
            'options.*.is_correct' => 'required|boolean',
        ]);

        $question = Question::create([
            'question_text' => $request->question_text,
            'specialization_id' => $request->specialization_id,
            'created_by' => auth()->id(),
            'difficulty_level' => $request->difficulty_level,
            'explanation' => $request->explanation,
            'time_limit' => $request->time_limit ?? 60,
            'points' => $request->points ?? 1,
            'is_approved' => false,
            'is_active' => true,
        ]);

        if ($request->has('options')) {
            foreach ($request->options as $index => $optionData) {
                QuestionOption::create([
                    'question_id' => $question->id,
                    'option_text' => $optionData['option_text'],
                    'is_correct' => $optionData['is_correct'],
                    'sort_order' => $index + 1,
                ]);
            }
        }

        return redirect()->route('instructor.questions.index')->with('success', 'تم إنشاء السؤال بنجاح');
    }

    public function show(Question $question)
    {
        $this->authorize('view', $question);
        $question->load('options', 'specialization');
        return view('instructor.questions.show', compact('question'));
    }

    public function edit(Question $question)
    {
        $this->authorize('update', $question);
        $specializations = Specialization::all();
        $question->load('options');
        return view('instructor.questions.edit', compact('question', 'specializations'));
    }

    public function update(Request $request, Question $question)
    {
        $this->authorize('update', $question);

        $request->validate([
            'question_text' => 'required|string',
            'specialization_id' => 'required|exists:specializations,id',
            'difficulty_level' => 'required|in:easy,medium,hard,expert',
            'explanation' => 'nullable|string',
            'time_limit' => 'nullable|integer|min:1',
            'points' => 'nullable|integer|min:1',
            'options' => 'required|array|min:2',
            'options.*.option_text' => 'required|string',
            'options.*.is_correct' => 'required|boolean',
        ]);

        $question->update([
            'question_text' => $request->question_text,
            'specialization_id' => $request->specialization_id,
            'difficulty_level' => $request->difficulty_level,
            'explanation' => $request->explanation,
            'time_limit' => $request->time_limit ?? $question->time_limit,
            'points' => $request->points ?? $question->points,
        ]);

        if ($request->has('options')) {
            $question->options()->delete(); // Remove existing options
            foreach ($request->options as $index => $optionData) {
                QuestionOption::create([
                    'question_id' => $question->id,
                    'option_text' => $optionData['option_text'],
                    'is_correct' => $optionData['is_correct'],
                    'sort_order' => $index + 1,
                ]);
            }
        }

        return redirect()->route('instructor.questions.index')->with('success', 'تم تحديث السؤال بنجاح');
    }

    public function destroy(Question $question)
    {
        $this->authorize('delete', $question);
        $question->delete();
        return redirect()->route('instructor.questions.index')->with('success', 'تم حذف السؤال بنجاح');
    }

    public function approve(Question $question)
    {
        $this->authorize('update', $question);
        $question->update(['is_approved' => true]);
        return redirect()->back()->with('success', 'تم الموافقة على السؤال بنجاح');
    }
}