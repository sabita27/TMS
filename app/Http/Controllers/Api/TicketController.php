<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class TicketController extends Controller
{
    /**
     * 📌 Raise Ticket
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json([
                'status'  => false,
                'message' => 'Unauthenticated',
            ], 401);
        }

        // ✅ VALIDATION
        $validator = Validator::make($request->all(), [
            'product_id'  => 'nullable|exists:products,id',
            'project_id'  => 'nullable|exists:projects,id',
            'service_id'  => 'nullable|exists:services,id',
            'subject'     => 'required|string|max:255',
            'description' => 'required|string',
            'priority'    => 'required|in:low,medium,high',
            'attachment'  => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:20480',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => $validator->errors()->first(),
                'errors'  => $validator->errors(),
            ], 422);
        }

        // ✅ REQUIRE AT LEAST ONE (VERY IMPORTANT)
        if (! $request->product_id && ! $request->project_id && ! $request->service_id) {
            return response()->json([
                'status'  => false,
                'message' => 'Select at least one: product, project or service',
            ], 422);
        }

        // ✅ CLEAN DATA (avoid sending invalid values)
        $data = [
            'ticket_id'   => 'TKT-' . strtoupper(Str::random(8)),
            'user_id'     => $user->id,
            'product_id'  => $request->filled('product_id') ? $request->product_id : null,
            'project_id'  => $request->filled('project_id') ? $request->project_id : null,
            'service_id'  => $request->filled('service_id') ? $request->service_id : null,
            'subject'     => $request->subject,
            'description' => $request->description,
            'priority'    => $request->priority,
            'status'      => 'open',
        ];

        // ✅ FILE UPLOAD
        if ($request->hasFile('attachment')) {
            $file               = $request->file('attachment');
            $filename           = time() . '_' . $file->getClientOriginalName();
            $data['attachment'] = $file->storeAs('attachments', $filename, 'public');
        }

        // ✅ CREATE
        $ticket = Ticket::create($data);

        return response()->json([
            'status'  => true,
            'message' => 'Ticket raised successfully',
            'data'    => $ticket,
        ]);
    }
    /**
     * 📌 My Tickets (User)
     */
    public function myTickets()
    {
        $user = Auth::user();

        $tickets = Ticket::where('user_id', $user->id)
            ->with(['product', 'project', 'service', 'assignedStaff'])
            ->latest()
            ->get();

        return response()->json([
            'status' => true,
            'data'   => $tickets,
        ]);
    }

    /**
     * 📌 View Single Ticket
     */
    public function show($id)
    {
        $ticket = Ticket::with([
            'product',
            'project',
            'service',
            'assignedStaff',
            'replies',
        ])->find($id);

        if (! $ticket) {
            return response()->json([
                'status'  => false,
                'message' => 'Ticket not found',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data'   => $ticket,
        ]);
    }

    /**
     * 📌 All Tickets (Admin / Manager)
     */
    public function index(Request $request)
    {
        $query = Ticket::with([
            'user:id,name',
            'assignedStaff:id,name',
            'product:id,name',
            'project:id,name',
            'service:id,name',
        ]);

        // ✅ FILTERS (optional but powerful)
        if ($request->priority) {
            $query->where('priority', $request->priority);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->assigned_to) {
            $query->where('assigned_to', $request->assigned_to);
        }

        // ✅ PAGINATION
        $tickets = $query->latest()->paginate(10);

        return response()->json([
            'status' => true,
            'data'   => $tickets,
        ]);
    }

    /**
 * 📌 All Conversations (Chat List)
 */
public function conversations(Request $request)
{
    $tickets = Ticket::with([
            'user:id,name',
            'assignedStaff:id,name',
            'replies' => function ($q) {
                $q->latest();
            }
        ])
        ->latest()
        ->get()
        ->map(function ($ticket) {

            $lastReply = $ticket->replies->first();

            return [
                'id' => $ticket->id,
                'ticket_id' => $ticket->ticket_id,
                'user_name' => $ticket->user->name ?? null,
                'subject' => $ticket->subject,

                'last_message' => $lastReply
                    ? $lastReply->replay
                    : $ticket->description,

                'last_message_by' => $lastReply
                    ? $lastReply->reply_by
                    : 'user',

                'status' => $ticket->status,

                'assigned_to' => $ticket->assignedStaff->name ?? null,

                'created_at' => $ticket->created_at->diffForHumans(),
                'closed_at' => $ticket->closed_at
                    ? $ticket->closed_at->diffForHumans()
                    : null,
            ];
        });

    return response()->json([
        'status' => true,
        'data'   => $tickets,
    ]);
}

/**
 * 📌 View Single Conversation (Full Chat)
 */
public function conversationDetail($id)
{
    $ticket = Ticket::with([
        'user:id,name',
        'assignedStaff:id,name',
        'product:id,name',
        'project:id,name',
        'service:id,name',
        'replies' => function ($q) {
            $q->orderBy('id', 'asc');
        },
        'replies.user:id,name',
        'replies.staff:id,name',
    ])->find($id);

    if (! $ticket) {
        return response()->json([
            'status' => false,
            'message' => 'Conversation not found'
        ], 404);
    }

    return response()->json([
        'status' => true,
        'data'   => $ticket
    ]);
}

/**
 * 📌 Delete Conversation (Ticket + Replies)
 */
public function deleteConversation($id)
{
    $user = Auth::user();

    // ✅ Only admin / manager allowed
    if (! $user || ! $user->hasAnyRole(['admin', 'manager'])) {
        return response()->json([
            'status' => false,
            'message' => 'Unauthorized'
        ], 403);
    }

    $ticket = Ticket::find($id);

    if (! $ticket) {
        return response()->json([
            'status' => false,
            'message' => 'Conversation not found'
        ], 404);
    }

    // ✅ Delete replies first (optional if cascade exists)
    $ticket->replies()->delete();

    // ✅ Delete ticket
    $ticket->delete();

    return response()->json([
        'status'  => true,
        'message' => 'Conversation deleted successfully'
    ]);
}

/**
 * 📌 Assigned Tickets (Staff)
 */
public function assignedTickets(Request $request)
{
    $user = Auth::user();

    if (! $user) {
        return response()->json([
            'status' => false,
            'message' => 'Unauthenticated'
        ], 401);
    }

    $query = Ticket::where('assigned_to', $user->id)
        ->with([
            'user:id,name',
            'product:id,name',
            'project:id,name',
            'service:id,name'
        ]);

    // ✅ OPTIONAL FILTERS
    if ($request->status) {
        $query->where('status', $request->status);
    }

    if ($request->priority) {
        $query->where('priority', $request->priority);
    }

    // ✅ PAGINATION (recommended)
    $tickets = $query->latest()->paginate(10);

    return response()->json([
        'status' => true,
        'count'  => $tickets->total(),
        'data'   => $tickets,
    ]);
}
}
