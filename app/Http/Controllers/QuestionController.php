<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\QuizAttempt;
use App\Models\QuizAttemptAnswer;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class QuestionController extends Controller
{
    /**
     * Display a listing of questions.
     */
    public function index(Request $request): View
    {
        $query = Question::with(['specialization', 'creator', 'options'])
            ->where('is_active', true);

        // Show only approved questions by default, but allow showing all if requested
        if (!$request->has('show_all') || $request->show_all != '1') {
            $query->where('is_approved', true);
        }

        // Filter by specialization if provided
        if ($request->has('specialization') && $request->specialization) {
            $query->where('specialization_id', $request->specialization);
        }

        // Search by question text
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('question_text', 'like', "%{$search}%")
                  ->orWhere('explanation', 'like', "%{$search}%");
            });
        }

        $questions = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

        return view('questions.index', compact('questions'));
    }

    /**
     * Show the practice page for questions.
     */
    public function practice(Request $request): View
    {
        $query = Question::where('status', 'approved')
                        ->with(['specialization', 'options']);

        // Filter by specialization if provided
        if ($request->has('specialization') && $request->specialization) {
            $query->where('specialization_id', $request->specialization);
        }

        // Get random questions for practice
        $questions = $query->inRandomOrder()->limit(10)->get();

        return view('questions.practice', compact('questions'));
    }

    /**
     * Submit an answer to a question.
     */
    public function answer(Request $request, Question $question): JsonResponse
    {
        $request->validate([
            'option_id' => 'required|exists:question_options,id',
        ]);

        $selectedOption = QuestionOption::find($request->option_id);

        // Check if the selected option belongs to the question
        if ($selectedOption->question_id !== $question->id) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid option selected.',
            ], 400);
        }

        $isCorrect = $selectedOption->is_correct;

        // Record the answer if user is authenticated
        if (auth()->check()) {
            QuizAttemptAnswer::create([
                'user_id' => auth()->id(),
                'question_id' => $question->id,
                'question_option_id' => $request->option_id,
                'is_correct' => $isCorrect,
            ]);
        }

        return response()->json([
            'success' => true,
            'is_correct' => $isCorrect,
            'correct_option' => $question->options()->where('is_correct', true)->first(),
            'explanation' => $question->explanation,
        ]);
    }

    /**
     * Get questions by specialization (API).
     */
    public function getBySpecialization(Request $request, $specializationId): JsonResponse
    {
        $questions = Question::where('specialization_id', $specializationId)
                           ->where('status', 'approved')
                           ->with('options')
                           ->get();

        return response()->json($questions);
    }
}