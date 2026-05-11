<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        if ($role === 'admin' && ! auth()->user()->isAdmin()) {
            abort(403, 'Admin access required.');
        }

        if ($role === 'user' && ! auth()->user()->isUser()) {
            abort(403, 'Access denied.');
        }

        return $next($request);
    }
}
