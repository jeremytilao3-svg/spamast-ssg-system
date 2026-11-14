<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    // Let the request through only if the user has one of the allowed roles
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // 403 Forbidden if the user is missing or has the wrong role
        abort_unless($request->user()?->hasRole(...$roles), 403);

        return $next($request);
    }
}