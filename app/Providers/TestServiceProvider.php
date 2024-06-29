<?php

namespace App\Providers;

use EcomDemo\Users\Services\Contracts\JWTTokensService;
use EcomDemo\Users\Services\LcobucciJWTTokensService;
use Illuminate\Support\ServiceProvider;

class TestServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(JWTTokensService::class, function ($app) {
            return new LcobucciJWTTokensService(storage_path('keys/private_key_test.pem'), storage_path('keys/public_key_test.pem'), config('jwt.pass_phrase'));
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
