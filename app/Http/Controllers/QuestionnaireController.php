<?php

namespace App\Http\Controllers;

use App\Models\Questionnaire;
use App\Models\Response;
use App\Models\Answer;
use Illuminate\Http\Request;

class QuestionnaireController extends Controller
{
    public function show(Questionnaire $questionnaire)
    {
        return view('questionnaire.show', compact('questionnaire'));
    }

    public function submit(Request $request, Questionnaire $questionnaire)
    {
        $request->validate([
            'answers' => ['required', 'array'],
            'answers.*' => ['required_if:questions.*.is_required,true'],
        ]);

        $response = Response::create([
            'questionnaire_id' => $questionnaire->id,
            'session_id' => session()->getId(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        foreach ($request->answers as $questionId => $value) {
            Answer::create([
                'response_id' => $response->id,
                'question_id' => $questionId,
                'value' => $value,
            ]);
        }

        return redirect()->route('questionnaire.thank-you');
    }

    public function thankYou()
    {
        return view('questionnaire.thank-you');
    }
}
