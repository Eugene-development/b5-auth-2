<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\Http\Controllers\CsrfCookieController;

Route::get('/', function () {
    return view('welcome');
});

// Named login route for middleware redirects
Route::get('/login', function () {
    return response()->json(['message' => 'Please log in via /api/login'], 401);
})->name('login');


Route::get('/health', function () {
    return "Health check";
});

// Sanctum CSRF cookie route with CORS support
Route::get('/sanctum/csrf-cookie', [CsrfCookieController::class, 'show'])
    ->middleware('web');

// Explicit OPTIONS handler for CSRF cookie (for CORS preflight)
Route::options('/sanctum/csrf-cookie', function () {
    return response('', 204);
})->middleware('web');

Route::get('/test-db', function () {
    try {
        DB::connection()->getPdo();
        return 'База данных подключена!!!';
    } catch (\Exception $e) {
        return 'Unable to connect to the database: ' . $e->getMessage();
    }
});

// Email verification route (signed, no auth required)
Route::get('/api/email/verify/{id}/{hash}', [App\Http\Controllers\AuthController::class, 'verifyEmail'])
    ->middleware(['signed'])
    ->name('verification.verify');
