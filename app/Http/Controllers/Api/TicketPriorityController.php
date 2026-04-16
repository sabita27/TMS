<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TicketPriority;
use Illuminate\Support\Facades\Validator;

class TicketPriorityController extends Controller
{
    // 📌 List all priorities
    public function index()
    {
        $priorities = TicketPriority::latest()->get();

        return response()->json([
            'status' => true,
            'data' => $priorities
        ]);
    }

    // 📌 Store new priority
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'  => 'required|string|unique:ticket_priorities,name',
            'color' => 'required|string|max:7',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $priority = TicketPriority::create([
            'name' => $request->name,
            'color' => $request->color,
            'status' => true
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Priority created successfully',
            'data' => $priority
        ]);
    }

    // 📌 Show single priority
    public function show($id)
    {
        $priority = TicketPriority::find($id);

        if (!$priority) {
            return response()->json([
                'status' => false,
                'message' => 'Priority not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $priority
        ]);
    }

    // 📌 Update priority
    public function update(Request $request, $id)
    {
        $priority = TicketPriority::find($id);

        if (!$priority) {
            return response()->json([
                'status' => false,
                'message' => 'Priority not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name'  => 'nullable|string|unique:ticket_priorities,name,' . $id,
            'color' => 'nullable|string|max:7',
            'status'=> 'nullable|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $priority->update($request->only(['name', 'color', 'status']));

        return response()->json([
            'status' => true,
            'message' => 'Priority updated successfully',
            'data' => $priority
        ]);
    }

    // 📌 Delete priority
    public function destroy($id)
    {
        $priority = TicketPriority::find($id);

        if (!$priority) {
            return response()->json([
                'status' => false,
                'message' => 'Priority not found'
            ], 404);
        }

        $priority->delete();

        return response()->json([
            'status' => true,
            'message' => 'Priority deleted successfully'
        ]);
    }
}
