<?php

use App\Http\Controllers\QuestionnaireController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/questionnaire/{questionnaire}', [QuestionnaireController::class, 'show'])
    ->name('questionnaire.show');
Route::post('/questionnaire/{questionnaire}', [QuestionnaireController::class, 'submit'])
    ->name('questionnaire.submit');
Route::get('/thank-you', [QuestionnaireController::class, 'thankYou'])
    ->name('questionnaire.thank-you');

require __DIR__.'/auth.php';
