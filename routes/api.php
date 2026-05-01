<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::middleware('throttle:5,1')->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user', fn () => request()->user());

        Route::apiResource('categories', CategoryController::class);
        Route::apiResource('products', ProductController::class);

        Route::get('/dashboard', [DashboardController::class, 'stats']);

        Route::apiResource('orders', OrderController::class)->only(['store', 'index', 'show']);
    });
});
