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
        $staffId = Auth::id();
        $assigned_tickets = Ticket::where('assigned_to', $staffId)
            ->with(['user', 'product'])
            ->latest()
            ->paginate(10);
            
        $stats = [
            'total_assigned' => Ticket::where('assigned_to', $staffId)->count(),
            'open_tickets' => Ticket::where('assigned_to', $staffId)->whereIn('status', ['open', 'in-progress'])->count(),
            'resolved_tickets' => Ticket::where('assigned_to', $staffId)->where('status', 'resolved')->count(),
        ];
            
        return view('staff.dashboard', compact('assigned_tickets', 'stats'));
    }

    public function designation()
    {
        $designations = Designation::where('status', 1)->get();
        return view('staff.designation', compact('designations'));
    }
}
