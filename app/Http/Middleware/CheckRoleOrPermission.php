<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CheckRoleOrPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $roleOrPermission)
    {
        // Early return if no user
        if (! $request->user()) {
            return redirect()->route('login');
        }

        $user = $request->user();

        // Cache user roles and permissions for performance
        $cacheKey = "user_roles_permissions_{$user->id}";
        $userAccess = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($user) {
            return [
                'roles' => $user->getRoleNames()->toArray(),
                'permissions' => $user->getAllPermissions()->pluck('name')->toArray(),
            ];
        });

        // Check if it's a role or permission
        if (in_array($roleOrPermission, $userAccess['roles']) ||
            in_array($roleOrPermission, $userAccess['permissions'])) {
            return $next($request);
        }

        throw new AuthorizationException('This action is unauthorized.');
    }
}
