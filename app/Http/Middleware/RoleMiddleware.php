<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Allow Admin to access all routes
        if ($user->hasRole('admin')) {
            return $next($request);
        }

        // If user has a role but not the required one, show 403
        if (!$user->hasAnyRole($roles)) {
            \Log::warning('User role not authorized', [
                'user_id' => $user->id,
                'email' => $user->email,
                'user_roles' => $user->getRoleNames(),
                'required_roles' => $roles,
            ]);
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }
            abort(403, 'You do not have permission to access this page.');
        }

        return $next($request);
    }
}
