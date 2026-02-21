@extends('layouts.backend.master')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Permission Management</h3>
        <button onclick="document.getElementById('addPermissionModal').style.display='block'" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Permission
        </button>
    </div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Guard</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($permissions as $permission)
                <tr>
                    <td>{{ $permission->id }}</td>
                    <td><span class="badge" style="background: #fdf4ff; color: #a21caf;">{{ $permission->name }}</span></td>
                    <td><span class="badge badge-info">{{ $permission->guard_name }}</span></td>
                    <td>{{ $permission->created_at->format('M d, Y') }}</td>
                    <td>
                        <div style="display: flex; gap: 0.5rem;">
                            <button onclick="editPermission({{ $permission->id }})" class="btn btn-primary" style="padding: 0.4rem 0.7rem; font-size: 0.75rem;">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <form action="{{ route('admin.permissions.delete', $permission->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this permission?')">
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
        {{ $permissions->links() }}
    </div>
</div>

<!-- Add Permission Modal -->
<div id="addPermissionModal" style="display:none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);">
    <div style="background: white; width: 400px; margin: 10% auto; padding: 2rem; border-radius: 0.5rem; box-shadow: 0 10px 25px rgba(0,0,0,0.1);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; border-bottom: 1px solid #eee; padding-bottom: 1rem;">
            <h3 style="margin: 0;">Add New Permission</h3>
            <button type="button" onclick="document.getElementById('addPermissionModal').style.display='none'" style="background: none; border: none; font-size: 1.25rem; color: #94a3b8; cursor: pointer;">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form action="{{ route('admin.permissions.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Permission Name</label>
                <input type="text" name="name" class="form-control" placeholder="e.g. manage roles" required>
            </div>
            <div style="display: flex; gap: 1rem; margin-top: 2rem; border-top: 1px solid #eee; padding-top: 1.5rem;">
                <button type="submit" class="btn btn-primary" style="flex:1;">Save Permission</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Permission Modal -->
<div id="editPermissionModal" style="display:none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);">
    <div style="background: white; width: 400px; margin: 10% auto; padding: 2rem; border-radius: 0.5rem; box-shadow: 0 10px 25px rgba(0,0,0,0.1);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; border-bottom: 1px solid #eee; padding-bottom: 1rem;">
            <h3 style="margin: 0;">Edit Permission</h3>
            <button type="button" onclick="document.getElementById('editPermissionModal').style.display='none'" style="background: none; border: none; font-size: 1.25rem; color: #94a3b8; cursor: pointer;">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="editPermissionForm" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label class="form-label">Permission Name</label>
                <input type="text" name="name" id="edit_permission_name" class="form-control" required>
            </div>
            <div style="display: flex; gap: 1rem; margin-top: 2rem; border-top: 1px solid #eee; padding-top: 1.5rem;">
                <button type="submit" class="btn btn-primary" style="flex:1;">Update Permission</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function editPermission(id) {
        fetch(`/admin/permissions/${id}/edit`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('edit_permission_name').value = data.name;
                document.getElementById('editPermissionForm').action = `/admin/permissions/${id}`;
                document.getElementById('editPermissionModal').style.display = 'block';
            });
    }

    // Close modals when clicking outside
    window.onclick = function(event) {
        if (event.target == document.getElementById('addPermissionModal')) {
            document.getElementById('addPermissionModal').style.display = "none";
        }
        if (event.target == document.getElementById('editPermissionModal')) {
            document.getElementById('editPermissionModal').style.display = "none";
        }
    }
</script>
@endsection
