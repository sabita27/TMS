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
        $stats = [
            'total_tickets' => Ticket::where('user_id', Auth::id())->count(),
            'open_tickets' => Ticket::where('user_id', Auth::id())->where('status', 'open')->count(),
            'resolved_tickets' => Ticket::where('user_id', Auth::id())->where('status', 'resolved')->count(),
        ];
        $recent_tickets = Ticket::where('user_id', Auth::id())->latest()->take(5)->get();
        return view('user.dashboard', compact('stats', 'recent_tickets'));
    }

    public function products()
    {
        $products = Product::where('status', 1)->with(['category', 'subCategory'])->paginate(12);
        return view('auth.products', compact('products'));
    }

    public function profile()
    {
        $user = Auth::user();
        return view('auth.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:15',
        ]);

        $user->update($request->only('name', 'email', 'phone'));
        return back()->with('success', 'Profile updated successfully!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password does not match.']);
        }

        $user->update(['password' => Hash::make($request->password)]);
        return back()->with('success', 'Password changed successfully!');
    }
}
