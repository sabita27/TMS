<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    // 📌 List all roles with permissions
    public function index()
    {
        $roles = Role::with('permissions')->latest()->get();

        return response()->json([
            'status' => true,
            'data' => $roles
        ]);
    }

    // 📌 Store role
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:roles,name|max:255',
            'permissions' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $role = Role::create([
            'name' => $request->name,
            'guard_name' => 'web'
        ]);

        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return response()->json([
            'status' => true,
            'message' => 'Role created successfully',
            'data' => $role->load('permissions')
        ]);
    }

    // 📌 Show single role
    public function show($id)
    {
        $role = Role::with('permissions')->find($id);

        if (!$role) {
            return response()->json([
                'status' => false,
                'message' => 'Role not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $role
        ]);
    }

    // 📌 Update role
    public function update(Request $request, $id)
    {
        $role = Role::find($id);

        if (!$role) {
            return response()->json([
                'status' => false,
                'message' => 'Role not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255|unique:roles,name,' . $id,
            'permissions' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $oldName = $role->name;

        $role->update([
            'name' => $request->name
        ]);

        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        } else {
            if (strtolower($oldName) === 'admin') {
                // Optional: keep admin safe
                // $role->syncPermissions(Permission::all());
            } else {
                $role->syncPermissions([]);
            }
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return response()->json([
            'status' => true,
            'message' => 'Role updated successfully',
            'data' => $role->load('permissions')
        ]);
    }

    // 📌 Delete role
    public function destroy($id)
    {
        $role = Role::find($id);

        if (!$role) {
            return response()->json([
                'status' => false,
                'message' => 'Role not found'
            ], 404);
        }

        if (strtolower($role->name) === 'admin') {
            return response()->json([
                'status' => false,
                'message' => 'Cannot delete admin role'
            ], 403);
        }

        $role->delete();

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return response()->json([
            'status' => true,
            'message' => 'Role deleted successfully'
        ]);
    }
}
