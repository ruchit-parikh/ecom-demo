<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class JWTAuthenticate
{
    /**
     * The authentication factory instance.
     *
     * @var Auth
     */
    protected Auth $auth;

    /**
     * Create a new middleware instance.
     *
     * @param Auth $auth
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  Request $request
     * @param  Closure $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($this->auth->guard(config('auth.defaults.guard'))->guest()) {
            return response()->json(['message' => 'You are not logged in or token you passed is expired'], Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}
