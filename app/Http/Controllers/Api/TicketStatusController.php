<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TicketStatus;
use Illuminate\Support\Facades\Validator;

class TicketStatusController extends Controller
{
    // 📌 List all statuses
    public function index()
    {
        $statuses = TicketStatus::latest()->get();

        return response()->json([
            'status' => true,
            'data' => $statuses
        ]);
    }

    // 📌 Store new status
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'  => 'required|string|unique:ticket_statuses,name',
            'color' => 'required|string|max:7',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $status = TicketStatus::create([
            'name' => $request->name,
            'color' => $request->color,
            'status' => true
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Status created successfully',
            'data' => $status
        ]);
    }

    // 📌 Show single status
    public function show($id)
    {
        $status = TicketStatus::find($id);

        if (!$status) {
            return response()->json([
                'status' => false,
                'message' => 'Status not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $status
        ]);
    }

    // 📌 Update status
    public function update(Request $request, $id)
    {
        $status = TicketStatus::find($id);

        if (!$status) {
            return response()->json([
                'status' => false,
                'message' => 'Status not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name'   => 'nullable|string|unique:ticket_statuses,name,' . $id,
            'color'  => 'nullable|string|max:7',
            'status' => 'nullable|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $status->update($request->only(['name', 'color', 'status']));

        return response()->json([
            'status' => true,
            'message' => 'Status updated successfully',
            'data' => $status
        ]);
    }

    // 📌 Delete status
    public function destroy($id)
    {
        $status = TicketStatus::find($id);

        if (!$status) {
            return response()->json([
                'status' => false,
                'message' => 'Status not found'
            ], 404);
        }

        $status->delete();

        return response()->json([
            'status' => true,
            'message' => 'Status deleted successfully'
        ]);
    }
}
