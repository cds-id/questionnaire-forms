<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\QuestionnaireController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('questionnaire.show', ['step' => 1]);
});

Route::get('/questionnaire', [QuestionnaireController::class, 'show'])
    ->name('questionnaire.show');
Route::post('/questionnaire', [QuestionnaireController::class, 'store'])
    ->name('questionnaire.store');
Route::get('/thank-you', [QuestionnaireController::class, 'thankYou'])
    ->name('questionnaire.thank-you');

Route::prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'login'])->name('admin.login');
    Route::get('/login', [AdminController::class, 'login'])->name('admin.login');
    Route::post('/login', [AdminController::class, 'authenticate'])->name('admin.authenticate');
    Route::post('/logout', [AdminController::class, 'logout'])->name('admin.logout');
    Route::get('/responses', [AdminController::class, 'responses'])->name('admin.responses');
    Route::get('/responses/export', [AdminController::class, 'exportExcel'])->name('admin.responses.export');
    Route::delete('/responses/{response}', [AdminController::class, 'destroy'])->name('admin.responses.destroy');
});

require __DIR__.'/auth.php';
