@extends('layouts.backend.master')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Project Master</h3>
        <button onclick="openAddModal()" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Project
        </button>
    </div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Project Name</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($projects as $project)
                <tr>
                    <td style="font-weight: 600;">{{ $project->name }}</td>
                    <td>{{ $project->start_date ? \Carbon\Carbon::parse($project->start_date)->format('M d, Y') : '-' }}</td>
                    <td>{{ $project->end_date ? \Carbon\Carbon::parse($project->end_date)->format('M d, Y') : '-' }}</td>
                    <td>
                        <span class="badge {{ $project->status ? 'badge-success' : 'badge-danger' }}">
                            {{ $project->status ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
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
<div id="addProjectModal" style="display:none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); overflow-y: auto;">
    <div style="background: white; width: 800px; margin: 2rem auto; padding: 2rem; border-radius: 0.75rem; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);">
        <h3 style="margin-top:0; border-bottom: 1px solid #eee; padding-bottom: 1rem;">Add New Project</h3>
        <form action="{{ route('admin.projects.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group" style="grid-column: span 2;">
                    <label class="form-label">Project Name</label>
                    <input type="text" name="name" class="form-control" required placeholder="Enter project name">
                </div>
                <div class="form-group">
                    <label class="form-label">Project Start Date</label>
                    <input type="date" name="start_date" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">Project End Date</label>
                    <input type="date" name="end_date" class="form-control">
                </div>
                <div class="form-group" style="grid-column: span 2;">
                    <label class="form-label">Project Description</label>
                    <textarea name="description" id="add_project_description" class="form-control"></textarea>
                </div>
                <div class="form-group" style="grid-column: span 2;">
                    <label class="form-label">Attachment</label>
                    <input type="file" name="attachment" class="form-control">
                </div>
               
                <div class="form-group" style="grid-column: span 2;">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
            </div>
            <div style="display: flex; gap: 1rem; margin-top: 2rem; border-top: 1px solid #eee; padding-top: 1.5rem;">
                <button type="submit" class="btn btn-primary" style="flex:1;">Save Project</button>
                <button type="button" onclick="closeAddModal()" class="btn" style="flex:1; background: #e5e7eb;">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Project Modal -->
<div id="editProjectModal" style="display:none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); overflow-y: auto;">
    <div style="background: white; width: 800px; margin: 2rem auto; padding: 2rem; border-radius: 0.75rem; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);">
        <h3 style="margin-top:0; border-bottom: 1px solid #eee; padding-bottom: 1rem;">Edit Project</h3>
        <form id="editProjectForm" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group" style="grid-column: span 2;">
                    <label class="form-label">Project Name</label>
                    <input type="text" name="name" id="edit_project_name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Project Start Date</label>
                    <input type="date" name="start_date" id="edit_start_date" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">Project End Date</label>
                    <input type="date" name="end_date" id="edit_end_date" class="form-control">
                </div>
                <div class="form-group" style="grid-column: span 2;">
                    <label class="form-label">Project Description</label>
                    <textarea name="description" id="edit_project_description" class="form-control"></textarea>
                </div>
                <div class="form-group" style="grid-column: span 2;">
                    <label class="form-label">Attachment</label>
                    <input type="file" name="attachment" class="form-control">
                    <div id="edit_attachment_preview" style="margin-top: 0.5rem; font-size: 0.8rem; color: var(--text-muted);"></div>
                </div>
                <div class="form-group" style="grid-column: span 2;">
                    <label class="form-label">Remarks</label>
                    <textarea name="remarks" id="edit_remarks" class="form-control" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" id="edit_project_status" class="form-control">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
            </div>
            <div style="display: flex; gap: 1rem; margin-top: 2rem; border-top: 1px solid #eee; padding-top: 1.5rem;">
                <button type="submit" class="btn btn-primary" style="flex:1;">Update Project</button>
                <button type="button" onclick="closeEditModal()" class="btn" style="flex:1; background: #e5e7eb;">Cancel</button>
            </div>
        </form>
    </div>
</div>

@section('styles')
<style>
    .form-label {
        color: #334155;
        font-weight: 600;
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }
</style>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
<script>
    $(document).ready(function() {
        CKEDITOR.replace('add_project_description');
        CKEDITOR.replace('edit_project_description');
    });

    function openAddModal() {
        document.getElementById('addProjectModal').style.display = 'block';
    }

    function closeAddModal() {
        document.getElementById('addProjectModal').style.display = 'none';
    }

    function closeEditModal() {
        document.getElementById('editProjectModal').style.display = 'none';
    }

    function editProject(id) {
        fetch(`/admin/projects/${id}/edit`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('edit_project_name').value = data.name;
                document.getElementById('edit_start_date').value = data.start_date || '';
                document.getElementById('edit_end_date').value = data.end_date || '';
                
                if (CKEDITOR.instances['edit_project_description']) {
                    CKEDITOR.instances['edit_project_description'].setData(data.description || '');
                }

                document.getElementById('edit_remarks').value = data.remarks || '';
                document.getElementById('edit_project_status').value = data.status;
                
                const attachPreview = document.getElementById('edit_attachment_preview');
                if (data.attachment) {
                    attachPreview.innerHTML = `Current file: <a href="/storage/${data.attachment}" target="_blank" style="color: blue;">View Attachment</a>`;
                } else {
                    attachPreview.innerHTML = '';
                }

                document.getElementById('editProjectForm').action = `/admin/projects/${id}`;
                document.getElementById('editProjectModal').style.display = 'block';
            });
    }
</script>
@endsection
@endsection
