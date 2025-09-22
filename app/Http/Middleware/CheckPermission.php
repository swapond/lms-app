<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $permission)
    {
        // Early return if no user
        if (! $request->user()) {
            return redirect()->route('login');
        }

        $user = $request->user();

        // Cache user permissions for performance
        $userPermissions = Cache::remember(
            "user_permissions_{$user->id}",
            now()->addMinutes(10),
            fn () => $user->getAllPermissions()->pluck('name')->toArray()
        );

        if (! in_array($permission, $userPermissions)) {
            throw new AuthorizationException('This action is unauthorized.');
        }

        return $next($request);
    }
}
