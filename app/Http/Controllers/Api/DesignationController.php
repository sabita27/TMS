<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Designation;
use Illuminate\Support\Facades\Validator;

class DesignationController extends Controller
{
    // 📌 Get all designations
    public function index()
    {
        $designations = Designation::latest()->get();

        return response()->json([
            'status' => true,
            'data' => $designations
        ]);
    }

    // 📌 Store new designation
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:designations,name|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()
            ], 422);
        }

        $designation = Designation::create([
            'name' => $request->name,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Designation created successfully',
            'data' => $designation
        ]);
    }

    // 📌 Show single designation
    public function show($id)
    {
        $designation = Designation::find($id);

        if (!$designation) {
            return response()->json([
                'status' => false,
                'message' => 'Designation not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $designation
        ]);
    }

    // 📌 Update designation
    public function update(Request $request, $id)
    {
        $designation = Designation::find($id);

        if (!$designation) {
            return response()->json([
                'status' => false,
                'message' => 'Designation not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:designations,name,' . $id . '|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()
            ], 422);
        }

        $designation->update([
            'name' => $request->name,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Designation updated successfully',
            'data' => $designation
        ]);
    }

    // 📌 Delete designation
    public function destroy($id)
    {
        $designation = Designation::find($id);

        if (!$designation) {
            return response()->json([
                'status' => false,
                'message' => 'Designation not found'
            ], 404);
        }

        $designation->delete();

        return response()->json([
            'status' => true,
            'message' => 'Designation deleted successfully'
        ]);
    }
}