<?php

use App\Http\Middleware\JWTAuthenticate;
use App\Http\Middleware\UserIsAdmin;
use App\Http\Middleware\UserIsCustomer;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\HandleCors;
use Illuminate\Routing\Middleware\SubstituteBindings;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withRouting(
        api: __DIR__.'/../routes/api.php',
        apiPrefix: 'api/v1',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->group('api', [
             'throttle:60',
            SubstituteBindings::class,
            HandleCors::class
        ]);

        $middleware->alias([
            'admin'    => UserIsAdmin::class,
            'customer' => UserIsCustomer::class,
            'jwt.auth' => JWTAuthenticate::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();

