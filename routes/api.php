<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Authentication routes
Route::post('/login', [AuthController::class, 'login'])->name('api.login');
Route::post('/register', [AuthController::class, 'register'])->name('api.register');

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    // Email verification routes
    Route::prefix('email')->group(function () {
        Route::post('/verification-notification', [AuthController::class, 'sendEmailVerification'])
            ->middleware('throttle:6,1'); // 6 attempts per minute
    });
});
