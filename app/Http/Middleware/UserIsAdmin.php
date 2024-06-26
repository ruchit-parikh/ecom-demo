<?php

namespace App\Http\Middleware;

use Closure;
use EcomDemo\Users\Entities\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     *
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var User|null $user */
        $user = $request->user();

        if ($user && $user->isAdmin()) {
            return $next($request);
        }

        return response()->json(['message' => __('You are not allowed to access this route')], Response::HTTP_FORBIDDEN);
    }
}
