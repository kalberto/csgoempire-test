<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AuthenticateWithToken
{
    /**
     * Check if an incoming request has the correct bearer token.
     *
     * @param  Closure(Request): (Response|RedirectResponse)  $next
     */
    public function handle(Request $request, Closure $next): JsonResponse
    {
        if (config('auth.api_token') === $request->bearerToken()) {
            return $next($request);
        }

        abort(401, 'Unauthenticated');
    }
}
