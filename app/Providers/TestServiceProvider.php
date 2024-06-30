<?php

namespace App\Providers;

use EcomDemo\Users\Services\Contracts\JWTTokensService;
use EcomDemo\Users\Services\LcobucciJWTTokensService;
use Illuminate\Config\Repository;
use Illuminate\Support\ServiceProvider;

class TestServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(JWTTokensService::class, function ($app) {
            /** @var Repository $configRepository */
            $configRepository = $app->make(Repository::class);

            return new LcobucciJWTTokensService(storage_path('keys/private_key_test.pem'), storage_path('keys/public_key_test.pem'), $configRepository);
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
