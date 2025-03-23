<?php

use App\Http\Controllers\Auth\GoogleAuthController;
use Illuminate\Support\Facades\Route;

Route::get('/health/check', fn () => response()->json())->name('api.health.check');
Route::prefix('auth')->group(function () {
    Route::get('/google', [GoogleAuthController::class, 'redirectToGoogle'])->name('api.auth.google');
    Route::get('/google/callback', [GoogleAuthController::class, 'handleGoogleCallback'])->name('api.auth.google.callback');
});
