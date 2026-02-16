<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::latest()->paginate(10);
        return view('admin.projects.index', compact('projects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:projects',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'description' => 'nullable|string',
            'attachment' => 'nullable|file|max:2048',
            'remarks' => 'nullable|string',
        ]);

        $data = $request->except('attachment');
        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')->store('projects', 'public');
        }
        $data['status'] = true;

        Project::create($data);

        return back()->with('success', 'Project created successfully.');
    }

    public function edit(Project $project)
    {
        return response()->json($project);
    }

    public function update(Request $request, Project $project)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:projects,name,' . $project->id,
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'description' => 'nullable|string',
            'attachment' => 'nullable|file|max:2048',
            'remarks' => 'nullable|string',
        ]);

        $data = $request->except('attachment');
        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')->store('projects', 'public');
        }
        $data['status'] = $request->status;

        $project->update($data);

        return back()->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return back()->with('success', 'Project deleted successfully.');
    }
}
