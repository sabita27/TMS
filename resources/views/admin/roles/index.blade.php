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
                    <th>Permissions</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($roles as $role)
                <tr>
                    <td>{{ $role->id }}</td>
                    <td><span class="badge badge-info">{{ $role->name }}</span></td>
                    <td>
                        <div style="display: flex; flex-wrap: wrap; gap: 0.25rem; max-width: 400px;">
                            @foreach($role->permissions as $permission)
                                <span class="badge" style="background: #e0f2fe; color: #0369a1; font-size: 0.65rem;">{{ $permission->name }}</span>
                            @endforeach
                        </div>
                    </td>
                    <td>{{ $role->created_at->format('M d, Y') }}</td>
                    <td>
                        <div style="display: flex; gap: 0.5rem;">
                            <button onclick="editRole({{ $role->id }})" class="btn btn-primary" style="padding: 0.4rem 0.7rem; font-size: 0.75rem;">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            @if(strtolower($role->name) !== 'admin')
                            <form action="{{ route('admin.roles.delete', $role->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this role?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="padding: 0.4rem 0.7rem; font-size: 0.75rem;">
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
        {{ $roles->links() }}
    </div>
</div>

<!-- Add Role Modal -->
<div id="addRoleModal" style="display:none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); overflow-y: auto;">
    <div style="background: white; width: 600px; margin: 5% auto; padding: 2rem; border-radius: 0.5rem; box-shadow: 0 10px 25px rgba(0,0,0,0.1);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; border-bottom: 1px solid #eee; padding-bottom: 1rem;">
            <h3 style="margin: 0;">Add New Role</h3>
            <button type="button" onclick="document.getElementById('addRoleModal').style.display='none'" style="background: none; border: none; font-size: 1.25rem; color: #94a3b8; cursor: pointer;">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form action="{{ route('admin.roles.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Role Name</label>
                <input type="text" name="name" class="form-control" placeholder="e.g. Moderator" required>
            </div>
            
            <div style="margin-top: 1.5rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                    <label class="form-label" style="margin: 0;">Assign Permissions</label>
                    <div style="display: flex; gap: 0.5rem;">
                        <button type="button" onclick="toggleAllPerms('addRoleModal', true)" style="font-size: 0.7rem; padding: 0.2rem 0.5rem; background: #f1f5f9; border: 1px solid #e2e8f0; border-radius: 0.25rem; cursor: pointer;">All</button>
                        <button type="button" onclick="toggleAllPerms('addRoleModal', false)" style="font-size: 0.7rem; padding: 0.2rem 0.5rem; background: #f1f5f9; border: 1px solid #e2e8f0; border-radius: 0.25rem; cursor: pointer;">None</button>
                    </div>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; background: #f8fafc; padding: 1rem; border-radius: 0.5rem; max-height: 250px; overflow-y: auto;">
                    @foreach($permissions as $permission)
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" id="perm_add_{{ $permission->id }}" class="add-perm-checkbox">
                        <label for="perm_add_{{ $permission->id }}" style="font-size: 0.85rem; color: #475569; cursor: pointer;">{{ $permission->name }}</label>
                    </div>
                    @endforeach
                </div>
            </div>

             <div style="display: flex; gap: 1rem; margin-top: 2rem; border-top: 1px solid #eee; padding-top: 1.5rem;">
                    <button type="submit" class="btn btn-primary"
                        style="flex:1; display:flex; justify-content:center; align-items:center;">
                        Save Role
                    </button>
                </div>
        </form>
    </div>
</div>

<!-- Edit Role Modal -->
<div id="editRoleModal" style="display:none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); overflow-y: auto;">
    <div style="background: white; width: 600px; margin: 5% auto; padding: 2rem; border-radius: 0.5rem; box-shadow: 0 10px 25px rgba(0,0,0,0.1);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; border-bottom: 1px solid #eee; padding-bottom: 1rem;">
            <h3 style="margin: 0;">Edit Role</h3>
            <button type="button" onclick="document.getElementById('editRoleModal').style.display='none'" style="background: none; border: none; font-size: 1.25rem; color: #94a3b8; cursor: pointer;">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="editRoleForm" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label class="form-label">Role Name</label>
                <input type="text" name="name" id="edit_role_name" class="form-control" required>
            </div>

            <div style="margin-top: 1.5rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                    <label class="form-label" style="margin: 0;">Assign Permissions</label>
                    <div style="display: flex; gap: 0.5rem;">
                        <button type="button" onclick="toggleAllPerms('editRoleModal', true)" style="font-size: 0.7rem; padding: 0.2rem 0.5rem; background: #f1f5f9; border: 1px solid #e2e8f0; border-radius: 0.25rem; cursor: pointer;">All</button>
                        <button type="button" onclick="toggleAllPerms('editRoleModal', false)" style="font-size: 0.7rem; padding: 0.2rem 0.5rem; background: #f1f5f9; border: 1px solid #e2e8f0; border-radius: 0.25rem; cursor: pointer;">None</button>
                    </div>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; background: #f8fafc; padding: 1rem; border-radius: 0.5rem; max-height: 250px; overflow-y: auto;">
                    @foreach($permissions as $permission)
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" id="perm_edit_{{ $permission->id }}" class="edit-perm-checkbox">
                        <label for="perm_edit_{{ $permission->id }}" style="font-size: 0.85rem; color: #475569; cursor: pointer;">{{ $permission->name }}</label>
                    </div>
                    @endforeach
                </div>
            </div>

            <div style="display: flex; gap: 1rem; margin-top: 2rem; border-top: 1px solid #eee; padding-top: 1.5rem;">
                <button type="submit" class="btn btn-primary" style="flex:1;">Update Role</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function editRole(id) {
        // Clear all checkboxes
        document.querySelectorAll('.edit-perm-checkbox').forEach(cb => cb.checked = false);

        fetch(`{{ url('admin/roles') }}/${id}/edit`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('edit_role_name').value = data.name;
                
                // Check current permissions
                if (data.permissions) {
                    data.permissions.forEach(perm => {
                        const checkbox = Array.from(document.querySelectorAll('.edit-perm-checkbox'))
                            .find(cb => cb.value === perm.name);
                        if (checkbox) checkbox.checked = true;
                    });
                }

                document.getElementById('editRoleForm').action = `{{ url('admin/roles') }}/${id}`;
                document.getElementById('editRoleModal').style.display = 'block';
            });
    }

    function toggleAllPerms(modalId, checked) {
        const modal = document.getElementById(modalId);
        const checkboxes = modal.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(cb => cb.checked = checked);
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
