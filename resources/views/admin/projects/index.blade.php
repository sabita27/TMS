@extends('layouts.backend.master')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Project Master</h3>
        <button onclick="document.getElementById('addProjectModal').style.display='block'" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Project
        </button>
    </div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Project Name</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($projects as $project)
                <tr>
                    <td style="font-weight: 600;">{{ $project->name }}</td>
                    <td>
                        <span class="badge {{ $project->status ? 'badge-success' : 'badge-danger' }}">
                            {{ $project->status ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td style="color: var(--text-muted);">{{ $project->created_at->format('M d, Y') }}</td>
                    <td>
                        <div style="display: flex; gap: 0.5rem;">
                            <button onclick="editProject({{ $project->id }})" class="btn btn-primary" style="padding: 0.4rem 0.7rem; font-size: 0.75rem;">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <form action="{{ route('admin.projects.delete', $project->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this project?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="padding: 0.4rem 0.7rem; font-size: 0.75rem;">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div style="margin-top: 1rem;">
        {{ $projects->links() }}
    </div>
</div>

<!-- Add Project Modal -->
<div id="addProjectModal" style="display:none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);">
    <div style="background: white; width: 450px; margin: 10% auto; padding: 2.5rem; border-radius: 0.75rem; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);">
        <h3 style="margin-top:0; border-bottom: 1px solid #eee; padding-bottom: 1rem;">Add New Project</h3>
        <form action="{{ route('admin.projects.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Project Name</label>
                <input type="text" name="name" class="form-control" required placeholder="Enter project name">
            </div>
            <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                <button type="submit" class="btn btn-primary" style="flex:2;">Save Project</button>
                <button type="button" onclick="document.getElementById('addProjectModal').style.display='none'" class="btn" style="flex:1; background: #e5e7eb;">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Project Modal -->
<div id="editProjectModal" style="display:none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);">
    <div style="background: white; width: 450px; margin: 10% auto; padding: 2.5rem; border-radius: 0.75rem; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);">
        <h3 style="margin-top:0; border-bottom: 1px solid #eee; padding-bottom: 1rem;">Edit Project</h3>
        <form id="editProjectForm" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label class="form-label">Project Name</label>
                <input type="text" name="name" id="edit_project_name" class="form-control" required>
            </div>
            <div class="form-group">
                <label class="form-label">Status</label>
                <select name="status" id="edit_project_status" class="form-control">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
            <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                <button type="submit" class="btn btn-primary" style="flex:2;">Update Project</button>
                <button type="button" onclick="document.getElementById('editProjectModal').style.display='none'" class="btn" style="flex:1; background: #e5e7eb;">Cancel</button>
            </div>
        </form>
    </div>
</div>

@section('scripts')
<script>
    function editProject(id) {
        fetch(`/admin/projects/${id}/edit`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('edit_project_name').value = data.name;
                document.getElementById('edit_project_status').value = data.status;
                document.getElementById('editProjectForm').action = `/admin/projects/${id}`;
                document.getElementById('editProjectModal').style.display = 'block';
            });
    }
</script>
@endsection
@endsection
