<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ManagerMiddleware
{
    /**
     * Handle an incoming request.
     * Allows users with the 'admin' or 'manager' role.
     * Admin can access all routes (super-user rule).
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to continue.');
        }

        if (!Auth::user()->hasAnyRole(['admin', 'manager'])) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized. Manager access required.'], 403);
            }
            abort(403, 'Unauthorized. Manager access required.');
        }

        return $next($request);
    }
}
