<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Position;
use Illuminate\Support\Facades\Validator;

class PositionController extends Controller
{
    // 📌 Get all positions (with designation)
    public function index()
    {
        $positions = Position::with('designation')->latest()->get();

        return response()->json([
            'status' => true,
            'data' => $positions
        ]);
    }

    // 📌 Store new position
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'designation_id' => 'required|exists:designations,id',
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()
            ], 422);
        }

        $position = Position::create([
            'designation_id' => $request->designation_id,
            'name' => $request->name,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Position created successfully',
            'data' => $position
        ]);
    }

    // 📌 Show single position
    public function show($id)
    {
        $position = Position::with('designation')->find($id);

        if (!$position) {
            return response()->json([
                'status' => false,
                'message' => 'Position not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $position
        ]);
    }

    // 📌 Update position
    public function update(Request $request, $id)
    {
        $position = Position::find($id);

        if (!$position) {
            return response()->json([
                'status' => false,
                'message' => 'Position not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'designation_id' => 'required|exists:designations,id',
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()
            ], 422);
        }

        $position->update([
            'designation_id' => $request->designation_id,
            'name' => $request->name,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Position updated successfully',
            'data' => $position
        ]);
    }

    // 📌 Delete position
    public function destroy($id)
    {
        $position = Position::find($id);

        if (!$position) {
            return response()->json([
                'status' => false,
                'message' => 'Position not found'
            ], 404);
        }

        $position->delete();

        return response()->json([
            'status' => true,
            'message' => 'Position deleted successfully'
        ]);
    }
}