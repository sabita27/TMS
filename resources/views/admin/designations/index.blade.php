@extends('layouts.backend.master')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Designation Management</h3>
        <button onclick="document.getElementById('addDesignationModal').style.display='block'" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Designation
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
                @foreach($designations as $designation)
                <tr>
                    <td>{{ $designation->id }}</td>
                    <td><span class="badge badge-info">{{ $designation->name }}</span></td>
                    <td>{{ $designation->created_at->format('M d, Y') }}</td>
                    <td>
                        <div style="display: flex; gap: 0.5rem;">
                            <button onclick="editDesignation({{ $designation->id }})" class="btn btn-primary" style="padding: 0.4rem 0.7rem; font-size: 0.75rem;">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <form action="{{ route('admin.designations.delete', $designation->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this designation?')">
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
        {{ $designations->links() }}
    </div>
</div>

<!-- Add Designation Modal -->
<div id="addDesignationModal" style="display:none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);">
    <div style="background: white; width: 400px; margin: 10% auto; padding: 2rem; border-radius: 0.5rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; border-bottom: 1px solid #eee; padding-bottom: 1rem;">
            <h3 style="margin: 0;">Add New Designation</h3>
            <button type="button" onclick="document.getElementById('addDesignationModal').style.display='none'" style="background: none; border: none; font-size: 1.25rem; color: #94a3b8; cursor: pointer;">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form action="{{ route('admin.designations.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Designation Name</label>
                <input type="text" name="name" class="form-control" placeholder="e.g. Senior Developer" required>
            </div>
            <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                <button type="submit" class="btn btn-primary" style="flex:1;">Save Designation</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Designation Modal -->
<div id="editDesignationModal" style="display:none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);">
    <div style="background: white; width: 400px; margin: 10% auto; padding: 2rem; border-radius: 0.5rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; border-bottom: 1px solid #eee; padding-bottom: 1rem;">
            <h3 style="margin: 0;">Edit Designation</h3>
            <button type="button" onclick="document.getElementById('editDesignationModal').style.display='none'" style="background: none; border: none; font-size: 1.25rem; color: #94a3b8; cursor: pointer;">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="editDesignationForm" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label class="form-label">Designation Name</label>
                <input type="text" name="name" id="edit_designation_name" class="form-control" required>
            </div>
            <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                <button type="submit" class="btn btn-primary" style="flex:1;">Update Designation</button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
    function editDesignation(id) {
        fetch(`{{ url('admin/designations') }}/${id}/edit`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('edit_designation_name').value = data.name;
                document.getElementById('editDesignationForm').action = `{{ url('admin/designations') }}/${id}`;
                document.getElementById('editDesignationModal').style.display = 'block';
            });
    }

    // Close modals when clicking outside
    window.onclick = function(event) {
        if (event.target == document.getElementById('addDesignationModal')) {
            document.getElementById('addDesignationModal').style.display = "none";
        }
        if (event.target == document.getElementById('editDesignationModal')) {
            document.getElementById('editDesignationModal').style.display = "none";
        }
    }
</script>
@endsection
