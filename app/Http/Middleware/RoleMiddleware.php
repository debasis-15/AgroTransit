<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (! $request->user()) {
            return redirect()->route('login');
        }

        if (! in_array($request->user()->role, $roles, true)) {
            $route = match ($request->user()->role) {
                'farmer' => 'farmer.dashboard',
                'driver' => 'driver.dashboard',
                'transport_owner' => 'owner.dashboard',
                'admin' => 'admin.dashboard',
                default => 'home',
            };

            return redirect()
                ->route($route)
                ->with('status', 'You are already logged in as '.$request->user()->role.'. Use logout to switch accounts.');
        }

        return $next($request);
    }
}
