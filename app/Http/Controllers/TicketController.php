<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Product;
use App\Models\Project;
use App\Models\Service;
use App\Models\TicketStatus;
use App\Models\User;
use App\Models\TicketReply;
use App\Notifications\TicketCreatedNotification;
use App\Notifications\TicketAssignedNotification;
use App\Notifications\TicketStatusNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TicketController extends Controller
{
    // Staff: All Assigned Tickets
    public function staffAssignedTickets()
    {
        $tickets = Ticket::where('assigned_to', Auth::id())
            ->with(['user', 'product', 'project', 'service'])
            ->latest()
            ->paginate(15);
        $ticketStatuses = TicketStatus::where('status', 'active')->get();
        return view('staff.assigned_tickets', compact('tickets', 'ticketStatuses'));
    }

    // User Tickets
    public function userTickets()
    {
        $tickets = Ticket::where('user_id', Auth::id())->with(['product', 'project', 'service', 'assignedStaff'])->latest()->paginate(10);
        return view('user.tickets.index', compact('tickets'));
    }

    public function create()
    {
        $user = Auth::user();
        $client = $user->client_detail ? $user->client_detail->client : null;

        if ($client) {
            // Products mapped to client
            $productIds = is_array($client->product_id) ? $client->product_id : [];
            $products = Product::whereIn('id', $productIds)->where('status', 1)->get();
            
            // Projects mapped to client
            $projectIds = is_array($client->project_id) ? $client->project_id : [];
            $projects = Project::whereIn('id', $projectIds)->get();
            
            // Services mapped to client
            $services = $client->services()->with('service')->get()->pluck('service')->filter();
        } else {
            // Fallback for staff/admin if they access the create page
            $products = Product::where('status', 1)->get();
            $projects = Project::all();
            $services = Service::all();
        }

        return view('user.tickets.create', compact('products', 'projects', 'services'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'nullable|exists:products,id',
            'project_id' => 'nullable|exists:projects,id',
            'service_id' => 'nullable|exists:services,id',
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx,zip|max:20480', // Max 20MB
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

        // Notify Admins and Managers (excluding the sender)
        $adminsManagers = User::role(['admin', 'manager'])->get()->reject(function($u) {
            return $u->id === Auth::id();
        });
        
        if ($adminsManagers->count() > 0) {
            \Illuminate\Support\Facades\Notification::send($adminsManagers, new TicketCreatedNotification($ticket));
        }

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

    public function managerTickets(Request $request)
    {
        if ($request->ajax()) {
            $tickets = Ticket::with(['user', 'product', 'project', 'service', 'assignedStaff'])->latest();
            $staffMembers = User::role('staff')->get();

            return \Yajra\DataTables\Facades\DataTables::of($tickets)
                ->addIndexColumn()
                ->editColumn('ticket_id', function($row){
                    return '<a href="'.route('ticket.show', $row->id).'" style="color: #3b82f6; font-weight: 700; text-decoration: none;">#'.e($row->ticket_id).'</a>';
                })
                ->addColumn('customer', function($row){
                    return e($row->user->name ?? '');
                })
                ->editColumn('subject', function($row){
                    return e($row->subject);
                })
                ->editColumn('priority', function($row){
                    $badgeClass = $row->priority == 'high' ? 'badge-danger' : 'badge-warning';
                    return '<span class="badge '.$badgeClass.'">'.ucfirst($row->priority).'</span>';
                })
                ->editColumn('status', function($row){
                    $statusColors = [
                        'open' => 'badge-danger',
                        'in-progress' => 'badge-warning',
                        'resolved' => 'badge-success',
                        'closed' => 'badge-secondary'
                    ];
                    $badgeClass = $statusColors[strtolower($row->status)] ?? 'badge-info';
                    return '<span class="badge '.$badgeClass.'">'.ucfirst($row->status).'</span>';
                })
                ->addColumn('assign_to_staff', function($row) use ($staffMembers){
                    $html = '<div style="display: flex; gap: 0.5rem;">';
                    $html .= '<form action="'.route('manager.tickets.assign', $row->id).'" method="POST" style="display:flex; gap:0.25rem;">';
                    $html .= csrf_field();
                    $html .= '<select name="assigned_to" class="form-control" style="padding: 0.25rem; font-size: 0.75rem; width: 120px;">';
                    $html .= '<option value="">Select Staff</option>';
                    foreach($staffMembers as $staff) {
                        $selected = $row->assigned_to == $staff->id ? 'selected' : '';
                        $html .= '<option value="'.$staff->id.'" '.$selected.'>'.e($staff->name).'</option>';
                    }
                    $html .= '</select>';
                    $html .= '<button type="submit" class="btn btn-primary" title="Assign" style="padding: 0.25rem 0.5rem; background: #3b82f6;"><i class="fas fa-user-check"></i></button>';
                    $html .= '</form></div>';
                    return $html;
                })
                ->rawColumns(['ticket_id', 'priority', 'status', 'assign_to_staff'])
                ->make(true);
        }

        $staffMembers = User::role('staff')->get();
        return view('auth.tickets', compact('staffMembers'));
    }

    public function allConversations(Request $request)
    {
        if ($request->ajax()) {
            $query = Ticket::with(['user', 'assignedStaff', 'replies' => function($q) {
                $q->latest();
            }])->latest();
            
            return \Yajra\DataTables\Facades\DataTables::of($query)
                ->addColumn('card', function($ticket) {
                    $lastReply = $ticket->replies->first();
                    $lastMsg = $lastReply ? $lastReply->replay : $ticket->description;
                    $lastMsgSender = $lastReply ? ($lastReply->reply_by == 'staff' ? 'Staff: ' : 'User: ') : 'User: ';
                    $isSolved = in_array(strtolower($ticket->status), ['resolved', 'closed']);
                    
                    $colors = ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899'];
                    $avatarColor = $colors[$ticket->id % count($colors)];

                    $route = route('ticket.show', $ticket->id);
                    $deleteRoute = route('manager.tickets.delete', $ticket->id);
                    $csrf = csrf_field();
                    $method = method_field('DELETE');

                    $timeDiff = $ticket->created_at->diffForHumans();
                    $userName = e($ticket->user->name);
                    $ticketId = e($ticket->ticket_id);
                    $subject = e($ticket->subject);
                    $msgPreview = \Illuminate\Support\Str::limit(strip_tags($lastMsg), 100);

                    $solvedStatusHtml = $isSolved ? '<i class="fas fa-check-circle"></i> Solved' : '<i class="fas fa-clock"></i> Active';
                    $solvedBadgeClass = $isSolved ? 'solved-badge' : 'unsolved-badge';

                    $resolvedExtra = '';
                    if($isSolved && $ticket->closed_at) {
                        $resolvedExtra = '<div style="font-size: 0.65rem; color: #15803d; font-weight: 700; text-transform: uppercase;">Resolved: '.$ticket->closed_at->diffForHumans().'</div>';
                    }

                    $assignInfo = '';
                    if($ticket->assigned_to) {
                        $assignInfo = '<div style="font-size: 0.65rem; color: #94a3b8; font-weight: 700; text-transform: uppercase;">Resource: <span style="color: #64748b;">'.e($ticket->assignedStaff->name ?? 'Unknown').'</span></div>';
                    } else {
                        $assignInfo = '<div style="font-size: 0.65rem; color: #ef4444; font-weight: 700; text-transform: uppercase;">Unassigned</div>';
                    }

                    return '
                    <div class="conversation-wrapper" style="border-bottom: 1px solid #f1f5f9;">
                        <a href="'.$route.'" class="conversation-item">
                            <div class="user-avatar-init" style="background: '.$avatarColor.';">
                                '.strtoupper(substr($userName, 0, 1)).'
                            </div>
                            
                            <div class="convo-content">
                                <div class="convo-header">
                                    <h4 class="convo-name">'.$userName.' <span style="color: #cbd5e1; font-weight: 400; font-size: 0.8rem; margin-left: 0.5rem;">#'.$ticketId.'</span></h4>
                                    <span class="convo-meta">'.$timeDiff.'</span>
                                </div>
                                
                                <div class="convo-subject">'.$subject.'</div>
                                
                                <p class="convo-last-msg">
                                    <span style="font-weight: 700; color: #475569;">'.$lastMsgSender.'</span>
                                    '.$msgPreview.'
                                </p>
                            </div>

                            <div class="convo-status-area">
                                <span class="status-pill '.$solvedBadgeClass.'">
                                    '.$solvedStatusHtml.'
                                </span>
                                '.$resolvedExtra.'
                                '.$assignInfo.'
                            </div>
                        </a>
                        
                        <div class="convo-actions">
                            <form action="'.$deleteRoute.'" method="POST" onsubmit="return confirm(\'Are you sure you want to permanently delete this ticket and all its conversations?\');">
                                '.$csrf.'
                                '.$method.'
                                <button type="submit" style="background: #fee2e2; color: #ef4444; border: 1px solid #fecaca; width: 40px; height: 40px; border-radius: 0.75rem; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: 0.2s;" onmouseover="this.style.background=\'#ef4444\'; this.style.color=\'white\';" onmouseout="this.style.background=\'#fee2e2\'; this.style.color=\'#ef4444\';" title="Delete Ticket">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </div>';
                })
                ->filterColumn('card', function($query, $keyword) {
                    $query->where('ticket_id', 'LIKE', "%{$keyword}%")
                          ->orWhere('subject', 'LIKE', "%{$keyword}%")
                          ->orWhereHas('user', function($q) use($keyword) {
                              $q->where('name', 'LIKE', "%{$keyword}%");
                          });
                })
                ->rawColumns(['card'])
                ->make(true);
        }

        return view('auth.conversations');
    }

    public function assign(Request $request, Ticket $ticket)
    {
        $request->validate(['assigned_to' => 'required|exists:users,id']);
        $ticket->update([
            'assigned_to' => $request->assigned_to,
            'status' => 'in-progress'
        ]);

        // Notify assigned staff
        $staff = User::find($request->assigned_to);
        if ($staff) {
            $staff->notify(new TicketAssignedNotification($ticket));
        }

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
        $request->validate(['status' => 'required|string']);
        $ticket->update(['status' => $request->status]);
        
        // Notify the user
        if ($ticket->user) {
            $ticket->user->notify(new TicketStatusNotification($ticket));
        }
        
        // Check if the new status is 'closed' or 'resolved'
        if (in_array(strtolower($request->status), ['closed', 'resolved'])) {
            $ticket->update(['closed_at' => now()]);
        }
        return back()->with('success', 'Ticket status updated to ' . $request->status);
    }

    public function solve(Ticket $ticket)
    {
        // Simple authorization check
        $user = Auth::user();
        if (!$user->hasAnyRole(['staff', 'admin', 'manager']) || ($user->hasRole('staff') && $ticket->assigned_to !== $user->id)) {
            abort(403);
        }

        // Look for 'Resolved' status in the database, fallback to 'resolved' string
        $resolvedStatus = TicketStatus::where('name', 'LIKE', 'Resolved%')->first();
        $newStatusName = $resolvedStatus ? $resolvedStatus->name : 'Resolved';

        $ticket->update([
            'status' => $newStatusName,
            'closed_at' => now()
        ]);

        // Notify the user
        if ($ticket->user) {
            $ticket->user->notify(new TicketStatusNotification($ticket));
        }

        return back()->with('success', 'Ticket marked as Solved!');
    }

    // View Ticket Details
    public function show(Ticket $ticket)
    {
        // Simple authorization check
        $user = Auth::user();
        
        // Admins and Managers can always see any ticket
        if (!$user->hasAnyRole(['admin', 'manager'])) {
            // Standard User ownership check
            if ($user->hasRole('user') && $ticket->user_id !== $user->id) {
                abort(403);
            }
            // Staff assignment check
            if ($user->hasRole('staff') && $ticket->assigned_to !== $user->id) {
                abort(403);
            }
        }
        $ticket->load(['user', 'product', 'project', 'service', 'assignedStaff', 'replies' => function($query) {
            $query->orderBy('id', 'desc');
        }, 'replies.user', 'replies.staff']);
        $staffMembers = User::role('staff')->get();
        $ticketStatuses = TicketStatus::where('status', 'active')->get();
        
        // If no statuses in DB, use default set
        if($ticketStatuses->isEmpty()) {
            $ticketStatuses = collect([
                (object)['name' => 'Open', 'color' => '#ef4444'],
                (object)['name' => 'In-Progress', 'color' => '#f59e0b'],
                (object)['name' => 'Resolved', 'color' => '#10b981'],
                (object)['name' => 'Closed', 'color' => '#64748b'],
                (object)['name' => 'On Hold', 'color' => '#8b5cf6'],
            ]);
        }

        return view('auth.ticket_show', compact('ticket', 'staffMembers', 'ticketStatuses'));
    }

    public function reply(Request $request, Ticket $ticket)
    {
        $request->validate([
            'replay' => 'nullable|required_without:attachment|string',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx,zip|max:20480',
        ]);

        $user = Auth::user();
        $isStaffOrAdmin = $user->hasAnyRole(['admin', 'manager', 'staff']);

        $data = [
            'ticket_id' => $ticket->id,
            'replay' => $request->replay,
            'user_id' => $ticket->user_id,
            'staff_id' => $isStaffOrAdmin ? $user->id : $ticket->assigned_to,
            'reply_by' => $isStaffOrAdmin ? 'staff' : 'user',
        ];

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filename = time() . '_reply_' . $file->getClientOriginalName();
            $path = $file->storeAs('reply_attachments', $filename, 'public');
            $data['attachment'] = $path;
        }

        TicketReply::create($data);

        // Truncate message for notification snippet
        $messageSnippet = \Illuminate\Support\Str::limit($request->replay, 50);

        // Logic-based Notifications
        $recipients = collect();

        if ($isStaffOrAdmin) {
            // 1. Always notify the ticket owner (User)
            if ($ticket->user && $ticket->user_id !== $user->id) {
                $recipients->push($ticket->user);
            }
            
            // 2. Notify assigned staff (if someone else replied, like an admin)
            if ($ticket->assigned_to && $ticket->assigned_to !== $user->id) {
                $assignedStaff = User::find($ticket->assigned_to);
                if ($assignedStaff) $recipients->push($assignedStaff);
            }
        } else {
            // 3. User replied: Notify assigned staff
            if ($ticket->assigned_to) {
                $assignedStaff = User::find($ticket->assigned_to);
                if ($assignedStaff) $recipients->push($assignedStaff);
            } else {
                // 4. Unassigned: Notify Admins ONLY (Managers don't need noise yet)
                $admins = User::role('admin')->get();
                foreach($admins as $admin) {
                    $recipients->push($admin);
                }
            }
        }

        // Send unique notifications (exclude sender)
        $uniqueRecipients = $recipients->unique('id')->reject(function ($u) use ($user) {
            return $u->id === $user->id;
        });

        if ($uniqueRecipients->count() > 0) {
            \Illuminate\Support\Facades\Notification::send($uniqueRecipients, new \App\Notifications\TicketReplyNotification($ticket, $user->name, $messageSnippet));
        }

        return back()->with('success', 'Reply added successfully.');
    }

    public function destroy(Ticket $ticket)
    {
        // Authorization check - only admin and manager can delete tickets
        if (!Auth::user()->hasAnyRole(['admin', 'manager'])) {
            abort(403);
        }

        $ticket->delete();
        return back()->with('success', 'Ticket and conversation deleted successfully.');
    }
}
