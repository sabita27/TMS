<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * 📋 GET ALL USERS
     */
    public function index()
    {
        $users = User::latest()->get();

        return response()->json([
            'status'  => true,
            'message' => 'Users fetched successfully',
            'data'    => $users,
        ]);
    }

    /**
     * ➕ CREATE USER
     */
    public function store(Request $request)
    {
        $messages = [
            'name.required'     => 'Name is required',
            'email.required'    => 'Email is required',
            'email.email'       => 'Invalid email format',
            'email.unique'      => 'Email already exists',
            'password.required' => 'Password is required',
            'password.min'      => 'Password must be at least 6 characters',
        ];

        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ], $messages);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => $validator->errors()->first(),
                'errors'  => $validator->errors(),
            ], 422);
        }

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if ($request->has('role')) {
            $user->assignRole($request->role);
        }

        return response()->json([
            'status'  => true,
            'message' => 'User created successfully',
            'data'    => $user,
        ]);
    }

    /**
     * 🔍 GET SINGLE USER
     */
    public function show($id)
    {
        $user = User::find($id);

        if (! $user) {
            return response()->json([
                'status'  => false,
                'message' => 'User not found',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data'   => $user,
            'role'   => method_exists($user, 'getRoleNames') ? $user->getRoleNames() : [],
        ]);
    }

    /**
     * ✏️ UPDATE USER
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (! $user) {
            return response()->json([
                'status'  => false,
                'message' => 'User not found',
            ], 404);
        }

        $messages = [
            'email.email'  => 'Invalid email format',
            'email.unique' => 'Email already exists',
            'password.min' => 'Password must be at least 6 characters',
        ];

        $validator = Validator::make($request->all(), [
            'name'     => 'nullable|string|max:255',
            'email'    => 'nullable|email|unique:users,email,' . $id,
            'password' => 'nullable|min:6',
        ], $messages);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => $validator->errors()->first(),
                'errors'  => $validator->errors(),
            ], 422);
        }

        // ✅ Safe update
        if ($request->filled('name')) {
            $user->name = $request->name;
        }

        if ($request->filled('email')) {
            $user->email = $request->email;
        }

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        // ✅ Update role
        if ($request->has('role')) {
            $user->syncRoles([$request->role]);
        }

        return response()->json([
            'status'  => true,
            'message' => 'User updated successfully',
            'data'    => $user,
        ]);
    }

    /**
     * ❌ DELETE USER
     */
    public function destroy($id)
    {
        $user = User::find($id);

        if (! $user) {
            return response()->json([
                'status'  => false,
                'message' => 'User not found',
            ], 404);
        }

        $user->delete();

        return response()->json([
            'status'  => true,
            'message' => 'User deleted successfully',
        ]);
    }

    /**
 * 📦 Browse Products (User API)
 */
public function products(Request $request)
{
    $products = \App\Models\Product::where('status', 1)
        ->with([
            'category:id,name',
            'subCategory:id,name'
        ])
        ->latest()
        ->paginate(12);

    return response()->json([
        'status' => true,
        'count'  => $products->total(),
        'data'   => $products
    ]);
}
}