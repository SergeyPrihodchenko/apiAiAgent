<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::group(['middleware' => 'auth:api'], function() {
    Route::get('/sber/token', [\App\Http\Controllers\SberController::class, 'getToken']);
    Route::get('/sber/prompt', [\App\Http\Controllers\SberController::class, 'sendPrompt']);
    Route::post('/sber/file-prompt', [\App\Http\Controllers\SberController::class, 'sendFileWithPrompt']);
});