<?php

use App\Http\Middleware\IsLoggedIn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/health', function () {
    return response()->json([
        'status' => 'OK',
        'message' => 'API is healthy',
    ], 200);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group(['prefix' => 'auth'], function () {
    Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register']);
    Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);
    Route::get('/user', [\App\Http\Controllers\AuthController::class, 'user'])->middleware(IsLoggedIn::class);
    Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout'])->middleware(IsLoggedIn::class);
});
