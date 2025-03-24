<?php

use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/health/check', fn () => response()->json())->name('api.health.check');
Route::prefix('auth')->group(function () {
    Route::post('/google', GoogleAuthController::class)->name('api.auth.google');
});
Route::post('products/list', ProductController::class)->name('products.list');
