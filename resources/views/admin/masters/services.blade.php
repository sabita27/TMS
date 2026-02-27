@extends('layouts.backend.master')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Service Master</h3>
            <button onclick="openAddModal()" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Service
            </button>
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Service Name</th>
                        <th>Category</th>
                        <th>Sub-Category</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($services as $service)
                        <tr>
                            <td style="font-weight: 600;">{{ $service->name }}</td>
                            <td>
                                @if ($service->category)
                                    <span class="badge"
                                        style="background: #e0e7ff; color: #4338ca; font-weight: 600;">{{ $service->category->name }}</span>
                                @else
                                    <span style="color: #9ca3af; font-style: italic;">-</span>
                                @endif
                            </td>
                            <td>
                                @if ($service->subcategory)
                                    <span class="badge"
                                        style="background: #dbeafe; color: #1e40af; font-weight: 600;">{{ $service->subcategory->name }}</span>
                                @else
                                    <span style="color: #9ca3af; font-style: italic;">-</span>
                                @endif
                            </td>
                            <td>${{ number_format($service->price, 2) }}</td>
                            <td>
                                <span class="badge {{ $service->status ? 'badge-success' : 'badge-danger' }}">
                                    {{ $service->status ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <div style="display: flex; gap: 0.5rem;">
                                    <!-- View button not requested but could be added similarly -->
                                    <button onclick="editService({{ $service->id }})" class="btn btn-primary"
                                        style="padding: 0.4rem 0.7rem; font-size: 0.75rem;">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    @if(Auth::user()->hasRole('admin'))
                                        <form action="{{ route('admin.services.delete', $service->id) }}" method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this service?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger"
                                                style="padding: 0.4rem 0.7rem; font-size: 0.75rem;">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="margin-top: 1rem;">
            {{ $services->links() }}
        </div>
    </div>

    <!-- Add Service Modal -->
    <div id="addServiceModal"
        style="display:none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); overflow-y: auto;">
        <div
            style="background: white; width: 800px; margin: 2rem auto; padding: 2rem; border-radius: 0.75rem; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);">
            <div
                style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; border-bottom: 1px solid #eee; padding-bottom: 1rem;">
                <h3 style="margin: 0;">Add New Service</h3>
                <button type="button" onclick="closeAddModal()"
                    style="background: none; border: none; font-size: 1.25rem; color: #94a3b8; cursor: pointer;">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form action="{{ route('admin.services.store') }}" method="POST">
                @csrf
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group" style="grid-column: span 2;">
                        <label class="form-label">Service Name</label>
                        <input type="text" name="name" class="form-control" required placeholder="Enter service name">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Category</label>
                        <select name="category_id" id="add_category_id" class="form-control"
                            onchange="loadSubcategories(this.value, 'add_subcategory_id')">
                            <option value="">Select Category</option>
                            @foreach ($categories as $category)
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
                        <label class="form-label">Price</label>
                        <input type="number" step="0.01" name="price" class="form-control" placeholder="0.00">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Active Status</label>
                        <select name="status" class="form-control">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>

                    <div class="form-group" style="grid-column: span 2;">
                        <label class="form-label">Description</label>
                        <textarea name="description" id="add_service_description" class="form-control"></textarea>
                    </div>
                </div>
                <div style="display: flex; gap: 1rem; margin-top: 2rem; border-top: 1px solid #eee; padding-top: 1.5rem;">
                    <button type="submit" class="btn btn-primary"
                        style="flex:1; display:flex; justify-content:center; align-items:center;">
                        Save Service
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Service Modal -->
    <div id="editServiceModal"
        style="display:none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); overflow-y: auto;">
        <div
            style="background: white; width: 800px; margin: 2rem auto; padding: 2rem; border-radius: 0.75rem; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);">
            <div
                style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; border-bottom: 1px solid #eee; padding-bottom: 1rem;">
                <h3 style="margin: 0;">Edit Service</h3>
                <button type="button" onclick="closeEditModal()"
                    style="background: none; border: none; font-size: 1.25rem; color: #94a3b8; cursor: pointer;">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="editServiceForm" method="POST">
                @csrf
                @method('PUT')
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group" style="grid-column: span 2;">
                        <label class="form-label">Service Name</label>
                        <input type="text" name="name" id="edit_service_name" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Category</label>
                        <select name="category_id" id="edit_category_id" class="form-control"
                            onchange="loadSubcategories(this.value, 'edit_subcategory_id')">
                            <option value="">Select Category</option>
                            @foreach ($categories as $category)
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
                        <label class="form-label">Price</label>
                        <input type="number" step="0.01" name="price" id="edit_price" class="form-control">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Active Status</label>
                        <select name="status" id="edit_status" class="form-control">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>

                    <div class="form-group" style="grid-column: span 2;">
                        <label class="form-label">Description</label>
                        <textarea name="description" id="edit_service_description" class="form-control"></textarea>
                    </div>
                </div>
                <div style="display: flex; gap: 1rem; margin-top: 2rem; border-top: 1px solid #eee; padding-top: 1.5rem;">
                    <button type="submit" class="btn btn-primary" style="flex:1;">Update Service</button>
                </div>
            </form>
        </div>
    </div>
@endsection

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
            CKEDITOR.replace('add_service_description');
            CKEDITOR.replace('edit_service_description');
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
            document.getElementById('addServiceModal').style.display = 'block';
        }

        function closeAddModal() {
            document.getElementById('addServiceModal').style.display = 'none';
        }

        function closeEditModal() {
            document.getElementById('editServiceModal').style.display = 'none';
        }

        function editService(id) {
            fetch(`/admin/services/${id}/edit`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('edit_service_name').value = data.name;
                    document.getElementById('edit_price').value = data.price;
                    document.getElementById('edit_status').value = data.status;

                    // Set category
                    document.getElementById('edit_category_id').value = data.category_id || '';

                    // Load and set subcategory
                    if (data.category_id) {
                        loadSubcategories(data.category_id, 'edit_subcategory_id', data.subcategory_id);
                    }

                    if (CKEDITOR.instances['edit_service_description']) {
                        CKEDITOR.instances['edit_service_description'].setData(data.description || '');
                    }

                    document.getElementById('editServiceForm').action = `/admin/services/${id}`;
                    document.getElementById('editServiceModal').style.display = 'block';
                });
        }
    </script>
@endsection
