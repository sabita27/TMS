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
                    <th>Category</th>
                    <th>Sub-Category</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Status</th>
                    <th>Priority</th>
                    <th>Active</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($projects as $project)
                <tr>
                    <td style="font-weight: 600;">{{ $project->name }}</td>
                    <td>
                        @if($project->category)
                            <span class="badge" style="background: #e0e7ff; color: #4338ca; font-weight: 600;">{{ $project->category->name }}</span>
                        @else
                            <span style="color: #9ca3af; font-style: italic;">-</span>
                        @endif
                    </td>
                    <td>
                        @if($project->subcategory)
                            <span class="badge" style="background: #dbeafe; color: #1e40af; font-weight: 600;">{{ $project->subcategory->name }}</span>
                        @else
                            <span style="color: #9ca3af; font-style: italic;">-</span>
                        @endif
                    </td>
                    <td>{{ $project->start_date ? \Carbon\Carbon::parse($project->start_date)->format('M d, Y') : '-' }}</td>
                    <td>{{ $project->end_date ? \Carbon\Carbon::parse($project->end_date)->format('M d, Y') : '-' }}</td>
                    <td>
                        @if($project->projectStatus)
                            <span class="badge" style="background-color: {{ $project->projectStatus->color }}20; color: {{ $project->projectStatus->color }}; border: 1px solid {{ $project->projectStatus->color }}; font-weight: 700; padding: 0.4rem 0.8rem;">
                                {{ $project->projectStatus->name }}
                            </span>
                        @else
                            <span style="color: #9ca3af; font-style: italic;">-</span>
                        @endif
                    </td>
                    <td>
                        @if($project->priority)
                            <span class="badge" style="background-color: {{ $project->priority->color }}20; color: {{ $project->priority->color }}; border: 1px solid {{ $project->priority->color }}; font-weight: 700; padding: 0.4rem 0.8rem;">
                                {{ $project->priority->name }}
                            </span>
                        @else
                            <span style="color: #9ca3af; font-style: italic;">-</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge {{ $project->status ? 'badge-success' : 'badge-danger' }}">
                            {{ $project->status ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td>
                        <div style="display: flex; gap: 0.5rem;">
                            <button onclick="viewProject({{ $project->id }})" class="btn" style="padding: 0.4rem 0.7rem; font-size: 0.75rem; background: #10b981; color: white;">
                                <i class="fas fa-eye"></i> View
                            </button>
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

<!-- View Project Modal -->
<div id="viewProjectModal" style="display:none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(8px); overflow-y: auto;">
    <div style="background: white; width: 850px; margin: 3rem auto; border-radius: 1.25rem; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); border: 1px solid rgba(255,255,255,0.1); overflow: hidden; animation: modalAppear 0.3s ease-out;">
        <!-- Header -->
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 1.5rem 2rem; background: linear-gradient(to right, #f8fafc, #ffffff); border-bottom: 1px solid #e2e8f0;">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <div style="width: 48px; height: 48px; background: #4f46e5; color: white; border-radius: 1rem; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.3);">
                    <i class="fas fa-project-diagram"></i>
                </div>
                <div>
                    <h3 style="margin: 0; font-size: 1.25rem; font-weight: 800; color: #1e293b; letter-spacing: -0.025em;">Project Information</h3>
                    <p style="margin: 0; font-size: 0.875rem; color: #64748b;">Comprehensive overview of project parameters</p>
                </div>
            </div>
            <button onclick="closeViewModal()" style="background: white; border: 1px solid #e2e8f0; width: 36px; height: 36px; border-radius: 50%; color: #94a3b8; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; justify-content: center; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Body -->
        <div style="padding: 2.5rem;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                <!-- Full Width Project Name -->
                <div style="grid-column: span 2; background: #f1f5f9; padding: 1.5rem; border-radius: 1rem; border: 1px solid #e2e8f0;">
                    <label style="display: flex; align-items: center; gap: 0.5rem; color: #475569; font-weight: 700; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 0.75rem;">
                        <i class="fas fa-tag"></i> Primary Project Name
                    </label>
                    <div id="view_project_name" style="font-size: 1.5rem; font-weight: 800; color: #0f172a; line-height: 1.2;"></div>
                </div>

                <!-- Color Coded Dates -->
                <div style="background: #fff1f2; padding: 1.25rem; border-radius: 1rem; border: 1px solid #ffe4e6; display: flex; flex-direction: column; gap: 0.5rem;">
                    <label style="display: flex; align-items: center; gap: 0.5rem; color: #e11d48; font-weight: 700; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">
                        <i class="fas fa- hourglass-start"></i> Initiation Date
                    </label>
                    <div id="view_start_date" style="font-size: 1.125rem; font-weight: 700; color: #9f1239;"></div>
                </div>

                <div style="background: #f0f9ff; padding: 1.25rem; border-radius: 1rem; border: 1px solid #e0f2fe; display: flex; flex-direction: column; gap: 0.5rem;">
                    <label style="display: flex; align-items: center; gap: 0.5rem; color: #0284c7; font-weight: 700; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">
                        <i class="fas fa-flag-checkered"></i> Estimated Deadline
                    </label>
                    <div id="view_end_date" style="font-size: 1.125rem; font-weight: 700; color: #075985;"></div>
                </div>

                <!-- Progress/Status Row -->
                <div style="display: flex; align-items: center; justify-content: space-between; grid-column: span 2; padding: 1rem 1.5rem; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 1rem;">
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <label style="color: #64748b; font-weight: 700; font-size: 0.75rem; text-transform: uppercase;">Current Status:</label>
                        <div id="view_project_status"></div>
                    </div>
                    <div id="view_attachment_link"></div>
                </div>

                <!-- Detailed Description -->
                <div style="grid-column: span 2;">
                    <label style="display: flex; align-items: center; gap: 0.5rem; color: #64748b; font-weight: 700; font-size: 0.75rem; text-transform: uppercase; margin-bottom: 0.75rem;">
                        <i class="fas fa-file-alt"></i> Detailed Project Description
                    </label>
                    <div id="view_project_description" style="min-height: 150px; padding: 1.5rem; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 1rem; color: #334155; line-height: 1.6; font-size: 1rem; box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);"></div>
                </div>

                <!-- Internal Remarks -->
                <div style="grid-column: span 2; background: #fffbeb; padding: 1.5rem; border-radius: 1rem; border: 1px solid #fde68a;">
                    <label style="display: flex; align-items: center; gap: 0.5rem; color: #b45309; font-weight: 700; font-size: 0.75rem; text-transform: uppercase; margin-bottom: 0.5rem;">
                        <i class="fas fa-comment-dots"></i> Stakeholder Remarks & Notes
                    </label>
                    <div id="view_remarks" style="color: #92400e; font-size: 0.9375rem; line-height: 1.5; font-style: italic;"></div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div style="padding: 1.5rem 2.5rem; background: #f8fafc; border-top: 1px solid #e2e8f0; display: flex; gap: 1rem;">
            <button type="button" onclick="closeViewModal()" style="flex: 1; padding: 0.85rem 2.5rem; background: #0f172a; color: white; border-radius: 0.75rem; border: none; font-weight: 700; cursor: pointer; transition: 0.2s; box-shadow: 0 4px 10px rgba(15, 23, 42, 0.15);">Dismiss View</button>
        </div>
    </div>
</div>

<style>
@keyframes modalAppear {
    from { opacity: 0; transform: translateY(-20px) scale(0.95); }
    to { opacity: 1; transform: translateY(0) scale(1); }
}
</style>

<!-- Add Project Modal -->
<div id="addProjectModal" style="display:none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); overflow-y: auto;">
    <div style="background: white; width: 800px; margin: 2rem auto; padding: 2rem; border-radius: 0.75rem; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; border-bottom: 1px solid #eee; padding-bottom: 1rem;">
            <h3 style="margin: 0;">Add New Project</h3>
            <button type="button" onclick="closeAddModal()" style="background: none; border: none; font-size: 1.25rem; color: #94a3b8; cursor: pointer;">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form action="{{ route('admin.projects.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group" style="grid-column: span 2;">
                    <label class="form-label">Project Name</label>
                    <input type="text" name="name" class="form-control" required placeholder="Enter project name">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Category</label>
                    <select name="category_id" id="add_category_id" class="form-control" onchange="loadSubcategories(this.value, 'add_subcategory_id')">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Sub-Category</label>
                    <select name="subcategory_id" id="add_subcategory_id" class="form-control">
                        <option value="">Select Sub-Category</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status_id" class="form-control">
                        <option value="">Select Status</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status->id }}" data-color="{{ $status->color }}">
                                {{ $status->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Priority</label>
                    <select name="priority_id" class="form-control">
                        <option value="">Select Priority</option>
                        @foreach($priorities as $priority)
                            <option value="{{ $priority->id }}" data-color="{{ $priority->color }}">
                                {{ $priority->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Project Start Date</label>
                    <input type="date" name="start_date" id="add_start_date" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">Project End Date</label>
                    <input type="date" name="end_date" id="add_end_date" class="form-control">
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
                    <label class="form-label">Active Status</label>
                    <select name="status" class="form-control">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
            </div>
            <div style="display: flex; gap: 1rem; margin-top: 2rem; border-top: 1px solid #eee; padding-top: 1.5rem;">
                <button type="submit" class="btn btn-primary" style="flex:1;">Save Project</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Project Modal -->
<div id="editProjectModal" style="display:none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); overflow-y: auto;">
    <div style="background: white; width: 800px; margin: 2rem auto; padding: 2rem; border-radius: 0.75rem; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; border-bottom: 1px solid #eee; padding-bottom: 1rem;">
            <h3 style="margin: 0;">Edit Project</h3>
            <button type="button" onclick="closeEditModal()" style="background: none; border: none; font-size: 1.25rem; color: #94a3b8; cursor: pointer;">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="editProjectForm" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group" style="grid-column: span 2;">
                    <label class="form-label">Project Name</label>
                    <input type="text" name="name" id="edit_project_name" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Category</label>
                    <select name="category_id" id="edit_category_id" class="form-control" onchange="loadSubcategories(this.value, 'edit_subcategory_id')">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Sub-Category</label>
                    <select name="subcategory_id" id="edit_subcategory_id" class="form-control">
                        <option value="">Select Sub-Category</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status_id" id="edit_status_id" class="form-control">
                        <option value="">Select Status</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status->id }}" data-color="{{ $status->color }}">
                                {{ $status->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Priority</label>
                    <select name="priority_id" id="edit_priority_id" class="form-control">
                        <option value="">Select Priority</option>
                        @foreach($priorities as $priority)
                            <option value="{{ $priority->id }}" data-color="{{ $priority->color }}">
                                {{ $priority->name }}
                            </option>
                        @endforeach
                    </select>
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
                    <label class="form-label">Active Status</label>
                    <select name="status" id="edit_project_status" class="form-control">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
            </div>
            <div style="display: flex; gap: 1rem; margin-top: 2rem; border-top: 1px solid #eee; padding-top: 1.5rem;">
                <button type="submit" class="btn btn-primary" style="flex:1;">Update Project</button>
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

        // Set default dates and restrictions
        const today = new Date().toISOString().split('T')[0];
        
        // Add Modal
        const addStartDate = document.getElementById('add_start_date');
        const addEndDate = document.getElementById('add_end_date');
        if (addStartDate) {
            addStartDate.value = today;
        }
        if (addEndDate) {
            addEndDate.setAttribute('min', today);
        }

        // Edit Modal
        const editEndDate = document.getElementById('edit_end_date');
        if (editEndDate) {
            editEndDate.setAttribute('min', today);
        }
    });

    // Dynamic subcategory loading
    function loadSubcategories(categoryId, targetSelectId, selectedValue = null) {
        const subcategorySelect = document.getElementById(targetSelectId);
        subcategorySelect.innerHTML = '<option value="">Select Sub-Category</option>';
        
        if (categoryId) {
            fetch(`/get-subcategories/${categoryId}`)
                .then(response => response.json())
                .then(data => {
                    data.forEach(subcategory => {
                        const option = document.createElement('option');
                        option.value = subcategory.id;
                        option.textContent = subcategory.name;
                        if (selectedValue && subcategory.id == selectedValue) {
                            option.selected = true;
                        }
                        subcategorySelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error loading subcategories:', error));
        }
    }

    function openAddModal() {
        document.getElementById('addProjectModal').style.display = 'block';
    }

    function closeAddModal() {
        document.getElementById('addProjectModal').style.display = 'none';
    }

    function closeEditModal() {
        document.getElementById('editProjectModal').style.display = 'none';
    }

    function closeViewModal() {
        document.getElementById('viewProjectModal').style.display = 'none';
    }

    function viewProject(id) {
        fetch(`/admin/projects/${id}/edit`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('view_project_name').innerText = data.name;
                document.getElementById('view_start_date').innerText = data.start_date ? new Date(data.start_date).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }) : 'Not Specified';
                document.getElementById('view_end_date').innerText = data.end_date ? new Date(data.end_date).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }) : 'Not Specified';
                document.getElementById('view_project_description').innerHTML = data.description || '<em style="color:#94a3b8">No description provided for this project.</em>';
                document.getElementById('view_remarks').innerText = data.remarks || 'No internal remarks recorded.';
                
                // Professional Status Badge
                const statusHtml = data.status == 1 
                    ? '<span style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.4rem 1rem; background: #ecfdf5; color: #059669; border: 1px solid #10b981; border-radius: 2rem; font-size: 0.75rem; font-weight: 700; text-transform: uppercase;"><span style="width: 8px; height: 8px; background: #10b981; border-radius: 50%; display: inline-block;"></span> Active</span>' 
                    : '<span style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.4rem 1rem; background: #fef2f2; color: #dc2626; border: 1px solid #ef4444; border-radius: 2rem; font-size: 0.75rem; font-weight: 700; text-transform: uppercase;"><span style="width: 8px; height: 8px; background: #ef4444; border-radius: 50%; display: inline-block;"></span> Inactive</span>';
                document.getElementById('view_project_status').innerHTML = statusHtml;

                const attachLink = document.getElementById('view_attachment_link');
                if (data.attachment) {
                    attachLink.innerHTML = `<a href="/storage/${data.attachment}" target="_blank" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.6rem 1.25rem; background: #4f46e5; color: white; border-radius: 0.75rem; font-size: 0.825rem; font-weight: 600; text-decoration: none; transition: 0.2s; box-shadow: 0 4px 10px rgba(79, 70, 229, 0.2);"><i class="fas fa-download"></i> Project Assets</a>`;
                } else {
                    attachLink.innerHTML = '<span style="color: #94a3b8; font-size: 0.85rem; font-style: italic;"><i class="fas fa-times-circle"></i> No Attachments</span>';
                }

                document.getElementById('viewProjectModal').style.display = 'block';
            });
    }

    function editProject(id) {
        fetch(`/admin/projects/${id}/edit`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('edit_project_name').value = data.name;
                document.getElementById('edit_start_date').value = data.start_date || '';
                document.getElementById('edit_end_date').value = data.end_date || '';
                
                // Set category
                document.getElementById('edit_category_id').value = data.category_id || '';
                
                // Load and set subcategory
                if (data.category_id) {
                    loadSubcategories(data.category_id, 'edit_subcategory_id', data.subcategory_id);
                }
                
                // Set status and priority
                document.getElementById('edit_status_id').value = data.status_id || '';
                document.getElementById('edit_priority_id').value = data.priority_id || '';
                
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
