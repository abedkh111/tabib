<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\QuizAttemptAnswer;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class QuizController extends Controller
{
    /**
     * Start a quiz.
     */
    public function start(Quiz $quiz): View
    {
        // Check if quiz is available
        if ($quiz->status !== 'active') {
            abort(404, 'Quiz not available.');
        }

        // Check if user has already attempted this quiz
        $existingAttempt = QuizAttempt::where('user_id', auth()->id())
                                    ->where('quiz_id', $quiz->id)
                                    ->where('status', 'completed')
                                    ->first();

        if ($existingAttempt) {
            return redirect()->route('quizzes.results', [$quiz, $existingAttempt])
                           ->with('info', 'You have already completed this quiz.');
        }

        $quiz->load(['questions' => function ($query) {
            $query->with('options')->inRandomOrder();
        }]);

        // Create or get existing attempt
        $attempt = QuizAttempt::firstOrCreate([
            'user_id' => auth()->id(),
            'quiz_id' => $quiz->id,
            'status' => 'in_progress',
        ], [
            'started_at' => now(),
            'time_limit' => $quiz->time_limit,
        ]);

        return view('quizzes.start', compact('quiz', 'attempt'));
    }

    /**
     * Submit quiz answers.
     */
    public function submit(Request $request, Quiz $quiz): RedirectResponse
    {
        $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'integer|exists:question_options,id',
            'time_taken' => 'nullable|integer',
        ]);

        // Get or create attempt
        $attempt = QuizAttempt::where('user_id', auth()->id())
                            ->where('quiz_id', $quiz->id)
                            ->where('status', 'in_progress')
                            ->first();

        if (!$attempt) {
            return redirect()->route('quizzes.start', $quiz)
                           ->with('error', 'No active attempt found.');
        }

        $correctAnswers = 0;
        $totalQuestions = count($request->answers);

        // Save answers
        foreach ($request->answers as $questionId => $optionId) {
            $question = Question::find($questionId);
            $option = $question->options()->find($optionId);

            $isCorrect = $option && $option->is_correct;

            if ($isCorrect) {
                $correctAnswers++;
            }

            QuizAttemptAnswer::create([
                'quiz_attempt_id' => $attempt->id,
                'question_id' => $questionId,
                'question_option_id' => $optionId,
                'is_correct' => $isCorrect,
            ]);
        }

        $score = ($correctAnswers / $totalQuestions) * 100;

        // Update attempt
        $attempt->update([
            'status' => 'completed',
            'completed_at' => now(),
            'score' => $score,
            'time_taken' => $request->time_taken,
        ]);

        return redirect()->route('quizzes.results', [$quiz, $attempt])
                       ->with('success', 'Quiz submitted successfully!');
    }

    /**
     * Show quiz results.
     */
    public function results(Quiz $quiz, QuizAttempt $attempt): View
    {
        // Verify attempt belongs to user and quiz
        if ($attempt->user_id !== auth()->id() || $attempt->quiz_id !== $quiz->id) {
            abort(403);
        }

        $attempt->load(['answers.question', 'answers.selectedOption']);

        return view('quizzes.results', compact('quiz', 'attempt'));
    }

    /**
     * Show user's quiz attempts.
     */
    public function attempts(): View
    {
        $attempts = QuizAttempt::where('user_id', auth()->id())
                             ->with(['quiz'])
                             ->orderBy('created_at', 'desc')
                             ->paginate(20);

        return view('quiz-attempts.index', compact('attempts'));
    }

    /**
     * Get quiz questions (API).
     */
    public function getQuestions(Quiz $quiz): JsonResponse
    {
        $questions = $quiz->questions()->with('options')->get();

        return response()->json($questions);
    }

    /**
     * Save quiz progress (API).
     */
    public function saveProgress(Request $request, Quiz $quiz): JsonResponse
    {
        $request->validate([
            'answers' => 'required|array',
            'current_question' => 'nullable|integer',
        ]);

        $attempt = QuizAttempt::where('user_id', auth()->id())
                            ->where('quiz_id', $quiz->id)
                            ->where('status', 'in_progress')
                            ->first();

        if (!$attempt) {
            return response()->json([
                'success' => false,
                'message' => 'No active attempt found.',
            ], 404);
        }

        // Save current progress (you might want to store partial answers)
        $attempt->update([
            'current_question' => $request->current_question,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Progress saved.',
        ]);
    }
}