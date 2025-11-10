<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Exceptions\UnauthorizedException;

class EnsureRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|array  ...$roles
     * @return mixed
     */
    public function handle($request, Closure $next, $role, $guard = null)
    {
        // Default to 'web' guard
        $defaultGuard = 'web';
        $availableGuards = array_keys(config('auth.guards', []));
        
        // Collect all role parameters
        $roleList = [];
        
        // The first parameter is always a role (or comma-separated roles)
        if (!empty($role)) {
            $roleList = array_merge($roleList, preg_split('/[,|]/', $role));
        }
        
        // If guard parameter is provided, check if it's actually a guard or another role
        if ($guard !== null) {
            // If it's a valid guard name, use it as guard
            if (in_array($guard, $availableGuards)) {
                $defaultGuard = $guard;
            } else {
                // Otherwise, it's another role parameter (Laravel splits commas)
                $roleList[] = $guard;
            }
        }
        
        $roleList = array_filter(array_map('trim', $roleList));
        
        if (empty($roleList)) {
            throw UnauthorizedException::forRoles([]);
        }
        
        $authGuard = Auth::guard($defaultGuard);

        if ($authGuard->guest()) {
            throw UnauthorizedException::notLoggedIn();
        }

        if (! $authGuard->user()->hasAnyRole($roleList)) {
            throw UnauthorizedException::forRoles($roleList);
        }

        return $next($request);
    }
}

