<?php

namespace App\Providers;

use App\Http\Guards\JWTAuthGuard;
use EcomDemo\Users\Repositories\Contracts\JWTTokensRepository;
use EcomDemo\Users\Repositories\Contracts\UserRepository;
use EcomDemo\Users\Repositories\EloquentJWTTokensRepository;
use EcomDemo\Users\Repositories\EloquentUserRepository;
use EcomDemo\Users\Services\Contracts\JWTTokensService;
use EcomDemo\Users\Services\LcobucciJWTTokensService;
use EcomDemo\Users\Services\TokensManager;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepository::class, EloquentUserRepository::class);
        $this->app->bind(JWTTokensRepository::class, EloquentJWTTokensRepository::class);

        $this->app->bind(JWTTokensService::class, function ($app) {
            return new LcobucciJWTTokensService(storage_path('keys/private_key.pem'), storage_path('keys/public_key.pem'), config('jwt.pass_phrase'));
        });

        $this->app->bind(TokensManager::class, function ($app) {
            $tokensService    = $app->make(JWTTokensService::class);
            $tokensRepository = $app->make(JWTTokensRepository::class);

            return new TokensManager($tokensService, $tokensRepository);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Auth::extend('jwt', function ($app, $name, array $config) {
            return new JWTAuthGuard(Auth::createUserProvider($config['provider']), $app['request']);
        });
    }
}
