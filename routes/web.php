<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller; // ← 🔥これが抜けがち！
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScheduleController; // 
use App\Http\Controllers\GeminiController;
use App\Http\Controllers\SurveyController; // 
use App\Http\Controllers\WeatherController;
use App\Http\Controllers\ConditionController; // 体調記録コントローラーを追加
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;

use App\Models\WeatherInfo;

Route::get('/', function () {
    $apiKey = config('services.openweather.key'); // ← .envから取得
    $city = 'Tokyo';
    $url = "https://api.openweathermap.org/data/2.5/weather?q={$city}&appid={$apiKey}&units=metric&lang=ja";

    $response = Http::get($url);
    $weather = $response->json();

    return view('welcome', compact('weather'));
});

Route::middleware('auth')->group(function () {
    // 体調記録機能のルート
    Route::get('/conditions/history', [ConditionController::class, 'history'])->name('conditions.history');
    Route::get('/conditions/{childSurvey}/create', [ConditionController::class, 'create'])->name('conditions.create');
    Route::post('/conditions/{childSurvey}', [ConditionController::class, 'store'])->name('conditions.store');
    Route::get('/conditions/{childSurvey}/{dailyConditionRecord}/edit', [ConditionController::class, 'edit'])->name('conditions.edit');
    Route::put('/conditions/{childSurvey}/{dailyConditionRecord}', [ConditionController::class, 'update'])->name('conditions.update');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::get('/schedule', [ScheduleController::class, 'index'])->name('schedule');
    Route::post('/schedule-add', [ScheduleController::class, 'scheduleAdd'])->name('schedule-add');
    Route::post('/schedule-get', [ScheduleController::class, 'scheduleGet'])->name('schedule-get');
    Route::get('/schedule-list', [ScheduleController::class, 'scheduleList'])->name('schedule-list');
    Route::get('/schedules/{id}', [ScheduleController::class, 'show'])->name('schedules.show');
    Route::delete('/schedules/{id}', [ScheduleController::class, 'destroy'])->name('schedules.destroy');
    Route::post('/schedules', [ScheduleController::class, 'store'])->name('schedules.store');
    Route::get('/schedules/{id}/edit', [ScheduleController::class, 'edit'])->name('schedules.edit');
    Route::patch('/schedules/{id}', [ScheduleController::class, 'update'])->name('schedules.update');

    // ① チャット履歴の表示（画面を開いたとき）
    Route::get('/ai-consult', [AiConsultController::class, 'index'])->name('ai-consult.index');
    // ② 質問の送信（Geminiで回答→DB保存）
    Route::post('/ai-consult', [AiConsultController::class, 'store'])->name('ai-consult.store');
    Route::get('/dashboard', [WeatherController::class, 'dashboard'])->name('dashboard');
    Route::get('/weather', [WeatherController::class, 'weather'])->name('weather');

    Route::get('/surveys/parent', [SurveyController::class, 'showParentSurvey'])->name('surveys.parent');
    Route::post('/surveys/parent', [SurveyController::class, 'submitParentSurvey'])->name('surveys.parent.submit');
    Route::get('/surveys/child/{siblingOrder?}', [SurveyController::class, 'showChildSurvey'])->name('surveys.child');
    Route::post('/surveys/child', [SurveyController::class, 'submitChildSurvey'])->name('surveys.child.submit');
    Route::get('/surveys/child/{siblingOrder}/details', [SurveyController::class, 'showChildDetailsSurvey'])->name('surveys.child.details');
    Route::post('/surveys/child/details', [SurveyController::class, 'submitChildDetailsSurvey'])->name('surveys.child.details.submit');
    Route::get('/surveys/confirmation', [SurveyController::class, 'showConfirmation'])->name('surveys.confirmation');
    Route::get('/surveys/confirmation/child/{siblingOrder}', [SurveyController::class, 'showChildConfirmation'])->name('surveys.confirmation.child');
    Route::get('/surveys/parent/edit', [SurveyController::class, 'editParentSurvey'])->name('surveys.parent.edit');
    Route::get('/surveys/child/{siblingOrder}/edit', [SurveyController::class, 'editChildSurvey'])->name('surveys.child.edit');
    Route::get('/surveys/completed', [SurveyController::class, 'showCompletedPage'])->name('surveys.completed');
    Route::patch('/surveys/parent', [SurveyController::class, 'updateParentSurvey'])->name('surveys.parent.update');
    Route::patch('/surveys/child', [SurveyController::class, 'updateChildSurvey'])->name('surveys.child.update');

    // 1日の振り返り（夜の体調記録）
    Route::get('/conditions/{childSurvey}/look-back', [ConditionController::class, 'lookBackCreate'])->name('conditions.look_back.create');
    Route::post('/conditions/{childSurvey}/look-back', [ConditionController::class, 'lookBackStore'])->name('conditions.look_back.store');
});

require __DIR__.'/auth.php';
