@extends('layouts.backend.master')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Client Master</h3>
        <button onclick="openAddModal()" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Client
        </button>
    </div>
    
    @if($errors->any())
    <div class="alert-message" style="background: #fee2e2; border: 1px solid #ef4444; color: #b91c1c; padding: 1rem; border-radius: 0.5rem; margin-bottom: 2rem;">
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
                    <th>Services</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($clients as $client)
                <tr>
                    <td>
                        <div style="font-weight: 600; color: #1e293b;">{{ $client->name }}</div>
                        <div style="font-size: 0.75rem; color: #64748b;">{{ Str::limit($client->address, 30) }}</div>
                    </td>
                    <td>
                        <div style="font-weight: 500;">{{ $client->contact_person1_name ?? '-' }}</div>
                        <div style="font-size: 0.75rem; color: #64748b;">{{ $client->contact_person1_phone ?? '-' }}</div>
                    </td>
                    <td>
                        <div style="font-weight: 500;">{{ $client->email }}</div>
                        <div style="font-size: 0.75rem; color: #64748b;">{{ $client->phone ?? '-' }}</div>
                    </td>
                    <td>
                        @php
                            $selectedProductIds = $client->product_id ?? [];
                            $productNames = $products->whereIn('id', $selectedProductIds)->pluck('name')->toArray();
                        @endphp
                        @if(!empty($productNames))
                            <div style="display: flex; flex-wrap: wrap; gap: 0.25rem;">
                                @foreach($productNames as $pName)
                                    <span class="badge" style="background: #eff6ff; color: #1d4ed8; font-weight: 600;">{{ $pName }}</span>
                                @endforeach
                            </div>
                        @else
                            <span style="color: #94a3b8; font-style: italic;">-</span>
                        @endif
                    </td>
                    <td>
                        @php
                            $selectedProjectIds = $client->project_id ?? [];
                            $projectNames = $projects->whereIn('id', $selectedProjectIds)->pluck('name')->toArray();
                        @endphp
                        @if(!empty($projectNames))
                            <div style="display: flex; flex-wrap: wrap; gap: 0.25rem;">
                                @foreach($projectNames as $pName)
                                    <span class="badge" style="background: #f0fdf4; color: #15803d; font-weight: 600;">{{ $pName }}</span>
                                @endforeach
                            </div>
                        @else
                            <span style="color: #94a3b8; font-style: italic;">-</span>
                        @endif
                    </td>
                    <td>
                        @php
                            $selectedServiceIds = $client->service_id ?? [];
                            $serviceNames = $services->whereIn('id', $selectedServiceIds)->pluck('name')->toArray();
                        @endphp
                        @if(!empty($serviceNames))
                            <div style="display: flex; flex-wrap: wrap; gap: 0.25rem;">
                                @foreach($serviceNames as $sName)
                                    <span class="badge" style="background: #fdf4ff; color: #a21caf; font-weight: 600;">{{ $sName }}</span>
                                @endforeach
                            </div>
                        @else
                            <span style="color: #94a3b8; font-style: italic;">-</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge {{ $client->status ? 'badge-success' : 'badge-danger' }}">
                            {{ $client->status ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td>
                        <div style="display: flex; gap: 0.5rem;">
                            <button onclick="viewClient({{ $client->id }})" class="btn" style="padding: 0.4rem 0.7rem; font-size: 0.75rem; background: #10b981; color: white;">
                                <i class="fas fa-eye"></i> View
                            </button>
                            <button onclick="editClient({{ $client->id }})" class="btn btn-primary" style="padding: 0.4rem 0.7rem; font-size: 0.75rem;">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <form action="{{ route('admin.clients.delete', $client->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this client?')">
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
        {{ $clients->links() }}
    </div>
</div>

<!-- Add Client Modal -->
<div id="addClientModal" style="display:none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); overflow-y: auto;">
    <div style="background: white; width: 800px; margin: 2rem auto; padding: 2rem; border-radius: 0.75rem; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);">
        <h3 style="margin-top:0; border-bottom: 1px solid #eee; padding-bottom: 1rem;">Add New Client</h3>
        <form action="{{ route('admin.clients.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label class="form-label">Organization Name</label>
                    <input type="text" name="name" class="form-control" required placeholder="Company or Individual Name">
                </div>
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required placeholder="Enter client email">
                </div>
                <div class="form-group">
                    <label class="form-label">Phone <span style="color: red;">*</span></label>
                    <input type="text" name="phone" class="form-control" placeholder="Enter Office Phone Number" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Address <span style="color: red;">*</span></label>
                    <input type="text" name="address" class="form-control" placeholder="Full Address" required>
                </div>
                
                <!-- Contact Person 1 -->
                <div class="section-divider" style="grid-column: span 2; margin-top: 1rem; border-top: 1px dashed #e2e8f0; padding-top: 1rem;">
                    <span style="font-size: 0.85rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Primary Contact Person</span>
                </div>
                <div class="form-group">
                    <label class="form-label">Name <span style="color: red;">*</span></label>
                    <input type="text" name="contact_person1_name" class="form-control" placeholder="Primary Contact Name" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Phone <span style="color: red;">*</span></label>
                    <input type="text" name="contact_person1_phone" class="form-control" placeholder="Primary Contact Phone" required>
                </div>

                <!-- Contact Person 2 -->
                <div class="section-divider" style="grid-column: span 2; margin-top: 0.5rem; border-top: 1px dashed #e2e8f0; padding-top: 1rem;">
                    <span style="font-size: 0.85rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Secondary Contact Person</span>
                </div>
                <div class="form-group">
                    <label class="form-label">Name</label>
                    <input type="text" name="contact_person2_name" class="form-control" placeholder="Secondary Contact Name">
                </div>
                <div class="form-group">
                    <label class="form-label">Phone</label>
                    <input type="text" name="contact_person2_phone" class="form-control" placeholder="Secondary Contact Phone">
                </div>

                <!-- Business Type Toggles -->
                <div class="form-group" style="grid-column: span 2; margin-top: 1.5rem; margin-bottom: 1.5rem; background: #f8fafc; padding: 1.5rem; border-radius: 0.75rem; border: 1px solid #e2e8f0;">
                    <label class="form-label" style="text-align: center; display: block; margin-bottom: 1rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em;">Business Type</label>
                    <div style="display: flex; justify-content: center; gap: 1rem; flex-wrap: wrap;">
                        <input type="radio" name="business_type" id="add_type_product" value="product" checked onchange="toggleClientFields('add')" style="display: none;">
                        <label for="add_type_product" class="biz-toggle">Product</label>

                        <input type="radio" name="business_type" id="add_type_project" value="project" onchange="toggleClientFields('add')" style="display: none;">
                        <label for="add_type_project" class="biz-toggle">Project</label>

                        <input type="radio" name="business_type" id="add_type_service" value="service" onchange="toggleClientFields('add')" style="display: none;">
                        <label for="add_type_service" class="biz-toggle">Service</label>

                        <input type="radio" name="business_type" id="add_type_both" value="both" onchange="toggleClientFields('add')" style="display: none;">
                        <label for="add_type_both" class="biz-toggle">All / Mix</label>
                    </div>
                </div>

                <!-- Product Section -->
                <div id="add_product_section" class="form-group" style="grid-column: span 2;">
                    <label class="form-label">Product Name (Multiple)</label>
                    <select name="product_id[]" id="add_product_name" class="form-control select2-modal" multiple>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Project Section -->
                <div id="add_project_section" class="form-group" style="grid-column: span 2; display: none;">
                    <label class="form-label">Project Name (Multiple)</label>
                    <select name="project_id[]" id="add_project_id" class="form-control select2-modal" multiple>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}">{{ $project->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Service Section -->
                <div id="add_service_section" class="form-group" style="grid-column: span 2; display: none;">
                    <label class="form-label">Service Name (Multiple)</label>
                    <select name="service_id[]" id="add_service_id" class="form-control select2-modal" multiple>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}">{{ $service->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Remarks and Status -->
                <div class="form-group" style="grid-column: span 2;">
                    <label class="form-label">Remarks</label>
                    <textarea name="remarks" class="form-control" rows="3" placeholder="Additional details..."></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Attachment</label>
                    <input type="file" name="attachment" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
            </div>
            <div style="display: flex; gap: 1rem; margin-top: 2rem; border-top: 1px solid #eee; padding-top: 1.5rem;">
                <button type="submit" class="btn btn-primary" style="flex:1;">Save Client</button>
                <button type="button" onclick="closeAddModal()" class="btn" style="flex:1; background: #e5e7eb;">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Client Modal -->
<div id="editClientModal" style="display:none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); overflow-y: auto;">
    <div style="background: white; width: 800px; margin: 2rem auto; padding: 2rem; border-radius: 0.75rem; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);">
        <h3 style="margin-top:0; border-bottom: 1px solid #eee; padding-bottom: 1rem;">Edit Client</h3>
        <form id="editClientForm" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label class="form-label">Client Name</label>
                    <input type="text" name="name" id="edit_name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" id="edit_email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Phone <span style="color: red;">*</span></label>
                    <input type="text" name="phone" id="edit_phone" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Address <span style="color: red;">*</span></label>
                    <input type="text" name="address" id="edit_address" class="form-control" required>
                </div>

                <div class="section-divider" style="grid-column: span 2; margin-top: 1rem; border-top: 1px dashed #e2e8f0; padding-top: 1rem;">
                    <span style="font-size: 0.85rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Primary Contact Person</span>
                </div>
                <div class="form-group">
                    <label class="form-label">Name <span style="color: red;">*</span></label>
                    <input type="text" name="contact_person1_name" id="edit_contact_person1_name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Phone <span style="color: red;">*</span></label>
                    <input type="text" name="contact_person1_phone" id="edit_contact_person1_phone" class="form-control" required>
                </div>

                <div class="section-divider" style="grid-column: span 2; margin-top: 0.5rem; border-top: 1px dashed #e2e8f0; padding-top: 1rem;">
                    <span style="font-size: 0.85rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Secondary Contact Person</span>
                </div>
                <div class="form-group">
                    <label class="form-label">Name</label>
                    <input type="text" name="contact_person2_name" id="edit_contact_person2_name" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">Phone</label>
                    <input type="text" name="contact_person2_phone" id="edit_contact_person2_phone" class="form-control">
                </div>

                <!-- Business Type Toggles -->
                <div class="form-group" style="grid-column: span 2; margin-top: 1.5rem; margin-bottom: 1.5rem; background: #f8fafc; padding: 1.5rem; border-radius: 0.75rem; border: 1px solid #e2e8f0;">
                    <label class="form-label" style="text-align: center; display: block; margin-bottom: 1rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em;">Business Type</label>
                    <div style="display: flex; justify-content: center; gap: 1rem; flex-wrap: wrap;">
                        <input type="radio" name="business_type" id="edit_type_product" value="product" onchange="toggleClientFields('edit')" style="display: none;">
                        <label for="edit_type_product" class="biz-toggle">Product</label>

                        <input type="radio" name="business_type" id="edit_type_project" value="project" onchange="toggleClientFields('edit')" style="display: none;">
                        <label for="edit_type_project" class="biz-toggle">Project</label>

                         <input type="radio" name="business_type" id="edit_type_service" value="service" onchange="toggleClientFields('edit')" style="display: none;">
                        <label for="edit_type_service" class="biz-toggle">Service</label>

                        <input type="radio" name="business_type" id="edit_type_both" value="both" onchange="toggleClientFields('edit')" style="display: none;">
                        <label for="edit_type_both" class="biz-toggle">All</label>
                    </div>
                </div>

                <!-- Product Section -->
                <div id="edit_product_section" class="form-group" style="grid-column: span 2;">
                    <label class="form-label">Product Name (Multiple)</label>
                    <select name="product_id[]" id="edit_product_name_select2" class="form-control select2-modal" multiple>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Project Section -->
                <div id="edit_project_section" class="form-group" style="grid-column: span 2; display: none;">
                    <label class="form-label">Project Name (Multiple)</label>
                    <select name="project_id[]" id="edit_project_id_select2" class="form-control select2-modal" multiple>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}">{{ $project->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Service Section -->
                <div id="edit_service_section" class="form-group" style="grid-column: span 2; display: none;">
                    <label class="form-label">Service Name (Multiple)</label>
                    <select name="service_id[]" id="edit_service_id_select2" class="form-control select2-modal" multiple>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}">{{ $service->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group" style="grid-column: span 2;">
                    <label class="form-label">Notes on Businesses</label>
                    <textarea name="project_description" id="edit_project_description" class="form-control"></textarea>
                </div>

                <div class="form-group" style="grid-column: span 2;">
                    <label class="form-label">Remarks</label>
                    <textarea name="remarks" id="edit_remarks" class="form-control" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Attachment</label>
                    <input type="file" name="attachment" class="form-control">
                    <div id="edit_attachment_preview" style="margin-top: 0.5rem; font-size: 0.8rem; color: #64748b;"></div>
                </div>
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" id="edit_status" class="form-control">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
            </div>
            <div style="display: flex; gap: 1rem; margin-top: 2rem; border-top: 1px solid #eee; padding-top: 1.5rem;">
                <button type="submit" class="btn btn-primary" style="flex:1;">Update Client</button>
                <button type="button" onclick="closeEditModal()" class="btn" style="flex:1; background: #e5e7eb;">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- View Client Modal -->
<div id="viewClientModal" style="display:none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(8px); overflow-y: auto;">
    <div style="background: white; width: 700px; margin: 3rem auto; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); border: 1px solid rgba(255,255,255,0.1); overflow: hidden; font-family: 'Inter', sans-serif;">
        
        <!-- Header -->
        <div style="padding: 1.5rem 2rem; background: #fff; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: start;">
            <div>
                 <h2 style="margin: 0; font-size: 1.5rem; font-weight: 800; color: #0f172a;" id="view_name">Client Name</h2>
                 <div style="display: flex; gap: 1rem; margin-top: 0.5rem; align-items: center;">
                     <div id="view_status_badge"></div>
                     <span style="color: #cbd5e1;">|</span>
                     <div id="view_attachment_link" style="font-size: 0.85rem;"></div>
                 </div>
            </div>
            <button onclick="closeViewModal()" style="background: #f8fafc; border: none; width: 32px; height: 32px; border-radius: 50%; color: #64748b; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: background 0.2s;">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div style="padding: 2rem;">
            <!-- Contact Info Grid -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
                <!-- Email -->
                <div style="display: flex; gap: 0.75rem;">
                    <div style="width: 36px; height: 36px; background: #eff6ff; color: #3b82f6; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div>
                        <div style="font-size: 0.7rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; margin-bottom: 2px;">Email</div>
                        <div id="view_email" style="font-size: 0.95rem; font-weight: 600; color: #334155;"></div>
                    </div>
                </div>
                <!-- Phone -->
                 <div style="display: flex; gap: 0.75rem;">
                    <div style="width: 36px; height: 36px; background: #f0fdf4; color: #22c55e; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <i class="fas fa-phone"></i>
                    </div>
                    <div>
                        <div style="font-size: 0.7rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; margin-bottom: 2px;">Phone</div>
                        <div id="view_phone" style="font-size: 0.95rem; font-weight: 600; color: #334155;"></div>
                    </div>
                </div>
                <!-- Address -->
                 <div style="display: flex; gap: 0.75rem; grid-column: span 2;">
                    <div style="width: 36px; height: 36px; background: #f5f3ff; color: #8b5cf6; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div>
                        <div style="font-size: 0.7rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; margin-bottom: 2px;">Address</div>
                        <div id="view_address" style="font-size: 0.95rem; font-weight: 500; color: #334155;"></div>
                    </div>
                </div>
            </div>

            <!-- Divider -->
            <div style="height: 1px; background: #e2e8f0; margin-bottom: 2rem;"></div>

            <!-- POC Section -->
             <div style="margin-bottom: 2rem;">
                <h4 style="margin: 0 0 1rem 0; font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">Point of Contacts</h4>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <!-- Primary -->
                    <div style="border: 1px solid #e2e8f0; border-radius: 0.75rem; padding: 1rem; background: #f8fafc;">
                        <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                             <span style="width: 8px; height: 8px; background: #3b82f6; border-radius: 50%;"></span>
                             <span style="font-size: 0.7rem; font-weight: 700; color: #0f172a; text-transform: uppercase;">Primary</span>
                        </div>
                        <div id="view_contact1" style="font-weight: 700; color: #334155; font-size: 1rem;"></div>
                        <div id="view_phone1" style="color: #64748b; font-size: 0.85rem; margin-top: 0.25rem;"></div>
                    </div>
                    <!-- Secondary -->
                    <div style="border: 1px solid #e2e8f0; border-radius: 0.75rem; padding: 1rem; background: #fff;">
                        <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                             <span style="width: 8px; height: 8px; background: #cbd5e1; border-radius: 50%;"></span>
                             <span style="font-size: 0.7rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Secondary</span>
                        </div>
                        <div id="view_contact2" style="font-weight: 700; color: #334155; font-size: 1rem;"></div>
                        <div id="view_phone2" style="color: #64748b; font-size: 0.85rem; margin-top: 0.25rem;"></div>
                    </div>
                </div>
            </div>

            <!-- Engagements -->
             <div style="margin-bottom: 2rem;">
                <h4 style="margin: 0 0 1rem 0; font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">Active Businesses</h4>
                <div id="view_engagements"></div>
            </div>

            <!-- Remarks -->
            <div style="background: #fff; border-radius: 0.5rem;">
                <h4 style="margin: 0 0 0.5rem 0; font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase;">Remarks</h4>
                <p id="view_remarks" style="color: #475569; font-size: 0.9rem; line-height: 1.5; margin: 0;"></p>
            </div>

        </div>
    </div>
</div>

@endsection

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .form-label {
        color: #334155;
        font-weight: 600;
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }
    .view-item label { color: #64748b; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; display: block; margin-bottom: 0.25rem; }
    
    /* Reusing Select2 and Toggle Styles from other masters for consistency */
    .select2-container--default .select2-selection--multiple {
        border: 1px solid #e2e8f0;
        border-radius: 0.5rem;
        padding: 4px;
        min-height: 42px;
    }
    .select2-container--default.select2-container--focus .select2-selection--multiple {
        border-color: #3b82f6;
        box-shadow: 0 0 0 1px #3b82f6;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #f1f5f9;
        border: 1px solid #e2e8f0;
        color: #1e293b;
        border-radius: 4px;
        padding: 4px 8px;
        font-size: 0.85rem;
        margin-top: 4px;
    }

    .biz-toggle {
        padding: 0.65rem 1.8rem;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        color: #64748b;
        transition: 0.2s;
        min-width: 100px;
        text-align: center;
        margin-bottom: 0.5rem;
    }
    input[type="radio"]:checked + .biz-toggle {
        background: #0f172a;
        border-color: #0f172a;
        color: #fff;
    }
</style>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
<script>
    const productsData = @json($products);
    const projectsData = @json($projects);
    const servicesData = @json($services);

    $(document).ready(function() {
        CKEDITOR.replace('add_project_description'); // Ensure this ID exists or replace with remarks if needed
        CKEDITOR.replace('edit_project_description');

        const select2Options = { allowClear: true, width: '100%' };
        $('#add_product_name').select2({ ...select2Options, placeholder: "Select Products" });
        $('#add_project_id').select2({ ...select2Options, placeholder: "Select Projects" });
        $('#add_service_id').select2({ ...select2Options, placeholder: "Select Services" });

        $('#edit_product_name_select2').select2({ ...select2Options, placeholder: "Select Products" });
        $('#edit_project_id_select2').select2({ ...select2Options, placeholder: "Select Projects" });
        $('#edit_service_id_select2').select2({ ...select2Options, placeholder: "Select Services" });

        toggleClientFields('add');
    });

    function toggleClientFields(type) {
        let value;
        if (type === 'add') {
            const checkedRadio = document.querySelector('#addClientModal input[name="business_type"]:checked');
            value = checkedRadio ? checkedRadio.value : 'product';
            document.getElementById('add_product_section').style.display = (value === 'product' || value === 'both') ? 'block' : 'none';
            document.getElementById('add_project_section').style.display = (value === 'project' || value === 'both') ? 'grid' : 'none'; // Using grid to match previous layout if needed, or block
            document.getElementById('add_service_section').style.display = (value === 'service' || value === 'both') ? 'block' : 'none';
        } else {
            const checkedRadio = document.querySelector('#editClientModal input[name="business_type"]:checked');
            value = checkedRadio ? checkedRadio.value : 'product';
            document.getElementById('edit_product_section').style.display = (value === 'product' || value === 'both') ? 'block' : 'none';
            document.getElementById('edit_project_section').style.display = (value === 'project' || value === 'both') ? 'grid' : 'none';
            document.getElementById('edit_service_section').style.display = (value === 'service' || value === 'both') ? 'block' : 'none';
        }
    }

    function openAddModal() { document.getElementById('addClientModal').style.display = 'block'; }
    function closeAddModal() { document.getElementById('addClientModal').style.display = 'none'; }
    function closeEditModal() { document.getElementById('editClientModal').style.display = 'none'; }
    function closeViewModal() { document.getElementById('viewClientModal').style.display = 'none'; }

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
                
                const bizType = data.business_type || 'product';
                const radio = document.querySelector(`#editClientModal input[name="business_type"][value="${bizType}"]`);
                if(radio) radio.checked = true;
                
                // Trigger toggle to show/hide fields properly before setting values
                toggleClientFields('edit');

                $('#edit_product_name_select2').val(data.product_id || []).trigger('change');
                $('#edit_project_id_select2').val(data.project_id || []).trigger('change');
                $('#edit_service_id_select2').val(data.service_id || []).trigger('change');

                if (CKEDITOR.instances['edit_project_description']) {
                    CKEDITOR.instances['edit_project_description'].setData(data.project_description || '');
                }

                document.getElementById('edit_remarks').value = data.remarks || '';
                document.getElementById('edit_status').value = data.status;

                const attachPreview = document.getElementById('edit_attachment_preview');
                attachPreview.innerHTML = data.attachment ? `Current file: <a href="/storage/${data.attachment}" target="_blank">View</a>` : '';

                document.getElementById('editClientForm').action = `/admin/clients/${id}`;
                document.getElementById('editClientModal').style.display = 'block';
            });
    }

    function viewClient(id) {
        fetch(`/admin/clients/${id}/edit`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('view_name').innerText = data.name;
                document.getElementById('view_email').innerText = data.email;
                document.getElementById('view_phone').innerText = data.phone || 'N/A';
                document.getElementById('view_address').innerText = data.address || 'N/A';
                
                document.getElementById('view_contact1').innerText = data.contact_person1_name || 'N/A';
                document.getElementById('view_phone1').innerText = data.contact_person1_phone || '';
                document.getElementById('view_contact2').innerText = data.contact_person2_name || 'N/A';
                document.getElementById('view_phone2').innerText = data.contact_person2_phone || '';

                document.getElementById('view_remarks').innerText = data.remarks || 'No remarks.';

                const statusHtml = data.status == 1 
                    ? '<span class="badge" style="background: #eff6ff; color: #1d4ed8;">Active</span>' 
                    : '<span class="badge" style="background: #fef2f2; color: #dc2626;">Inactive</span>';
                document.getElementById('view_status_badge').innerHTML = statusHtml;

                const attachLink = document.getElementById('view_attachment_link');
                if (data.attachment) {
                    attachLink.innerHTML = `<a href="/storage/${data.attachment}" target="_blank" class="btn btn-primary" style="font-size:0.8rem; padding: 0.5rem 1rem;">View Attachment</a>`;
                } else {
                    attachLink.innerHTML = '';
                }
                
                // Products Mapping
                let productHtml = '';
                if(data.product_id && Array.isArray(data.product_id) && data.product_id.length > 0) {
                    const pIds = data.product_id.map(String); 
                    const clientProducts = productsData.filter(p => pIds.includes(String(p.id)));
                    productHtml = clientProducts.map(p => `<span style="display:inline-block; background: #eff6ff; color: #1d4ed8; padding: 0.25rem 0.75rem; border-radius: 999px; font-size: 0.75rem; font-weight: 600; margin-right: 0.5rem; margin-bottom: 0.5rem;">${p.name}</span>`).join('');
                }

                // Projects Mapping
                let projectHtml = '';
                if(data.project_id && Array.isArray(data.project_id) && data.project_id.length > 0) {
                    const pIds = data.project_id.map(String);
                    const clientProjects = projectsData.filter(p => pIds.includes(String(p.id)));
                    projectHtml = clientProjects.map(p => `<span style="display:inline-block; background: #f0fdf4; color: #15803d; padding: 0.25rem 0.75rem; border-radius: 999px; font-size: 0.75rem; font-weight: 600; margin-right: 0.5rem; margin-bottom: 0.5rem;">${p.name}</span>`).join('');
                }
                
                // Services Mapping
                let serviceHtml = '';
                if(data.service_id && Array.isArray(data.service_id) && data.service_id.length > 0) {
                    const pIds = data.service_id.map(String);
                    const clientServices = servicesData.filter(p => pIds.includes(String(p.id)));
                    serviceHtml = clientServices.map(p => `<span style="display:inline-block; background: #fdf4ff; color: #a21caf; padding: 0.25rem 0.75rem; border-radius: 999px; font-size: 0.75rem; font-weight: 600; margin-right: 0.5rem; margin-bottom: 0.5rem;">${p.name}</span>`).join('');
                }

                const engagementHtml = (productHtml || projectHtml || serviceHtml) 
                    ? `<div>
                        ${productHtml ? `<div style="font-size:0.75rem; color:#64748b; margin-bottom:0.25rem;">Products</div>${productHtml}` : ''}
                        ${projectHtml ? `<div style="font-size:0.75rem; color:#64748b; margin-top:0.5rem; margin-bottom:0.25rem;">Projects</div>${projectHtml}` : ''}
                        ${serviceHtml ? `<div style="font-size:0.75rem; color:#64748b; margin-top:0.5rem; margin-bottom:0.25rem;">Services</div>${serviceHtml}` : ''}
                       </div>` 
                    : '<span style="color:#94a3b8; font-style:italic;">No active engagements.</span>';

                document.getElementById('view_engagements').innerHTML = engagementHtml; 

                document.getElementById('viewClientModal').style.display = 'block';
            });
    }
</script>
@endsection
