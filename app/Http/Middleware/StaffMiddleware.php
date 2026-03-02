<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class StaffMiddleware
{
    /**
     * Handle an incoming request.
     * Allows users with the 'admin' or 'staff' role.
     * Admin can access all routes (super-user rule).
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to continue.');
        }

        if (!Auth::user()->hasAnyRole(['admin', 'staff'])) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized. Staff access required.'], 403);
            }
            abort(403, 'Unauthorized. Staff access required.');
        }

        return $next($request);
    }
}
