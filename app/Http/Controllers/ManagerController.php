<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;

class ManagerController extends Controller
{
    public function index()
    {
        $stats = [
            'total_tickets' => Ticket::count(),
            'unassigned_tickets' => Ticket::whereNull('assigned_to')->count(),
            'in_progress' => Ticket::where('status', 'in-progress')->count(),
            'resolved' => Ticket::where('status', 'resolved')->count(),
        ];
        
        $recent_tickets = Ticket::with(['user', 'assignedStaff'])->latest()->take(5)->get();
        return view('manager.dashboard', compact('stats', 'recent_tickets'));
    }
}
