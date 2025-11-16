<?php

use App\Http\Middleware\IsLoggedIn;
use Illuminate\Support\Facades\Log;
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
    Route::apiResource('muscle-groups', \App\Http\Controllers\MuscleGroupController::class);
    Route::apiResource('exercises', \App\Http\Controllers\ExerciseController::class);
});

Route::get('/test-log', function () {
    // Test all log levels
    Log::emergency('EMERGENCY test');
    Log::alert('ALERT test');
    Log::critical('CRITICAL test');
    Log::error('ERROR test');
    Log::warning('WARNING test');
    Log::notice('NOTICE test');
    Log::info('INFO test');
    Log::debug('DEBUG test');

    // Check if file exists and is writable
    $logPath = storage_path('logs/laravel.log');
    $fileExists = file_exists($logPath);
    $isWritable = is_writable($logPath);
    $dirWritable = is_writable(storage_path('logs/'));

    return 'Log test completed!<br>
            File exists: '.($fileExists ? 'Yes' : 'No').'<br>
            File writable: '.($isWritable ? 'Yes' : 'No').'<br>
            Directory writable: '.($dirWritable ? 'Yes' : 'No').'<br>
            Check: storage/logs/laravel.log';
});
