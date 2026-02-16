@extends('layouts.backend.master')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">User Management</h3>
        <button onclick="document.getElementById('addUserModal').style.display='block'" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New User
        </button>
    </div>

    @if($errors->any())
        <div style="background: #fee2e2; border: 1px solid #ef4444; color: #b91c1c; padding: 1rem; margin: 1rem; border-radius: 0.5rem;">
            <ul style="margin: 0; padding-left: 1.5rem;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('success'))
        <div style="background: #dcfce7; border: 1px solid #22c55e; color: #15803d; padding: 1rem; margin: 1rem; border-radius: 0.5rem;">
            {{ session('success') }}
        </div>
    @endif

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Joined</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->phone ?? '-' }}</td>
                    <td><span class="badge badge-info">{{ ucfirst($user->role->name ?? 'None') }}</span></td>
                    <td>
                        <span class="badge {{ $user->status ? 'badge-success' : 'badge-danger' }}">
                            {{ $user->status ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td>{{ $user->created_at->format('M d, Y') }}</td>
                    <td>
                        <div style="display: flex; gap: 0.5rem;">
                            <button onclick="editUser({{ $user->id }})" class="btn btn-primary" style="padding: 0.4rem 0.7rem; font-size: 0.75rem;">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?')">
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
        {{ $users->links() }}
    </div>
</div>

<!-- Add User Modal -->
<div id="addUserModal" style="display:none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); overflow-y: auto;">
    <div style="background: white; width: 400px; margin: 2rem auto; padding: 2rem; border-radius: 0.5rem; position: relative;">
        <h3 style="margin-top:0;">Add New User</h3>
        <form action="{{ route('admin.users.store') }}" method="POST" autocomplete="off">
            @csrf
            <div class="form-group">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control" placeholder="Enter user name" required>
            </div>
            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" placeholder="Enter user email" required autocomplete="off">
            </div>
            <div class="form-group">
                <label class="form-label">Phone</label>
                <input type="text" name="phone" class="form-control" placeholder="Enter user phone">
            </div>
            <div class="form-group">
                <label class="form-label">Role</label>
                <select name="role_id" id="add_role_id" class="form-control" onchange="toggleStaffFields(this.value, 'add')" required>
                    <option value="">Select Role</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                    @endforeach
                </select>
            </div>
            
            <div id="add_staff_fields" style="display: none;">
                <div class="form-group">
                    <label class="form-label">Designation</label>
                    <select name="designation_id" id="add_designation_id" class="form-control" onchange="fetchPositions(this.value, 'add')">
                        <option value="">Select Designation</option>
                        @foreach($designations as $designation)
                            <option value="{{ $designation->id }}">{{ $designation->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Position</label>
                    <select name="position_id" id="add_position_id" class="form-control">
                        <option value="">Select Position</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Enter secure password" required autocomplete="new-password">
            </div>
            <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                <button type="submit" class="btn btn-primary" style="flex:1;">Save User</button>
                <button type="button" onclick="document.getElementById('addUserModal').style.display='none'" class="btn" style="flex:1; background: #e5e7eb;">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit User Modal -->
<div id="editUserModal" style="display:none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); overflow-y: auto;">
    <div style="background: white; width: 400px; margin: 2rem auto; padding: 2rem; border-radius: 0.5rem; position: relative;">
        <h3 style="margin-top:0;">Edit User</h3>
        <form id="editUserForm" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label class="form-label">Name</label>
                <input type="text" name="name" id="edit_name" class="form-control" placeholder="Enter user name" required>
            </div>
            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" id="edit_email" class="form-control" placeholder="Enter user email" required>
            </div>
            <div class="form-group">
                <label class="form-label">Phone</label>
                <input type="text" name="phone" id="edit_phone" class="form-control" placeholder="Enter user phone">
            </div>
            <div class="form-group">
                <label class="form-label">Role</label>
                <select name="role_id" id="edit_role_id" class="form-control" onchange="toggleStaffFields(this.value, 'edit')" required>
                    <option value="">Select Role</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                    @endforeach
                </select>
            </div>
            
            <div id="edit_staff_fields" style="display: none;">
                <div class="form-group">
                    <label class="form-label">Designation</label>
                    <select name="designation_id" id="edit_designation_id" class="form-control" onchange="fetchPositions(this.value, 'edit')">
                        <option value="">Select Designation</option>
                        @foreach($designations as $designation)
                            <option value="{{ $designation->id }}">{{ $designation->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Position</label>
                    <select name="position_id" id="edit_position_id" class="form-control">
                        <option value="">Select Position</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Status</label>
                <select name="status" id="edit_status" class="form-control">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Password (Optional)</label>
                <input type="password" name="password" class="form-control" placeholder="Leave blank to keep current">
            </div>
            <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                <button type="submit" class="btn btn-primary" style="flex:1;">Update User</button>
                <button type="button" onclick="document.getElementById('editUserModal').style.display='none'" class="btn" style="flex:1; background: #e5e7eb;">Cancel</button>
            </div>
        </form>
    </div>
</div>

@section('scripts')
<script>
    function editUser(id) {
        fetch(`/admin/users/${id}/edit`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('edit_name').value = data.name;
                document.getElementById('edit_email').value = data.email;
                document.getElementById('edit_phone').value = data.phone || '';
                document.getElementById('edit_role_id').value = data.role_id;
                document.getElementById('edit_status').value = data.status;
                
                // Trigger logic for staff fields
                toggleStaffFields(data.role_id, 'edit');
                
                if (data.staff_detail) {
                    document.getElementById('edit_designation_id').value = data.staff_detail.designation_id;
                    // Fetch positions and set value
                    fetchPositions(data.staff_detail.designation_id, 'edit', data.staff_detail.position_id);
                }

                document.getElementById('editUserForm').action = `/admin/users/${id}`;
                document.getElementById('editUserModal').style.display = 'block';
            });
    }

    function toggleStaffFields(roleId, type) {
        const roleSelect = document.getElementById(`${type}_role_id`);
        // Find the selected option text to check if it is 'Staff'
        const selectedText = roleSelect.options[roleSelect.selectedIndex].text;
        const staffFields = document.getElementById(`${type}_staff_fields`);
        
        if (selectedText.trim().toLowerCase() === 'staff') {
            staffFields.style.display = 'block';
            // Add required attribute dynamically if needed
            document.getElementById(`${type}_designation_id`).required = true;
            document.getElementById(`${type}_position_id`).required = true;
        } else {
            staffFields.style.display = 'none';
            document.getElementById(`${type}_designation_id`).required = false;
            document.getElementById(`${type}_position_id`).required = false;
        }
    }

    function fetchPositions(designationId, type, selectedPositionId = null) {
        const positionSelect = document.getElementById(`${type}_position_id`);
        positionSelect.innerHTML = '<option value="">Loading...</option>';
        
        if (!designationId) {
            positionSelect.innerHTML = '<option value="">Select Position</option>';
            return;
        }

        console.log(`Fetching positions for designation ${designationId}`);

        fetch(`/get-positions/${designationId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log('Positions loaded:', data);
                positionSelect.innerHTML = '<option value="">Select Position</option>';
                data.forEach(position => {
                    const option = document.createElement('option');
                    option.value = position.id;
                    option.text = position.name;
                    if (selectedPositionId && position.id == selectedPositionId) {
                        option.selected = true;
                    }
                    positionSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error fetching positions:', error);
                positionSelect.innerHTML = '<option value="">Error loading positions</option>';
            });
    }
</script>
@endsection
@endsection
