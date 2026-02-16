@extends('layouts.backend.master')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Role Management</h3>
        <button onclick="document.getElementById('addRoleModal').style.display='block'" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Role
        </button>
    </div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($roles as $role)
                <tr>
                    <td>{{ $role->id }}</td>
                    <td><span class="badge badge-info">{{ $role->name }}</span></td>
                    <td>{{ $role->created_at->format('M d, Y') }}</td>
                    <td>
                        <div style="display: flex; gap: 0.5rem;">
                            <button onclick="editRole({{ $role->id }})" class="btn btn-primary" style="padding: 0.4rem 0.7rem; font-size: 0.75rem;">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <form action="{{ route('admin.roles.delete', $role->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this role?')">
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
        {{ $roles->links() }}
    </div>
</div>

<!-- Add Role Modal -->
<div id="addRoleModal" style="display:none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);">
    <div style="background: white; width: 400px; margin: 10% auto; padding: 2rem; border-radius: 0.5rem;">
        <h3 style="margin-top:0;">Add New Role</h3>
        <form action="{{ route('admin.roles.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Role Name</label>
                <input type="text" name="name" class="form-control" placeholder="e.g. Moderator" required>
            </div>
            <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                <button type="submit" class="btn btn-primary" style="flex:1;">Save Role</button>
                <button type="button" onclick="document.getElementById('addRoleModal').style.display='none'" class="btn" style="flex:1; background: #e5e7eb;">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Role Modal -->
<div id="editRoleModal" style="display:none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);">
    <div style="background: white; width: 400px; margin: 10% auto; padding: 2rem; border-radius: 0.5rem;">
        <h3 style="margin-top:0;">Edit Role</h3>
        <form id="editRoleForm" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label class="form-label">Role Name</label>
                <input type="text" name="name" id="edit_role_name" class="form-control" required>
            </div>
            <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                <button type="submit" class="btn btn-primary" style="flex:1;">Update Role</button>
                <button type="button" onclick="document.getElementById('editRoleModal').style.display='none'" class="btn" style="flex:1; background: #e5e7eb;">Cancel</button>
            </div>
        </form>
    </div>
</div>

@section('scripts')
<script>
    function editRole(id) {
        fetch(`/admin/roles/${id}/edit`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('edit_role_name').value = data.name;
                document.getElementById('editRoleForm').action = `/admin/roles/${id}`;
                document.getElementById('editRoleModal').style.display = 'block';
            });
    }

    // Close modals when clicking outside
    window.onclick = function(event) {
        if (event.target == document.getElementById('addRoleModal')) {
            document.getElementById('addRoleModal').style.display = "none";
        }
        if (event.target == document.getElementById('editRoleModal')) {
            document.getElementById('editRoleModal').style.display = "none";
        }
    }
</script>
@endsection
@endsection
