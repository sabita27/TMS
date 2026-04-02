<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * 🔐 LOGIN API
     */
    public function login(Request $request)
    {
        // ✅ Validation
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        // ✅ Check user
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status'  => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

        // ✅ SAFELY delete old tokens (avoid error if none exist)
        if (method_exists($user, 'tokens')) {
            $user->tokens()->delete();
        }

        // ✅ Create token (safe check)
        if (!method_exists($user, 'createToken')) {
            return response()->json([
                'status'  => false,
                'message' => 'Sanctum not installed or configured'
            ], 500);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'status'  => true,
            'message' => 'Login successful',
            'token'   => $token,
            'user'    => $user
        ]);
    }

    /**
     * 🔓 LOGOUT API
     */
    public function logout(Request $request)
    {
        try {
            if ($request->user() && $request->user()->currentAccessToken()) {
                $request->user()->currentAccessToken()->delete();
            }

            return response()->json([
                'status'  => true,
                'message' => 'Logged out successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Logout failed',
                'error'   => $e->getMessage() // 👈 for debugging
            ], 500);
        }
    }

    /**
     * 👤 GET AUTH USER
     */
    public function me(Request $request)
    {
        if (!$request->user()) {
            return response()->json([
                'status'  => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        return response()->json([
            'status' => true,
            'data'   => $request->user()
        ]);
    }
}