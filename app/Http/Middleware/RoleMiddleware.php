<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (! $request->user()) {
            return redirect('login');
        }

        // Admin (role 1) has access to everything
        if ($request->user()->role_id == 1) {
            return $next($request);
        }

        if (! in_array($request->user()->role_id, $roles)) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
