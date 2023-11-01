<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;

class EnsureJsonResponse
{
    /**
     * Force the request to return jso
     *
     * @param  Closure(Request): (\Illuminate\Http\Response|RedirectResponse)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->headers->has('Accept') && ! Str::contains($request->header('Accept'), ['/json', '/*'])) {
            throw new NotAcceptableHttpException();
        }

        $request->headers->set('Accept', 'application/json');

        return $next($request);
    }
}
