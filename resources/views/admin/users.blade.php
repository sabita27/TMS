@extends('layouts.backend.master')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">User Management</h3>
            <button onclick="document.getElementById('addUserModal').style.display='block'" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New User
            </button>
        </div>

        @if ($errors->any())
            <div id="validation-errors" class="alert-message"
                style="background: #fee2e2; border: 1px solid #ef4444; color: #b91c1c; padding: 1rem; margin: 1rem; border-radius: 0.5rem; transition: opacity 0.5s ease-out;">
                <ul style="margin: 0; padding-left: 1.5rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- @if (session('success'))
        <div style="background: #dcfce7; border: 1px solid #22c55e; color: #15803d; padding: 1rem; margin: 1rem; border-radius: 0.5rem;">
            {{ session('success') }}
        </div>
    @endif --}}

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
                    @foreach ($users as $user)
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
                                    <button onclick="viewUser({{ $user->id }})" class="btn"
                                        style="padding: 0.4rem 0.7rem; font-size: 0.75rem; background: #10b981; color: white;">
                                        <i class="fas fa-eye"></i> View
                                    </button>
                                    <button onclick="editUser({{ $user->id }})" class="btn btn-primary"
                                        style="padding: 0.4rem 0.7rem; font-size: 0.75rem;">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <form action="{{ route('admin.users.delete', $user->id) }}" method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this user?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger"
                                            style="padding: 0.4rem 0.7rem; font-size: 0.75rem;">
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
    <div id="addUserModal"
        style="display:none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); overflow-y: auto;">
        <div
            style="background: white; width: 400px; margin: 2rem auto; padding: 2rem; border-radius: 0.5rem; position: relative;">
            <div
                style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; border-bottom: 1px solid #eee; padding-bottom: 1rem;">
                <h3 style="margin: 0;">Add New User</h3>
                <button type="button" onclick="document.getElementById('addUserModal').style.display='none'"
                    style="background: none; border: none; font-size: 1.25rem; color: #94a3b8; cursor: pointer;">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form action="{{ route('admin.users.store') }}" method="POST" autocomplete="off">
                @csrf
                <div class="form-group">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" placeholder="Enter user name" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" placeholder="Enter user email" required
                        autocomplete="off">
                </div>
                <div class="form-group">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control" placeholder="Enter user phone">
                </div>
                <div class="form-group">
                    <label class="form-label">Role</label>
                    <select name="role_id" id="add_role_id" class="form-control" onchange="toggleFields(this, 'add')"
                        required>
                        <option value="">Select Role</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                        @endforeach
                    </select>
                </div>

                <div id="add_client_fields" style="display: none;">
                    <div class="form-group">
                        <label class="form-label">Client</label>
                        <select name="client_id" id="add_client_id" class="form-control">
                            <option value="">Select Client</option>
                            @foreach ($clients as $client)
                                <option value="{{ $client->id }}">{{ $client->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div id="add_staff_fields" style="display: none;">
                    <div class="form-group">
                        <label class="form-label">Designation</label>
                        <select name="designation_id" id="add_designation_id" class="form-control"
                            onchange="fetchPositions(this.value, 'add')">
                            <option value="">Select Designation</option>
                            @foreach ($designations as $designation)
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
                    <input type="password" name="password" class="form-control" placeholder="Enter secure password" required
                        autocomplete="new-password">
                </div>
                <div style="display: flex; gap: 1rem; margin-top: 2rem; border-top: 1px solid #eee; padding-top: 1.5rem;">
                    <button type="submit" class="btn btn-primary"
                        style="flex:1; display:flex; justify-content:center; align-items:center;">
                        Save User
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- View User Modal -->
    <div id="viewUserModal"
        style="display:none; position: fixed; z-index: 1100; left: 0; top: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(8px); overflow-y: auto;">
        <div
            style="background: white; width: 650px; margin: 5rem auto; border-radius: 1.25rem; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); overflow: hidden; animation: modalSlideUp 0.3s ease-out;">
            <!-- Header -->
            <div
                style="padding: 1.5rem 2rem; background: linear-gradient(135deg, #1e293b, #334155); color: white; display: flex; justify-content: space-between; align-items: center;">
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <div
                        style="width: 40px; height: 40px; background: rgba(255,255,255,0.1); border-radius: 0.75rem; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-user-circle" style="font-size: 1.5rem;"></i>
                    </div>
                    <div>
                        <h3 style="margin: 0; font-size: 1.125rem; font-weight: 700;">User Profile</h3>
                        <p style="margin: 0; font-size: 0.75rem; opacity: 0.8;">Full details and account status</p>
                    </div>
                </div>
                <button onclick="closeViewModal()"
                    style="background: none; border: none; color: white; cursor: pointer; font-size: 1.25rem; opacity: 0.7; transition: 0.2s;">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Body -->
            <div style="padding: 2rem;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                    <!-- Full Name -->
                    <div style="grid-column: span 2; border-bottom: 1px solid #f1f5f9; padding-bottom: 1rem;">
                        <label
                            style="display: block; font-size: 0.7rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.25rem;">Full
                            Name</label>
                        <div id="view_user_name" style="font-size: 1.25rem; font-weight: 800; color: #0f172a;"></div>
                    </div>

                    <!-- Contact Grid -->
                    <div>
                        <label
                            style="display: block; font-size: 0.7rem; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 0.25rem;">Email
                            Address</label>
                        <div id="view_user_email" style="font-weight: 600; color: #334155;"></div>
                    </div>
                    <div>
                        <label
                            style="display: block; font-size: 0.7rem; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 0.25rem;">Phone
                            Number</label>
                        <div id="view_user_phone" style="font-weight: 600; color: #334155;"></div>
                    </div>

                    <!-- Role & Status -->
                    <div style="background: #f8fafc; padding: 1rem; border-radius: 0.75rem; border: 1px solid #e2e8f0;">
                        <label
                            style="display: block; font-size: 0.7rem; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 0.5rem;">Account
                            Role</label>
                        <div id="view_user_role"></div>
                    </div>
                    <div style="background: #f8fafc; padding: 1rem; border-radius: 0.75rem; border: 1px solid #e2e8f0;">
                        <div id="view_user_status"></div>
                    </div>

                    <!-- Dynamic Fields: Client -->
                    <div id="view_client_section"
                        style="grid-column: span 2; display: none; background: #eef2ff; padding: 1.25rem; border-radius: 1rem; border: 1px solid #e0e7ff;">
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <div
                                style="width: 32px; height: 32px; background: #4f46e5; color: white; border-radius: 0.5rem; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-building" style="font-size: 0.875rem;"></i>
                            </div>
                            <div>
                                <label
                                    style="display: block; font-size: 0.7rem; font-weight: 800; color: #4338ca; text-transform: uppercase;">Associated
                                    Client</label>
                                <div id="view_user_client" style="font-weight: 700; color: #1e1b4b;"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Dynamic Fields: Staff -->
                    <div id="view_staff_section"
                        style="grid-column: span 2; display: none; background: #f0fdf4; padding: 1.25rem; border-radius: 1rem; border: 1px solid #dcfce7;">
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                <div
                                    style="width: 32px; height: 32px; background: #16a34a; color: white; border-radius: 0.5rem; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-id-badge" style="font-size: 0.875rem;"></i>
                                </div>
                                <div>
                                    <label
                                        style="display: block; font-size: 0.7rem; font-weight: 800; color: #15803d; text-transform: uppercase;">Designation</label>
                                    <div id="view_user_designation" style="font-weight: 700; color: #052e16;"></div>
                                </div>
                            </div>
                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                <div
                                    style="width: 32px; height: 32px; background: #16a34a; color: white; border-radius: 0.5rem; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-briefcase" style="font-size: 0.875rem;"></i>
                                </div>
                                <div>
                                    <label
                                        style="display: block; font-size: 0.7rem; font-weight: 800; color: #15803d; text-transform: uppercase;">Position</label>
                                    <div id="view_user_position" style="font-weight: 700; color: #052e16;"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Stats -->
                    <div
                        style="grid-column: span 2; display: flex; justify-content: center; gap: 2rem; border-top: 1px solid #f1f5f9; padding-top: 1.5rem; margin-top: 0.5rem;">
                        <div style="text-align: center;">
                            <span
                                style="display: block; font-size: 0.65rem; color: #94a3b8; font-weight: 700; text-transform: uppercase;">Account
                                Created</span>
                            <span id="view_user_joined"
                                style="font-size: 0.875rem; font-weight: 600; color: #475569;"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @keyframes modalSlideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

    <!-- Edit User Modal -->
    <div id="editUserModal"
        style="display:none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); overflow-y: auto;">
        <div
            style="background: white; width: 400px; margin: 2rem auto; padding: 2rem; border-radius: 0.5rem; position: relative;">
            <div
                style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; border-bottom: 1px solid #eee; padding-bottom: 1rem;">
                <h3 style="margin: 0;">Edit User</h3>
                <button type="button" onclick="document.getElementById('editUserModal').style.display='none'"
                    style="background: none; border: none; font-size: 1.25rem; color: #94a3b8; cursor: pointer;">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="editUserForm" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" id="edit_name" class="form-control"
                        placeholder="Enter user name" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" id="edit_email" class="form-control"
                        placeholder="Enter user email" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" id="edit_phone" class="form-control"
                        placeholder="Enter user phone">
                </div>
                <div class="form-group">
                    <label class="form-label">Role</label>
                    <select name="role_id" id="edit_role_id" class="form-control" onchange="toggleFields(this, 'edit')"
                        required>
                        <option value="">Select Role</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                        @endforeach
                    </select>
                </div>

                <div id="edit_client_fields" style="display: none;">
                    <div class="form-group">
                        <label class="form-label">Client</label>
                        <select name="client_id" id="edit_client_id" class="form-control">
                            <option value="">Select Client</option>
                            @foreach ($clients as $client)
                                <option value="{{ $client->id }}">{{ $client->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div id="edit_staff_fields" style="display: none;">
                    <div class="form-group">
                        <label class="form-label">Designation</label>
                        <select name="designation_id" id="edit_designation_id" class="form-control"
                            onchange="fetchPositions(this.value, 'edit')">
                            <option value="">Select Designation</option>
                            @foreach ($designations as $designation)
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
                    <input type="password" name="password" class="form-control"
                        placeholder="Leave blank to keep current">
                </div>
                <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                    <button type="submit" class="btn btn-primary" style="flex:1;">Update User</button>
                </div>
            </form>
        </div>
    </div>

@section('scripts')
    <script>
        function closeViewModal() {
            document.getElementById('viewUserModal').style.display = 'none';
        }

        function viewUser(id) {
            fetch(`/admin/users/${id}/edit`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('view_user_name').innerText = data.name;
                    document.getElementById('view_user_email').innerText = data.email;
                    document.getElementById('view_user_phone').innerText = data.phone || 'N/A';
                    document.getElementById('view_user_joined').innerText = new Date(data.created_at)
                        .toLocaleDateString('en-US', {
                            month: 'long',
                            day: 'numeric',
                            year: 'numeric'
                        });

                    // Professional Role & Status Badges
                    const roleName = data.role ? data.role.name : 'None';
                    document.getElementById('view_user_role').innerHTML =
                        `<span style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.4rem 1rem; background: #eff6ff; color: #1e40af; border: 1px solid #bfdbfe; border-radius: 2rem; font-size: 0.75rem; font-weight: 700; text-transform: uppercase;">${roleName}</span>`;

                    const statusHtml = data.status == 1 ?
                        '<span style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.4rem 1rem; background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; border-radius: 2rem; font-size: 0.75rem; font-weight: 700; text-transform: uppercase;"><span style="width: 8px; height: 8px; background: #22c55e; border-radius: 50%; display: inline-block;"></span> Active</span>' :
                        '<span style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.4rem 1rem; background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; border-radius: 2rem; font-size: 0.75rem; font-weight: 700; text-transform: uppercase;"><span style="width: 8px; height: 8px; background: #ef4444; border-radius: 50%; display: inline-block;"></span> Inactive</span>';
                    document.getElementById('view_user_status').innerHTML = statusHtml;

                    // Handle Sections
                    const clientSec = document.getElementById('view_client_section');
                    const staffSec = document.getElementById('view_staff_section');
                    clientSec.style.display = 'none';
                    staffSec.style.display = 'none';

                    if (roleName.toLowerCase() === 'user' && data.client_detail) {
                        clientSec.style.display = 'block';
                        document.getElementById('view_user_client').innerText = data.client_detail.client ? data
                            .client_detail.client.name : 'N/A';
                    } else if (roleName.toLowerCase() === 'staff' && data.staff_detail) {
                        staffSec.style.display = 'block';
                        document.getElementById('view_user_designation').innerText = data.staff_detail.designation ?
                            data.staff_detail.designation.name : 'N/A';
                        document.getElementById('view_user_position').innerText = data.staff_detail.position ? data
                            .staff_detail.position.name : 'N/A';
                    }

                    document.getElementById('viewUserModal').style.display = 'block';
                });
        }

        function editUser(id) {
            fetch(`/admin/users/${id}/edit`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('edit_name').value = data.name;
                    document.getElementById('edit_email').value = data.email;
                    document.getElementById('edit_phone').value = data.phone || '';
                    document.getElementById('edit_role_id').value = data.role_id;
                    document.getElementById('edit_status').value = data.status;

                    // Trigger logic for fields AFTER setting role_id
                    toggleFields(document.getElementById('edit_role_id'), 'edit');

                    if (data.client_detail) {
                        document.getElementById('edit_client_id').value = data.client_detail.client_id;
                    }

                    if (data.staff_detail) {
                        document.getElementById('edit_designation_id').value = data.staff_detail.designation_id;
                        // Fetch positions and set value
                        fetchPositions(data.staff_detail.designation_id, 'edit', data.staff_detail.position_id);
                    }

                    document.getElementById('editUserForm').action = `/admin/users/${id}`;
                    document.getElementById('editUserModal').style.display = 'block';
                });
        }

        function toggleFields(roleSelect, type) {
            const selectedText = roleSelect.options[roleSelect.selectedIndex].text.trim().toLowerCase();
            const staffFields = document.getElementById(`${type}_staff_fields`);
            const clientFields = document.getElementById(`${type}_client_fields`);
            // Reset both
            staffFields.style.display = 'none';
            clientFields.style.display = 'none';
            document.getElementById(`${type}_designation_id`).required = false;
            document.getElementById(`${type}_position_id`).required = false;
            document.getElementById(`${type}_client_id`).required = false;

            if (selectedText === 'staff') {
                staffFields.style.display = 'block';
                document.getElementById(`${type}_designation_id`).required = true;
                document.getElementById(`${type}_position_id`).required = true;
            } else if (selectedText === 'user') {
                clientFields.style.display = 'block';
                document.getElementById(`${type}_client_id`).required = true;
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
