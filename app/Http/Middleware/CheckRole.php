<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role)
    {
        // Early return if no user
        if (! $request->user()) {
            return redirect()->route('login');
        }

        $user = $request->user();

        // Cache user roles for performance
        $userRoles = Cache::remember(
            "user_roles_{$user->id}",
            now()->addMinutes(10),
            fn () => $user->getRoleNames()->toArray()
        );

        if (! in_array($role, $userRoles)) {
            throw new AuthorizationException('This action is unauthorized.');
        }

        return $next($request);
    }
}
