<?php

namespace App\Http\Controllers;

use App\Models\Designation;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffController extends Controller
{
    public function index()
    {
        $assigned_tickets = Ticket::where('assigned_to', Auth::id())
            ->with(['user', 'product'])
            ->latest()
            ->paginate(10);
            
        return view('staff.dashboard', compact('assigned_tickets'));
    }

    public function designation()
    {
        $designations = Designation::where('status', 1)->get();
        return view('staff.designation', compact('designations'));
    }
}
