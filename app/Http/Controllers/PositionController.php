<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Position;
use App\Models\Designation;

class PositionController extends Controller
{
    public function index()
    {
        $positions = Position::with('designation')->latest()->paginate(10);
        $designations = Designation::where('status', true)->get();
        return view('admin.positions.index', compact('positions', 'designations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'designation_id' => 'required|exists:designations,id',
            'name' => 'required|string|max:255',
        ]);

        Position::create([
            'designation_id' => $request->designation_id,
            'name' => $request->name,
        ]);

        return back()->with('success', 'Position created successfully.');
    }

    public function edit(Position $position)
    {
        return response()->json($position);
    }

    public function update(Request $request, Position $position)
    {
        $request->validate([
            'designation_id' => 'required|exists:designations,id',
            'name' => 'required|string|max:255',
        ]);

        $position->update([
            'designation_id' => $request->designation_id,
            'name' => $request->name,
        ]);

        return back()->with('success', 'Position updated successfully.');
    }

    public function destroy(Position $position)
    {
        $position->delete();
        return back()->with('success', 'Position deleted successfully.');
    }
}
