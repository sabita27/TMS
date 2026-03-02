<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserMiddleware
{
    /**
     * Handle an incoming request.
     * Allows authenticated users with the 'user' role.
     * Admin can access all routes (super-user rule).
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to continue.');
        }

        if (!Auth::user()->hasAnyRole(['admin', 'user'])) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized. User access required.'], 403);
            }
            abort(403, 'Unauthorized. User access required.');
        }

        return $next($request);
    }
}
