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

        // Debug logging
        \Log::info('RoleMiddleware Check', [
            'user_id' => $user ? $user->id : 'null',
            'user_email' => $user ? $user->email : 'null',
            'user_role_id' => $user ? $user->role_id : 'null',
            'user_role_name' => ($user && $user->role) ? $user->role->name : 'null',
            'required_roles' => $roles,
        ]);

        if (!$user) {
            return redirect()->route('login');
        }

        // If user has no role assigned (broken state), log them out to prevent loops
        if (!$user->role) {
            \Log::warning('User has no role assigned', ['user_id' => $user->id, 'email' => $user->email]);
            Auth::logout();
            return redirect()->route('login')->with('error', 'Your account has no role assigned. Please contact support.');
        }

        // Allow Admin to access all routes
        if ($user->role->name === 'admin') {
            return $next($request);
        }

        // If user has a role but not the required one, show 403
        if (!in_array($user->role->name, $roles)) {
            \Log::warning('User role not authorized', [
                'user_id' => $user->id,
                'email' => $user->email,
                'user_role' => $user->role->name,
                'required_roles' => $roles,
            ]);
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }
            abort(403, 'You do not have permission to access this page. Your role: ' . $user->role->name . ', Required: ' . implode(', ', $roles));
        }

        return $next($request);
    }
}
