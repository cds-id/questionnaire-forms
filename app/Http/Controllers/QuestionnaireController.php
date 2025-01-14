<?php

namespace App\Http\Controllers;

use App\Models\Questionnaire;
use App\Models\Response;
use App\Models\Question;
use App\Models\Answer;
use Illuminate\Http\Request;
use DB;

class QuestionnaireController extends Controller
{
    public function show(Request $request)
    {
        if (session()->has('questionnaire_completed')) {
            return redirect()->route('questionnaire.thank-you');
        }

        // Check if this IP has submitted recently
        $recentResponse = Response::where('ip_address', $request->ip())
            ->where('created_at', '>', now()->subHours(24))
            ->first();

        if ($recentResponse) {
            return redirect()->route('questionnaire.thank-you');
        }

        $questionnaire = Questionnaire::findOrFail(1);
        $currentStep = $request->query('step', 1);
        $sections = $questionnaire->sections()->orderBy('order')->get();
        $currentSection = $sections[$currentStep - 1] ?? null;

        if (!$currentSection) {
            return redirect()->route('questionnaire.show', ['step' => 1]);
        }

        return view('questionnaire.show', [
            'questionnaire' => $questionnaire,
            'currentSection' => $currentSection,
            'currentStep' => $currentStep,
            'totalSteps' => $sections->count()
        ]);
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction(); // Start transaction
            // Check if user has already completed the questionnaire
            if (session()->has('questionnaire_completed')) {
                return redirect()->route('questionnaire.thank-you')
                    ->with('warning', 'You have already submitted your response.');
            }

            // Check if this IP has submitted recently
            $recentResponse = Response::where('ip_address', $request->ip())
                ->where('created_at', '>', now()->subHours(24))
                ->first();

            if ($recentResponse) {
                return redirect()->route('questionnaire.thank-you')
                    ->with('warning', 'You have already submitted a response recently. Please wait 24 hours before submitting again.');
            }

            $questionnaire = Questionnaire::findOrFail(1);
            $currentStep = $request->query('step', 1);
            $sections = $questionnaire->sections()->orderBy('order')->get();
            $totalSteps = $sections->count();

            // Validate current section's questions
            $currentSection = $sections[$currentStep - 1];
            $rules = [];
            foreach ($currentSection->questions as $question) {
                if ($question->is_required) {
                    $rules["answers.{$question->id}"] = 'required';
                }
            }

            $request->validate($rules);

            // Store answers in session
            $answers = $request->session()->get('questionnaire_answers', []);
            $newAnswers = $request->input('answers', []);

            // Validate that all question IDs exist
            $validQuestionIds = $currentSection->questions->pluck('id')->toArray();

            foreach ($newAnswers as $questionId => $value) {
                // Convert questionId to integer and validate
                $questionId = (int)$questionId;

                // Verify that the question exists in the database
                $questionExists = Question::where('id', $questionId)->exists();

                if (!$questionExists || !in_array($questionId, $validQuestionIds)) {
                    throw new \Exception("Invalid question ID: {$questionId}");
                }
            }

            $answers = array_merge($answers, $newAnswers);
            $request->session()->put('questionnaire_answers', $answers);

            // If this is the last step, save all answers
            if ($currentStep == $totalSteps) {
                $uniqueSessionId = uniqid('response_', true);

                $response = Response::create([
                    'questionnaire_id' => $questionnaire->id,
                    'session_id' => $uniqueSessionId,
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);

                // Validate all stored answers before saving
                $allQuestionIds = Question::whereIn('section_id', $sections->pluck('id'))->pluck('id')->toArray();

                foreach ($answers as $questionId => $value) {
                    $questionId = (int)$questionId;

                    // Skip if questionId is 0 or invalid
                    if ($questionId === 0 || !in_array($questionId, $allQuestionIds)) {
                        \Log::warning("Skipping invalid question ID: {$questionId}");
                        continue;
                    }

                    Answer::create([
                        'response_id' => $response->id,
                        'question_id' => $questionId,
                        'value' => $value,
                    ]);
                }

                // Mark questionnaire as completed in session
                session(['questionnaire_completed' => true]);
                $request->session()->forget('questionnaire_answers');

                DB::commit(); // Commit transaction
                return redirect()->route('questionnaire.thank-you');
            }
            DB::commit(); // Commit transaction
            return redirect()->route('questionnaire.show', [
                'step' => $currentStep + 1
            ]);

        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaction
            \Log::error('Error storing questionnaire answers: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());

            // Log the current state for debugging
            \Log::debug('Current answers:', $request->input('answers', []));
            \Log::debug('Session answers:', session('questionnaire_answers', []));
            return back()
                ->withErrors(['error' => 'An error occurred: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function thankYou()
    {
        return view('questionnaire.thank-you');
    }

    // Add a method to reset the questionnaire (optional, for testing)
    public function reset()
    {
        session()->forget(['questionnaire_completed', 'questionnaire_answers']);
        return redirect()->route('questionnaire.show', ['step' => 1])
            ->with('message', 'Questionnaire reset successfully.');
    }
}
