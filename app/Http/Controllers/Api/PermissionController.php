<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Support\Facades\Validator;

class PermissionController extends Controller
{
    // 📌 List all permissions
    public function index()
    {
        $permissions = Permission::latest()->get();

        return response()->json([
            'status' => true,
            'data' => $permissions
        ]);
    }

    // 📌 Store permission
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:permissions,name|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $permission = Permission::create([
            'name' => strtolower($request->name), // recommended
            'guard_name' => 'web'
        ]);

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return response()->json([
            'status' => true,
            'message' => 'Permission created successfully',
            'data' => $permission
        ]);
    }

    // 📌 Show single permission
    public function show($id)
    {
        $permission = Permission::find($id);

        if (!$permission) {
            return response()->json([
                'status' => false,
                'message' => 'Permission not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $permission
        ]);
    }

    // 📌 Update permission
    public function update(Request $request, $id)
    {
        $permission = Permission::find($id);

        if (!$permission) {
            return response()->json([
                'status' => false,
                'message' => 'Permission not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:permissions,name,' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $permission->update([
            'name' => strtolower($request->name)
        ]);

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return response()->json([
            'status' => true,
            'message' => 'Permission updated successfully',
            'data' => $permission
        ]);
    }

    // 📌 Delete permission
    public function destroy($id)
    {
        $permission = Permission::find($id);

        if (!$permission) {
            return response()->json([
                'status' => false,
                'message' => 'Permission not found'
            ], 404);
        }

        $permission->delete();

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return response()->json([
            'status' => true,
            'message' => 'Permission deleted successfully'
        ]);
    }
}
