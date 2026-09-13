<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            return response('Unauthorized', 401);
        }

        foreach ($roles as $role) {
            if ($user->role->value === $role) {
                return $next($request);
            }
        }

        abort(403);
    }
}
