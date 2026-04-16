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
}
