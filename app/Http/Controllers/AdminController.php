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
use Spatie\Permission\Models\Role;
use App\Models\StaffDetail;

class AdminController extends Controller
{
    public function index()
    {
        \Log::info('Admin dashboard hit', ['user_id' => auth()->id()]);
        $stats = [
            'users' => User::count(),
            'products' => Product::count(),
            'clients' => Client::count(),
            'tickets' => Ticket::count(),
            'open_tickets' => Ticket::where('status', 'open')->count(),
            'closed_tickets' => Ticket::where('status', 'closed')->count(),
            'categories' => \App\Models\ProductCategory::count(),
            'agents' => User::role('staff')->count(),
        ];

        // Stats for Charts
        $tickets_by_status = Ticket::select('status', \DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');

        $tickets_by_priority = Ticket::select('priority', \DB::raw('count(*) as count'))
            ->groupBy('priority')
            ->pluck('count', 'priority');

        $tickets_by_category = Ticket::join('product_categories', 'tickets.category_id', '=', 'product_categories.id')
            ->select('product_categories.name', \DB::raw('count(*) as count'))
            ->groupBy('product_categories.name')
            ->pluck('count', 'name');

        $tickets_by_agent = User::role('staff')
            ->withCount('assignedTickets')
            ->pluck('assigned_tickets_count', 'name');

        $tickets_this_year = Ticket::whereYear('created_at', date('Y'))
            ->select(\DB::raw('MONTH(created_at) as month'), \DB::raw('count(*) as count'))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month');
        
        $recent_tickets = Ticket::with(['user', 'product'])->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recent_tickets', 'tickets_by_status', 'tickets_by_priority', 'tickets_by_category', 'tickets_by_agent', 'tickets_this_year'));
    }

    public function users()
    {
        $users = User::with(['legacyRole', 'staff_detail.designation', 'staff_detail.position', 'client_detail.client'])->latest()->paginate(10);
        $roles = Role::all();
        $designations = \App\Models\Designation::where('status', true)->get();
        $clients = \App\Models\Client::where('status', true)->get();
        return view('admin.users', compact('users', 'roles', 'designations', 'clients'));
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

        $role = Role::findById($request->role_id);
        $roleName = strtolower($role->name);
        $isStaff = ($roleName == 'staff');
        $isUser = ($roleName == 'user');

        if ($isStaff) {
            $request->validate([
                'designation_id' => 'required|exists:designations,id',
                'position_id' => 'required|exists:positions,id',
            ]);
        }

        if ($isUser) {
            $request->validate([
                'client_id' => 'required|exists:clients,id',
            ]);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $request->role_id,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole($roleName);

        if ($isStaff) {
            $user->staff_detail()->create([
                'designation_id' => $request->designation_id,
                'position_id' => $request->position_id,
            ]);
        }

        if ($isUser) {
            $user->client_detail()->create([
                'client_id' => $request->client_id,
            ]);
        }

        return back()->with('success', 'User created successfully.');
    }

    public function editUser(User $user)
    {
        $user->load(['roles', 'staff_detail.designation', 'staff_detail.position', 'client_detail.client']);
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

        $role = Role::findById($request->role_id);
        $roleName = strtolower($role->name);
        $isStaff = ($roleName == 'staff');
        $isUser = ($roleName == 'user');

        if ($isStaff) {
            $request->validate([
                'designation_id' => 'required|exists:designations,id',
                'position_id' => 'required|exists:positions,id',
            ]);
        }

        if ($isUser) {
            $request->validate([
                'client_id' => 'required|exists:clients,id',
            ]);
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role_id' => $request->role_id,
            'status' => $request->status,
        ]);

        $user->syncRoles([$roleName]);

        if ($request->password) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        if ($isStaff) {
            $user->staff_detail()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'designation_id' => $request->designation_id,
                    'position_id' => $request->position_id,
                ]
            );
            $user->client_detail()->delete();
        } elseif ($isUser) {
            $user->client_detail()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'client_id' => $request->client_id,
                ]
            );
            $user->staff_detail()->delete();
        } else {
            $user->staff_detail()->delete();
            $user->client_detail()->delete();
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
