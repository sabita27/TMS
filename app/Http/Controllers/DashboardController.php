<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use App\Models\Client;
use App\Models\Ticket;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Define base stats structure
        $stats = [
            'users' => 0,
            'products' => 0,
            'clients' => 0,
            'tickets' => 0,
            'open_tickets' => 0,
            'closed_tickets' => 0,
            'agents' => 0,
        ];

        // Fetch Global Stats if permitted
        if ($user->can('manage tickets')) {
            $stats['users'] = User::count();
            $stats['products'] = Product::count();
            $stats['clients'] = Client::count();
            $stats['tickets'] = Ticket::count();
            $stats['open_tickets'] = Ticket::whereNotIn('status', ['resolved', 'closed', 'Resolved', 'Closed'])->count();
            $stats['closed_tickets'] = Ticket::whereIn('status', ['resolved', 'closed', 'Resolved', 'Closed'])->count();
            $stats['agents'] = User::role('staff')->count();
        } 
        // Fetch Limited Stats for Staff (Assigned to them)
        elseif ($user->can('edit tickets')) {
            $staffId = $user->id;
            $stats['tickets']         = Ticket::where('assigned_to', $staffId)->count(); // total assigned
            $stats['total_assigned']  = $stats['tickets'];
            $stats['open_tickets']    = Ticket::where('assigned_to', $staffId)
                                              ->whereNotIn('status', ['resolved', 'closed', 'Resolved', 'Closed'])
                                              ->count();
            $stats['closed_tickets']  = Ticket::where('assigned_to', $staffId)
                                              ->whereIn('status', ['resolved', 'closed', 'Resolved', 'Closed'])
                                              ->count();
        }
        // Fetch Limited Stats for Regular User (Their own tickets)
        else {
            $stats['tickets'] = Ticket::where('user_id', $user->id)->count();
            $stats['open_tickets'] = Ticket::where('user_id', $user->id)->whereNotIn('status', ['resolved', 'closed', 'Resolved', 'Closed'])->count();
            $stats['closed_tickets'] = Ticket::where('user_id', $user->id)->whereIn('status', ['resolved', 'closed', 'Resolved', 'Closed'])->count();
        }

        // Charts Data (Shared or Contextual)
        $tickets_by_status = collect();
        $tickets_by_priority = collect();
        $tickets_by_category = collect();
        $tickets_by_agent = collect();
        $tickets_this_year = collect();

        if ($user->can('manage tickets')) {
            $tickets_by_status = Ticket::select('status', DB::raw('count(*) as count'))->groupBy('status')->pluck('count', 'status');
            $tickets_by_priority = Ticket::select('priority', DB::raw('count(*) as count'))->groupBy('priority')->pluck('count', 'priority');
            $tickets_by_category = Ticket::whereNotNull('category_id')->join('product_categories', 'tickets.category_id', '=', 'product_categories.id')
                ->select('product_categories.name', DB::raw('count(*) as count'))->groupBy('product_categories.name')->pluck('count', 'name');
            $tickets_by_agent = User::role('staff')->withCount('assignedTickets')->pluck('assigned_tickets_count', 'name');
            $tickets_this_year = Ticket::whereYear('created_at', date('Y'))
                ->select(DB::raw('MONTH(created_at) as month'), DB::raw('count(*) as count'))->groupBy('month')->orderBy('month')->pluck('count', 'month');
        }

        // Recent Tickets (Contextual)
        $query = Ticket::with(['user', 'product']);
        if ($user->can('manage tickets')) {
            // No additional filter for high-level management
        } elseif ($user->can('edit tickets')) {
            $query->where('assigned_to', $user->id);
        } else {
            // Regular user or custom role: only show their own tickets
            $query->where('user_id', $user->id);
        }
        $recent_tickets = $query->latest()->take(5)->get();

        return view('dashboard.dashboard', compact(
            'stats', 
            'recent_tickets', 
            'tickets_by_status', 
            'tickets_by_priority', 
            'tickets_by_category', 
            'tickets_by_agent', 
            'tickets_this_year'
        ));
    }
}
