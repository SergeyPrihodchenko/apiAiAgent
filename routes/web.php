<?php

use Illuminate\Support\Facades\Route;

Route::get('/sber/token', [\App\Http\Controllers\SberController::class, 'getToken']);
Route::post('/sber/prompt', [\App\Http\Controllers\SberController::class, 'sendPrompt']);
Route::post('/sber/file-prompt', [\App\Http\Controllers\SberController::class, 'sendFileWithPrompt']);