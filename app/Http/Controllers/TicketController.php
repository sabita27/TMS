<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductSubCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TicketController extends Controller
{
    // User Tickets
    public function userTickets()
    {
        $tickets = Ticket::where('user_id', Auth::id())->with(['product', 'assignedStaff'])->latest()->paginate(10);
        return view('user.tickets.index', compact('tickets'));
    }

    public function create()
    {
        $products = Product::where('status', 1)->get();
        $categories = ProductCategory::where('status', 1)->get();
        return view('user.tickets.create', compact('products', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'nullable|exists:products,id',
            'category_id' => 'required|exists:product_categories,id',
            'sub_category_id' => 'required|exists:product_sub_categories,id',
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx,zip|max:5120', // Max 5MB
        ]);

        $data = $request->all();
        
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('attachments', $filename, 'public');
            $data['attachment'] = $path;
        }

        $ticket = new Ticket($data);
        $ticket->ticket_id = 'TKT-' . strtoupper(Str::random(8));
        $ticket->user_id = Auth::id();
        $ticket->status = 'open';
        $ticket->save();

        return redirect()->route('user.tickets')->with('success', 'Ticket raised successfully. ID: ' . $ticket->ticket_id);
    }

    public function close(Ticket $ticket)
    {
        if ($ticket->user_id !== Auth::id()) {
            abort(403);
        }
        $ticket->update(['status' => 'closed', 'closed_at' => now()]);
        return back()->with('success', 'Ticket closed successfully.');
    }

    // Manager View
    public function managerTickets()
    {
        $tickets = Ticket::with(['user', 'product', 'assignedStaff'])->latest()->paginate(10);
        $staffMembers = User::whereHas('role', function ($query) {
            $query->where('name', 'staff');
        })->get();
        return view('manager.tickets.index', compact('tickets', 'staffMembers'));
    }

    public function assign(Request $request, Ticket $ticket)
    {
        $request->validate(['assigned_to' => 'required|exists:users,id']);
        $ticket->update([
            'assigned_to' => $request->assigned_to,
            'status' => 'in-progress'
        ]);
        return back()->with('success', 'Ticket assigned successfully.');
    }

    public function forward(Request $request, Ticket $ticket)
    {
        $request->validate(['forwarded_to' => 'required|exists:users,id']);
        $ticket->update([
            'forwarded_to' => $request->forwarded_to,
            'assigned_to' => $request->forwarded_to // Re-assign when forwarded
        ]);
        return back()->with('success', 'Ticket forwarded successfully.');
    }

    // Staff View
    public function updateStatus(Request $request, Ticket $ticket)
    {
        $request->validate(['status' => 'required|in:open,in-progress,resolved,closed']);
        $ticket->update(['status' => $request->status]);
        if ($request->status == 'closed') {
            $ticket->update(['closed_at' => now()]);
        }
        return back()->with('success', 'Ticket status updated.');
    }
}
