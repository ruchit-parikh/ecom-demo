<?php

use Illuminate\Support\Facades\Route;

Route::namespace('\App\Http\Controllers')->group(function() {
    Route::middleware(['jwt.auth'])->group(function() {
        Route::middleware(['admin'])->namespace('Admin')->group(function() {
            // Setup admin endpoints
        });

        Route::middleware(['customer'])->namespace('Customers')->group(function() {
            // Setup customer endpoints
        });

        Route::post('/user/logout', 'AuthController@logout');
    });

    Route::post('/user/login', 'AuthController@login');
});
