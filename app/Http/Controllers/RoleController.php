<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->latest()->paginate(10);
        $permissions = Permission::all();
        return view('admin.roles.index', compact('roles', 'permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name|max:255',
            'permissions' => 'nullable|array'
        ]);

        $role = Role::create([
            'name' => $request->name,
            'guard_name' => 'web'
        ]);

        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        // Clear cache
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return back()->with('success', 'Role created successfully.');
    }

    public function edit(Role $role)
    {
        return response()->json($role->load('permissions'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id . '|max:255',
            'permissions' => 'nullable|array'
        ]);

        $oldName = $role->name;
        $role->update([
            'name' => $request->name,
        ]);

        // If it's the admin role, we might want to ensure it always has all permissions
        // But we'll let the user decide for now, except we'll sync what's sent.
        
        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        } else {
            // For safety, don't allow removing all permissions from admin via this UI if it's the only admin
            if (strtolower($oldName) === 'admin' && empty($request->permissions)) {
                // Optional: force all permissions back to admin if you want to be super safe
                // $role->syncPermissions(Permission::all());
            } else {
                $role->syncPermissions([]);
            }
        }

        // Clear cache
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return back()->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        if (strtolower($role->name) === 'admin') {
            return back()->with('error', 'Cannot delete admin role.');
        }
        
        $role->delete();
        
        // Clear cache
        app(PermissionRegistrar::class)->forgetCachedPermissions();
        
        return back()->with('success', 'Role deleted successfully.');
    }
}
