<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use App\Models\Product;
use App\Models\Client;
use Illuminate\Http\Request;

class ManagerController extends Controller
{
    public function index()
    {
        $stats = [
            'users' => User::count(), // Manager might see total users count even if they can't manage them
            'products' => Product::count(),
            'clients' => Client::count(),
            'tickets' => Ticket::count(),
            'open_tickets' => Ticket::where('status', 'open')->count(),
            'closed_tickets' => Ticket::where('status', 'closed')->count(),
            'agents' => User::role('staff')->count(),
        ];

        // Stats for Charts (Same as Admin)
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

        return view('manager.dashboard', compact('stats', 'recent_tickets', 'tickets_by_status', 'tickets_by_priority', 'tickets_by_category', 'tickets_by_agent', 'tickets_this_year'));
    }
}
