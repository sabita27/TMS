<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use App\Models\Client;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Designation;
use App\Models\Position;
use App\Models\Role;
use App\Models\StaffDetail;

class AdminController extends Controller
{
    public function index()
    {
        $stats = [
            'users' => User::count(),
            'products' => Product::count(),
            'clients' => Client::count(),
            'tickets' => Ticket::count(),
            'open_tickets' => Ticket::where('status', 'open')->count(),
        ];
        
        $recent_tickets = Ticket::with(['user', 'product'])->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recent_tickets'));
    }

    public function users()
    {
        $users = User::with(['role', 'staff_detail.designation', 'staff_detail.position'])->latest()->paginate(10);
        $roles = \App\Models\Role::all();
        $designations = \App\Models\Designation::where('status', true)->get();
        return view('admin.users', compact('users', 'roles', 'designations'));
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'role_id' => 'required|exists:roles,id',
            'phone' => 'nullable|string',
            'password' => 'required|string|min:8',
        ]);

        $role = Role::find($request->role_id);
        $isStaff = ($role && strtolower($role->name) == 'staff');

        if ($isStaff) {
            $request->validate([
                'designation_id' => 'required|exists:designations,id',
                'position_id' => 'required|exists:positions,id',
            ]);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $request->role_id,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        if ($isStaff) {
            $user->staff_detail()->create([
                'designation_id' => $request->designation_id,
                'position_id' => $request->position_id,
            ]);
        }

        return back()->with('success', 'User created successfully.');
    }

    public function editUser(User $user)
    {
        $user->load('staff_detail');
        return response()->json($user);
    }

    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role_id' => 'required|exists:roles,id',
            'phone' => 'nullable|string',
            'status' => 'required|in:0,1',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role_id' => $request->role_id,
            'status' => $request->status,
        ]);

        if ($request->password) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        $role = Role::find($request->role_id);
        $isStaff = ($role && strtolower($role->name) == 'staff');

        if ($isStaff) {
            $request->validate([
                'designation_id' => 'required|exists:designations,id',
                'position_id' => 'required|exists:positions,id',
            ]);
        }

        if ($isStaff) {
            $user->staff_detail()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'designation_id' => $request->designation_id,
                    'position_id' => $request->position_id,
                ]
            );
        } else {
            // Remove staff detail if role is no longer staff
            $user->staff_detail()->delete();
        }

        return back()->with('success', 'User updated successfully.');
    }

    public function getPositions(Designation $designation)
    {
        return response()->json($designation->positions);
    }

    public function destroyUser(User $user)
    {
        $user->delete();
        return back()->with('success', 'User deleted successfully.');
    }
}
