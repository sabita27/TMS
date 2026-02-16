<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = \App\Models\Role::latest()->paginate(10);
        return view('admin.roles.index', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name|max:255',
        ]);

        \App\Models\Role::create([
            'name' => $request->name,
        ]);

        return back()->with('success', 'Role created successfully.');
    }

    public function edit(\App\Models\Role $role)
    {
        return response()->json($role);
    }

    public function update(Request $request, \App\Models\Role $role)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id . '|max:255',
        ]);

        $role->update([
            'name' => $request->name,
        ]);

        return back()->with('success', 'Role updated successfully.');
    }

    public function destroy(\App\Models\Role $role)
    {
        $role->delete();
        return back()->with('success', 'Role deleted successfully.');
    }
}
