@extends('layouts.backend.master')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Client Master</h3>
        <button onclick="document.getElementById('addClientModal').style.display='block'" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Client
        </button>
    </div>

    @if($errors->any())
    <div style="background: #fee2e2; border: 1px solid #ef4444; color: #b91c1c; padding: 1rem; border-radius: 0.5rem; margin-bottom: 2rem;">
        <ul style="margin: 0; padding-left: 1.5rem;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Client Name</th>
                    <th>Contact Person</th>
                    <th>Email / Phone</th>
                    <th>Products</th>
                    <th>Projects</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($clients as $client)
                <tr>
                    <td>
                        <div style="font-weight: 600;">{{ $client->name }}</div>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">{{ Str::limit($client->address, 30) }}</div>
                    </td>
                    <td>
                        <div>{{ $client->contact_person_name ?? '-' }}</div>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $client->contact_person_phone ?? '-' }}</div>
                    </td>
                    <td>
                        <div>{{ $client->email }}</div>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $client->phone ?? '-' }}</div>
                    </td>
                    <td>
                        @php
                            $selectedProductIds = $client->product_id ?? [];
                            $productNames = $products->whereIn('id', $selectedProductIds)->pluck('name')->toArray();
                        @endphp
                        {{ !empty($productNames) ? implode(', ', $productNames) : '-' }}
                    </td>
                    <td>
                        @php
                            $selectedProjectIds = $client->project_id ?? [];
                            $projectNames = $projects->whereIn('id', $selectedProjectIds)->pluck('name')->toArray();
                        @endphp
                        {{ !empty($projectNames) ? implode(', ', $projectNames) : '-' }}
                    </td>
                    <td>{{ $client->project_start_date ? $client->project_start_date->format('M d, Y') : '-' }}</td>
                    <td>{{ $client->project_end_date ? $client->project_end_date->format('M d, Y') : '-' }}</td>
                    <td>
                        <span class="badge {{ $client->status ? 'badge-success' : 'badge-danger' }}">
                            {{ $client->status ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td>
                        <div style="display: flex; gap: 0.5rem;">
                            <button onclick="editClient({{ $client->id }})" class="btn btn-primary" style="padding: 0.4rem 0.7rem; font-size: 0.75rem;">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form action="{{ route('admin.clients.delete', $client->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this client?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="padding: 0.4rem 0.7rem; font-size: 0.75rem;">
                                    <i class="fas fa-trash"></i>
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
        {{ $clients->links() }}
    </div>
</div>

<!-- Add Client Modal -->
<div id="addClientModal" style="display:none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); overflow-y: auto;">
    <div style="background: white; width: 600px; margin: 2rem auto; padding: 2rem; border-radius: 0.5rem;">
        <h3 style="margin-top:0; border-bottom: 1px solid #eee; padding-bottom: 1rem;">Add New Client</h3>
        <form action="{{ route('admin.clients.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label class="form-label">Client Name</label>
                    <input type="text" name="name" class="form-control" required placeholder="Company or Individual Name">
                </div>
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required placeholder="Enter client email">
                </div>
                <div class="form-group">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control" placeholder="Enter Office Phone Number">
                </div>
                <div class="form-group">
                    <label class="form-label">Address</label>
                    <input type="text" name="address" class="form-control" placeholder="Full Address">
                </div>
                <div class="form-group">
                    <label class="form-label">Contact Person-1 Name</label>
                    <input type="text" name="contact_person1_name" class="form-control" placeholder="Enter the name">
                </div>
                <div class="form-group">
                    <label class="form-label">Contact Person-1 Phone</label>
                    <input type="text" name="contact_person1_phone" class="form-control" placeholder="Enter the phone number">
                </div>
                <div class="form-group">
                    <label class="form-label">Contact Person-2 Name</label>
                    <input type="text" name="contact_person2_name" class="form-control" placeholder="Enter the name">
                </div>
                <div class="form-group">
                    <label class="form-label">Contact Person-2 Phone</label>
                    <input type="text" name="contact_person2_phone" class="form-control" placeholder="Enter the phone number">
                </div>
                <!-- Business Type Toggles -->
                <div class="form-group" style="grid-column: span 2; margin-bottom: 2rem;">
                    <label class="form-label" style="text-align: center; display: block; margin-bottom: 1rem; font-weight: 700;">Choose Business Category</label>
                    <div style="display: flex; justify-content: center; gap: 1rem;">
                        <input type="radio" name="business_type" id="add_type_product" value="product" checked onchange="toggleClientFields('add')" style="display: none;">
                        <label for="add_type_product" class="biz-toggle">Product</label>

                        <input type="radio" name="business_type" id="add_type_project" value="project" onchange="toggleClientFields('add')" style="display: none;">
                        <label for="add_type_project" class="biz-toggle">Project</label>

                        <input type="radio" name="business_type" id="add_type_both" value="both" onchange="toggleClientFields('add')" style="display: none;">
                        <label for="add_type_both" class="biz-toggle">Both</label>
                    </div>
                </div>

                <!-- Product Section -->
                <div id="add_product_section" class="form-group" style="grid-column: span 2;">
                    <label class="form-label">Product Name (Multiple)</label>
                    <select name="product_id[]" id="add_product_name" class="form-control" multiple>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Project Section -->
                <div id="add_project_section" style="grid-column: span 2; display: none; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group" style="grid-column: span 2;">
                        <label class="form-label">Project Name (Multiple)</label>
                        <select name="project_id[]" id="add_project_id" class="form-control" multiple>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}">{{ $project->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Project Start Date</label>
                        <input type="date" name="project_start_date" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Project End Date</label>
                        <input type="date" name="project_end_date" class="form-control">
                    </div>
                    <div class="form-group" style="grid-column: span 2;">
                        <label class="form-label">Project Description</label>
                        <textarea name="project_description" id="add_project_description" class="form-control"></textarea>
                    </div>
                    <div class="form-group" style="grid-column: span 2;">
                        <label class="form-label">Attachment</label>
                        <input type="file" name="attachment" class="form-control">
                    </div>
                </div>

                <!-- Remarks and Status (Common) -->
                <div class="form-group" style="grid-column: span 2;">
                    <label class="form-label">Remarks</label>
                    <textarea name="remarks" class="form-control" rows="3" placeholder="Additional details..."></textarea>
                </div>
                <div class="form-group" style="grid-column: span 2;">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
            </div>
            <div style="display: flex; gap: 1rem; margin-top: 1.5rem; border-top: 1px solid #eee; padding-top: 1.5rem;">
                <button type="submit" class="btn btn-primary" style="flex:1;">Save Client</button>
                <button type="button" onclick="document.getElementById('addClientModal').style.display='none'" class="btn" style="flex:1; background: #e5e7eb;">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Client Modal -->
<div id="editClientModal" style="display:none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); overflow-y: auto;">
    <div style="background: white; width: 600px; margin: 2rem auto; padding: 2rem; border-radius: 0.5rem;">
        <h3 style="margin-top:0; border-bottom: 1px solid #eee; padding-bottom: 1rem;">Edit Client</h3>
        <form id="editClientForm" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label class="form-label">Client Name</label>
                    <input type="text" name="name" id="edit_name" class="form-control" required placeholder="Company or Individual Name">
                </div>
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" id="edit_email" class="form-control" required placeholder="client@example.com">
                </div>
                <div class="form-group">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" id="edit_phone" class="form-control" placeholder="Office Phone Number">
                </div>
                <div class="form-group">
                    <label class="form-label">Address</label>
                    <input type="text" name="address" id="edit_address" class="form-control" placeholder="Full Address">
                </div>
                <div class="form-group">
                    <label class="form-label">Contact Person-1 Name</label>
                    <input type="text" name="contact_person1_name" id="edit_contact_person1_name" class="form-control" placeholder="Enter the name">
                </div>
                <div class="form-group">
                    <label class="form-label">Contact Person-1 Phone</label>
                    <input type="text" name="contact_person1_phone" id="edit_contact_person1_phone" class="form-control" placeholder="Enter the phone number">
                </div>
                <div class="form-group">
                    <label class="form-label">Contact Person-2 Name</label>
                    <input type="text" name="contact_person2_name" id="edit_contact_person2_name" class="form-control" placeholder="Enter the name">
                </div>
                <div class="form-group">
                    <label class="form-label">Contact Person-2 Phone</label>
                    <input type="text" name="contact_person2_phone" id="edit_contact_person2_phone" class="form-control" placeholder="Enter the phone number">
                </div>
                <!-- Business Type Toggles -->
                <div class="form-group" style="grid-column: span 2; margin-bottom: 2rem;">
                    <label class="form-label" style="text-align: center; display: block; margin-bottom: 1rem; font-weight: 700;">Choose Business Category</label>
                    <div style="display: flex; justify-content: center; gap: 1rem;">
                        <input type="radio" name="business_type" id="edit_type_product" value="product" onchange="toggleClientFields('edit')" style="display: none;">
                        <label for="edit_type_product" class="biz-toggle">Product</label>

                        <input type="radio" name="business_type" id="edit_type_project" value="project" onchange="toggleClientFields('edit')" style="display: none;">
                        <label for="edit_type_project" class="biz-toggle">Project</label>

                        <input type="radio" name="business_type" id="edit_type_both" value="both" onchange="toggleClientFields('edit')" style="display: none;">
                        <label for="edit_type_both" class="biz-toggle">Both</label>
                    </div>
                </div>

                <!-- Product Section -->
                <div id="edit_product_section" class="form-group" style="grid-column: span 2;">
                    <label class="form-label">Product Name (Multiple)</label>
                    <select name="product_id[]" id="edit_product_name_select2" class="form-control" multiple>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Project Section -->
                <div id="edit_project_section" style="grid-column: span 2; display: none; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group" style="grid-column: span 2;">
                        <label class="form-label">Project Name (Multiple)</label>
                        <select name="project_id[]" id="edit_project_id_select2" class="form-control" multiple>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}">{{ $project->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Project Start Date</label>
                        <input type="date" name="project_start_date" id="edit_project_start_date" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Project End Date</label>
                        <input type="date" name="project_end_date" id="edit_project_end_date" class="form-control">
                    </div>
                    <div class="form-group" style="grid-column: span 2;">
                        <label class="form-label">Project Description</label>
                        <textarea name="project_description" id="edit_project_description" class="form-control"></textarea>
                    </div>
                    <div class="form-group" style="grid-column: span 2;">
                        <label class="form-label">Attachment</label>
                        <input type="file" name="attachment" class="form-control">
                        <div id="edit_attachment_preview" style="margin-top: 0.5rem; font-size: 0.8rem; color: var(--text-muted);"></div>
                    </div>
                </div>

                <!-- Remarks (Common) -->
                <div class="form-group" style="grid-column: span 2;">
                    <label class="form-label">Remarks</label>
                    <textarea name="remarks" id="edit_remarks" class="form-control" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" id="edit_status" class="form-control">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
            </div>
            <div style="display: flex; gap: 1rem; margin-top: 1.5rem; border-top: 1px solid #eee; padding-top: 1.5rem;">
                <button type="submit" class="btn btn-primary" style="flex:1;">Update Client</button>
                <button type="button" onclick="document.getElementById('editClientModal').style.display='none'" class="btn" style="flex:1; background: #e5e7eb;">Cancel</button>
            </div>
        </form>
    </div>
</div>

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container--default .select2-selection--multiple {
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        padding: 6px;
        min-height: 42px;
        background: #fff;
    }
    .select2-container--default.select2-container--focus .select2-selection--multiple {
        border-color: #334155;
        box-shadow: 0 0 0 3px rgba(51, 65, 85, 0.1);
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #f1f5f9;
        border: 1px solid #e2e8f0;
        color: #1e293b;
        border-radius: 6px;
        padding: 2px 10px;
        font-size: 0.85rem;
        font-weight: 500;
        margin-top: 4px;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: #64748b;
        margin-right: 8px;
        border: none;
        font-weight: bold;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
        background: transparent;
        color: #ef4444;
    }
    
    /* Toggle Switch Styles - Professional Slate */
    .biz-toggle {
        padding: 0.65rem 1.8rem;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        color: #64748b;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        min-width: 110px;
        text-align: center;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    }
    input[type="radio"]:checked + .biz-toggle {
        background: #334155;
        border-color: #334155;
        color: #fff;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        transform: translateY(-1px);
    }
    .biz-toggle:hover:not(input[type="radio"]:checked + .biz-toggle) {
        background: #f8fafc;
        border-color: #cbd5e1;
        color: #334155;
    }
    
    /* Better modal scrolling and dropdown visibility */
    .select2-container { z-index: 9999 !important; }
    #addClientModal, #editClientModal { scroll-behavior: smooth; }
    
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
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
<script>
    let addEditor, editEditor;

    $(document).ready(function() {
        // Initialize CKEditor
        CKEDITOR.replace('add_project_description');
        CKEDITOR.replace('edit_project_description');

        // Initialize Select2 for Add Modal
        // Initialize Select2 with improved visibility
        const select2Options = {
            allowClear: true,
            width: '100%',
        };

        $('#add_product_name').select2({ ...select2Options, placeholder: "Select Products" });
        $('#add_project_id').select2({ ...select2Options, placeholder: "Select Projects" });
        $('#edit_product_name_select2').select2({ ...select2Options, placeholder: "Select Products" });
        $('#edit_project_id_select2').select2({ ...select2Options, placeholder: "Select Projects" });

        // Initial toggle state
        toggleClientFields('add');
    });

    function toggleClientFields(type) {
        let value;
        if (type === 'add') {
            const checkedRadio = document.querySelector('#addClientModal input[name="business_type"]:checked');
            value = checkedRadio ? checkedRadio.value : 'product';
            
            const productSec = document.getElementById('add_product_section');
            const projectSec = document.getElementById('add_project_section');
            
            productSec.style.display = (value === 'product' || value === 'both') ? 'block' : 'none';
            projectSec.style.display = (value === 'project' || value === 'both') ? 'grid' : 'none';
        } else {
            const checkedRadio = document.querySelector('#editClientModal input[name="business_type"]:checked');
            value = checkedRadio ? checkedRadio.value : 'product';
            
            const productSec = document.getElementById('edit_product_section');
            const projectSec = document.getElementById('edit_project_section');
            
            productSec.style.display = (value === 'product' || value === 'both') ? 'block' : 'none';
            projectSec.style.display = (value === 'project' || value === 'both') ? 'grid' : 'none';
        }
    }

    function editClient(id) {
        fetch(`/admin/clients/${id}/edit`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('edit_name').value = data.name;
                document.getElementById('edit_email').value = data.email;
                document.getElementById('edit_phone').value = data.phone || '';
                document.getElementById('edit_address').value = data.address || '';
                document.getElementById('edit_contact_person1_name').value = data.contact_person1_name || '';
                document.getElementById('edit_contact_person1_phone').value = data.contact_person1_phone || '';
                document.getElementById('edit_contact_person2_name').value = data.contact_person2_name || '';
                document.getElementById('edit_contact_person2_phone').value = data.contact_person2_phone || '';
                
                // Set business type radio
                const bizType = data.business_type || 'product';
                const radioToCheck = document.querySelector(`#editClientModal input[name="business_type"][value="${bizType}"]`);
                if (radioToCheck) radioToCheck.checked = true;
                
                // Show/hide sections
                toggleClientFields('edit');

                // Set Product Name
                const selectedProducts = data.product_id || [];
                $('#edit_product_name_select2').val(selectedProducts).trigger('change');

                // Set Project fields
                const selectedProjects = data.project_id || [];
                $('#edit_project_id_select2').val(selectedProjects).trigger('change');

                document.getElementById('edit_project_start_date').value = data.project_start_date ? data.project_start_date.split('T')[0] : '';
                document.getElementById('edit_project_end_date').value = data.project_end_date ? data.project_end_date.split('T')[0] : '';
                
                // Set CKEditor content
                if (CKEDITOR.instances['edit_project_description']) {
                    CKEDITOR.instances['edit_project_description'].setData(data.project_description || '');
                }

                document.getElementById('edit_remarks').value = data.remarks || '';
                document.getElementById('edit_status').value = data.status;

                // Attachment preview
                const attachPreview = document.getElementById('edit_attachment_preview');
                if (data.attachment) {
                    attachPreview.innerHTML = `Current file: <a href="/storage/${data.attachment}" target="_blank" style="color: var(--primary-color);">View Attachment</a>`;
                } else {
                    attachPreview.innerHTML = '';
                }

                document.getElementById('editClientForm').action = `/admin/clients/${id}`;
                document.getElementById('editClientModal').style.display = 'block';
            });
    }
</script>
@endsection
@endsection
