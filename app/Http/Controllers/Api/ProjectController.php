<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\TicketPriority;
use App\Models\TicketStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    /**
     * 📋 GET ALL PROJECTS
     */
    public function index()
    {
        $projects = Project::with(['category', 'subcategory', 'projectStatus', 'priority'])
            ->latest()
            ->get();

        return response()->json([
            'status' => true,
            'data'   => $projects,
        ]);
    }

    /**
     * ➕ CREATE PROJECT
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'           => 'required|string|max:255|unique:projects',

            'category_id'    => 'nullable|exists:product_categories,id',
            'subcategory_id' => 'nullable|exists:product_sub_categories,id',

            'status_id'      => 'nullable|exists:ticket_statuses,id',
            'priority_id'    => 'nullable|exists:ticket_priorities,id',

            'start_date'     => 'nullable|date',
            'end_date'       => 'nullable|date',

            'description'    => 'nullable|string',
            'remarks'        => 'nullable|string',

            'attachment'     => 'nullable|file|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => $validator->errors()->first(),
                'errors'  => $validator->errors(),
            ], 422);
        }

        $data = $request->except('attachment');

        // ✅ FILE UPLOAD
        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')->store('projects', 'public');
        }

        // ✅ DEFAULT STATUS TRUE
        $data['status'] = 1;

        $project = Project::create($data);

        return response()->json([
            'status'  => true,
            'message' => 'Project created successfully',
            'data'    => $project,
        ], 201);
    }

    /**
     * 🔍 GET SINGLE PROJECT
     */
    public function show($id)
    {
        $project = Project::with(['category', 'subcategory', 'projectStatus', 'priority'])->find($id);

        if (! $project) {
            return response()->json([
                'status'  => false,
                'message' => 'Project not found',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data'   => $project,
        ]);
    }

    /**
     * ✏️ UPDATE PROJECT
     */
    public function update(Request $request, $id)
    {
        $project = Project::find($id);

        if (! $project) {
            return response()->json([
                'status'  => false,
                'message' => 'Project not found',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name'           => 'nullable|string|max:255|unique:projects,name,' . $id,
            'category_id'    => 'nullable|exists:product_categories,id',
            'subcategory_id' => 'nullable|exists:product_sub_categories,id',
            'status_id'      => 'nullable|exists:ticket_statuses,id',
            'priority_id'    => 'nullable|exists:ticket_priorities,id',
            'start_date'     => 'nullable|date',
            'end_date'       => 'nullable|date',
            'description'    => 'nullable|string',
            'remarks'        => 'nullable|string',
            'attachment'     => 'nullable|file|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        // ✅ ONLY TAKE REQUIRED FIELDS
        $data = $request->only([
            'name',
            'category_id',
            'subcategory_id',
            'status_id',
            'priority_id',
            'start_date',
            'end_date',
            'description',
            'remarks',
            'status',
        ]);

        // ✅ HANDLE FILE
        if ($request->hasFile('attachment')) {

            if ($project->attachment && Storage::disk('public')->exists($project->attachment)) {
                Storage::disk('public')->delete($project->attachment);
            }

            $data['attachment'] = $request->file('attachment')->store('projects', 'public');
        }

        // ✅ UPDATE ONLY IF DATA EXISTS
        if (! empty($data)) {
            $project->update($data);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Project updated successfully',
            'data'    => $project->fresh(),
        ]);
    }

    /**
     * ❌ DELETE PROJECT
     */
    public function destroy($id)
    {
        $project = Project::find($id);

        if (! $project) {
            return response()->json([
                'status'  => false,
                'message' => 'Project not found',
            ], 404);
        }

        // delete file
        if ($project->attachment && Storage::disk('public')->exists($project->attachment)) {
            Storage::disk('public')->delete($project->attachment);
        }

        $project->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Project deleted successfully',
        ]);
    }
    public function getStatuses()
    {
        $statuses = TicketStatus::where('status', 1)->get();

        return response()->json([
            'status' => true,
            'data'   => $statuses,
        ]);
    }
    public function getPriorities()
    {
        $priorities = TicketPriority::where('status', 1)->get();

        return response()->json([
            'status' => true,
            'data'   => $priorities,
        ]);
    }

}
