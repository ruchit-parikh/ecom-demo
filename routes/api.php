<?php

use Illuminate\Support\Facades\Route;

Route::namespace('\App\Http\Controllers')->group(function () {
    Route::middleware(['jwt.auth'])->group(function () {
        Route::middleware(['admin'])->namespace('Admin')->group(function () {
            // Setup admin endpoints
        });

        Route::middleware(['customer'])->namespace('Customers')->group(function () {
            // Setup customer endpoints
        });

        Route::post('/user/logout', 'AuthController@logout');

        Route::get('/user/orders', 'UserController@getOrders');
        Route::put('/user/edit', 'UserController@edit');
        Route::get('/user', 'UserController@show');
        Route::delete('/user', 'UserController@deleteAccount');
    });

    Route::post('/user/reset-password-token', 'AuthController@resetPassword');
    Route::post('/user/forgot-password', 'AuthController@sendPasswordResetLink');
    Route::post('/user/create', 'AuthController@register');
    Route::post('/user/login', 'AuthController@login');
});
