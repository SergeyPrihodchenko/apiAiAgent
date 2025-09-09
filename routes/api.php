<?php

use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'api'], function() {
    Route::post('/test', [TestController::class, 'index']);
});