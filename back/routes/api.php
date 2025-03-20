<?php

use Illuminate\Support\Facades\Route;

Route::get('/health/check', fn () => response()->json())->name('api.health.check');
