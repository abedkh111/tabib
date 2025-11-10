<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Quiz;
use App\Models\Course;
use App\Models\Question;

class QuizController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:instructor|admin|super-admin']);
    }

    public function index()
    {
        $quizzes = Quiz::where('created_by', auth()->id())
            ->with('course')
            ->paginate(20);
        return view('instructor.quizzes.index', compact('quizzes'));
    }

    public function create()
    {
        $courses = Course::where('instructor_id', auth()->id())->get();
        return view('instructor.quizzes.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'course_id' => 'required|exists:courses,id',
            'description' => 'nullable|string',
            'duration_minutes' => 'nullable|integer|min:1',
            'passing_score' => 'nullable|integer|min:0|max:100',
            'max_attempts' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
        ]);

        // Verify the course belongs to the instructor
        $course = Course::findOrFail($request->course_id);
        if ($course->instructor_id !== auth()->id()) {
            abort(403, 'غير مصرح لك بإنشاء اختبار لهذا الكورس');
        }

        Quiz::create([
            'title' => $request->title,
            'course_id' => $request->course_id,
            'created_by' => auth()->id(),
            'description' => $request->description,
            'time_limit' => $request->duration_minutes,
            'passing_score' => $request->passing_score ?? 60,
            'max_attempts' => $request->max_attempts ?? 3,
            'is_active' => $request->is_active ?? true,
            'specialization_id' => $course->specialization_id,
        ]);

        return redirect()->route('instructor.quizzes.index')->with('success', 'تم إنشاء الاختبار بنجاح');
    }

    public function show(Quiz $quiz)
    {
        $this->authorize('view', $quiz);
        $quiz->load(['course', 'questions', 'attempts']);
        return view('instructor.quizzes.show', compact('quiz'));
    }

    public function edit(Quiz $quiz)
    {
        $this->authorize('update', $quiz);
        $courses = Course::where('instructor_id', auth()->id())->get();
        return view('instructor.quizzes.edit', compact('quiz', 'courses'));
    }

    public function update(Request $request, Quiz $quiz)
    {
        $this->authorize('update', $quiz);

        $request->validate([
            'title' => 'required|string|max:255',
            'course_id' => 'required|exists:courses,id',
            'description' => 'nullable|string',
            'duration_minutes' => 'nullable|integer|min:1',
            'passing_score' => 'nullable|integer|min:0|max:100',
            'max_attempts' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
        ]);

        // Verify the course belongs to the instructor
        $course = Course::findOrFail($request->course_id);
        if ($course->instructor_id !== auth()->id()) {
            abort(403, 'غير مصرح لك بتحديث الاختبار لهذا الكورس');
        }

        $updateData = [
            'title' => $request->title,
            'course_id' => $request->course_id,
            'description' => $request->description,
            'time_limit' => $request->duration_minutes,
            'passing_score' => $request->passing_score,
            'max_attempts' => $request->max_attempts,
            'is_active' => $request->is_active,
        ];
        
        if ($request->has('course_id') && $course->specialization_id) {
            $updateData['specialization_id'] = $course->specialization_id;
        }
        
        $quiz->update($updateData);

        return redirect()->route('instructor.quizzes.index')->with('success', 'تم تحديث الاختبار بنجاح');
    }

    public function destroy(Quiz $quiz)
    {
        $this->authorize('delete', $quiz);
        $quiz->delete();
        return redirect()->route('instructor.quizzes.index')->with('success', 'تم حذف الاختبار بنجاح');
    }

    public function statistics(Quiz $quiz)
    {
        $this->authorize('view', $quiz);

        $stats = [
            'total_attempts' => $quiz->attempts()->count(),
            'passed_attempts' => $quiz->attempts()->where('passed', true)->count(),
            'failed_attempts' => $quiz->attempts()->where('passed', false)->count(),
            'average_score' => $quiz->attempts()->avg('score'),
            'highest_score' => $quiz->attempts()->max('score'),
            'lowest_score' => $quiz->attempts()->min('score'),
        ];

        $scoreDistribution = $quiz->attempts()
            ->selectRaw('FLOOR(score / 10) * 10 as score_range, COUNT(*) as count')
            ->groupBy('score_range')
            ->orderBy('score_range')
            ->pluck('count', 'score_range')
            ->toArray();

        return view('instructor.quizzes.statistics', compact('quiz', 'stats', 'scoreDistribution'));
    }
}