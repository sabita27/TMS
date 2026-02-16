<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Designation;

class DesignationController extends Controller
{
    public function index()
    {
        $designations = Designation::latest()->paginate(10);
        return view('admin.designations.index', compact('designations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:designations,name|max:255',
        ]);

        Designation::create([
            'name' => $request->name,
        ]);

        return back()->with('success', 'Designation created successfully.');
    }

    public function edit(Designation $designation)
    {
        return response()->json($designation);
    }

    public function update(Request $request, Designation $designation)
    {
        $request->validate([
            'name' => 'required|unique:designations,name,' . $designation->id . '|max:255',
        ]);

        $designation->update([
            'name' => $request->name,
        ]);

        return back()->with('success', 'Designation updated successfully.');
    }

    public function destroy(Designation $designation)
    {
        $designation->delete();
        return back()->with('success', 'Designation deleted successfully.');
    }
}
