<?php

namespace App\Providers;

use App\Http\Guards\JWTAuthGuard;
use EcomDemo\Orders\Repositories\Contracts\OrderRepository;
use EcomDemo\Orders\Repositories\EloquentOrderRepository;
use EcomDemo\Users\Repositories\Contracts\JWTTokensRepository;
use EcomDemo\Users\Repositories\Contracts\UserRepository;
use EcomDemo\Users\Repositories\EloquentJWTTokensRepository;
use EcomDemo\Users\Repositories\EloquentUserRepository;
use EcomDemo\Users\Services\Contracts\JWTTokensService;
use EcomDemo\Users\Services\LcobucciJWTTokensService;
use EcomDemo\Users\Services\TokensManager;
use Illuminate\Config\Repository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(UserRepository::class, EloquentUserRepository::class);
        $this->app->singleton(JWTTokensRepository::class, EloquentJWTTokensRepository::class);
        $this->app->singleton(OrderRepository::class, EloquentOrderRepository::class);

        $this->app->singleton(JWTTokensService::class, function ($app) {
            /** @var Repository $configRepository */
            $configRepository = $app->make(Repository::class);

            return new LcobucciJWTTokensService(storage_path('keys/private_key.pem'), storage_path('keys/public_key.pem'), $configRepository);
        });

        $this->app->singleton(TokensManager::class, function ($app) {
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
