<?php

use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/health/check', fn () => response()->json())->name('api.health.check');
Route::prefix('auth')->group(function () {
    Route::post('/google', GoogleAuthController::class)->name('api.auth.google');
});
Route::post('products/list', ProductController::class)->name('products.list');

Route::prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'get'])->name('api.cart.get');
    Route::post('add/{product}', [CartController::class, 'addItem'])->name('api.cart.add');
    Route::post('add-multiple', [CartController::class, 'addMultipleItems'])->name('api.cart.add.multiple');
    Route::delete('remove/{product}', [CartController::class, 'removeItem'])->name('api.cart.remove');
    Route::put('update/{product}/{quantity}', [CartController::class, 'updateQuantity'])->name('api.cart.update');
    Route::delete('clear', [CartController::class, 'clear'])->name('api.cart.clear');
});
