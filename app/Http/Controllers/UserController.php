<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
  

class UserController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $stats = [
            'total_tickets'    => Ticket::where('user_id', $userId)->count(),
            'open_tickets'     => Ticket::where('user_id', $userId)->where('status', 'open')->count(),
            'resolved_tickets' => Ticket::where('user_id', $userId)->where('status', 'resolved')->count(),
        ];

        $recent_tickets = Ticket::where('user_id', $userId)
            ->latest()
            ->take(5)
            ->get();

        return view('user.dashboard', compact('stats', 'recent_tickets'));
    }

    public function products()
    {
        $products = Product::where('status', 1)
            ->with(['category', 'subCategory'])
            ->paginate(12);

        return view('auth.products', compact('products'));
    }

    public function profile()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        return view('auth.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user) {
            return back()->withErrors(['error' => 'Unauthorized']);
        }

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:15',
        ]);

        // ✅ Using update is fine here
        $user->update($request->only('name', 'email', 'phone'));

        return back()->with('success', 'Profile updated successfully!');
    }

    public function updatePassword(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user) {
            return back()->withErrors(['error' => 'Unauthorized']);
        }

        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'Current password does not match.'
            ]);
        }

        // ✅ FIXED (no red underline)
        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'Password changed successfully!');
    }
}