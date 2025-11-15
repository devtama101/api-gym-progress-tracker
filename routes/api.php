<?php

use App\Http\Middleware\IsLoggedIn;
use Illuminate\Support\Facades\Route;

Route::get('/health', function () {
    return response()->json([
        'status' => 'OK',
        'message' => 'API is healthy',
    ], 200);
});

Route::group(['prefix' => 'auth'], function () {
    Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register']);
    Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);
    Route::get('/user', [\App\Http\Controllers\AuthController::class, 'user'])->middleware(IsLoggedIn::class);
    Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout'])->middleware(IsLoggedIn::class);
});

Route::middleware([IsLoggedIn::class])->group(function () {
    Route::apiResource('programs', \App\Http\Controllers\ProgramController::class);
});
