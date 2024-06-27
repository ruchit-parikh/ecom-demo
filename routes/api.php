<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Customers\UserController;
use Illuminate\Support\Facades\Route;

Route::namespace('\App\Http\Controllers')->group(function () {
    Route::middleware(['jwt.auth'])->group(function () {
        Route::middleware(['admin'])->namespace('Admin')->group(function () {
            // Setup admin endpoints
        });

        Route::middleware(['customer'])->prefix('user')->namespace('Customers')->group(function () {
            Route::post('/logout', [AuthController::class, 'logout']);

            Route::get('/orders', [UserController::class, 'getOrders']);
            Route::put('/edit', [UserController::class, 'edit']);
            Route::get('/', [UserController::class, 'show']);
            Route::delete('/', [UserController::class, 'deleteAccount']);
        });
    });

    Route::post('/user/reset-password-token', [AuthController::class, 'resetPassword']);
    Route::post('/user/forgot-password', [AuthController::class, 'sendPasswordResetLink']);
    Route::post('/user/create', [AuthController::class, 'register']);
    Route::post('/user/login', [AuthController::class, 'login']);
});
