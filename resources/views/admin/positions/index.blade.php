@extends('layouts.backend.master')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Position Management</h3>
        <button onclick="document.getElementById('addPositionModal').style.display='block'" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Position
        </button>
    </div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Designation</th>
                    <th>Position</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($positions as $position)
                <tr>
                    <td>{{ $position->id }}</td>
                    <td><span class="badge badge-info">{{ $position->designation->name ?? 'N/A' }}</span></td>
                    <td><span class="badge badge-secondary" style="background: #e2e8f0; color: #475569;">{{ $position->name }}</span></td>
                    <td>{{ $position->created_at->format('M d, Y') }}</td>
                    <td>
                        <div style="display: flex; gap: 0.5rem;">
                            <button onclick="editPosition({{ $position->id }})" class="btn btn-primary" style="padding: 0.4rem 0.7rem; font-size: 0.75rem;">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <form action="{{ route('admin.positions.delete', $position->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this position?')">
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
        {{ $positions->links() }}
    </div>
</div>

<!-- Add Position Modal -->
<div id="addPositionModal" style="display:none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);">
    <div style="background: white; width: 400px; margin: 10% auto; padding: 2rem; border-radius: 0.5rem;">
        <h3 style="margin-top:0;">Add New Position</h3>
        <form action="{{ route('admin.positions.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Designation</label>
                <select name="designation_id" class="form-control" required>
                    <option value="">Select Designation</option>
                    @foreach($designations as $designation)
                        <option value="{{ $designation->id }}">{{ $designation->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Position Name</label>
                <input type="text" name="name" class="form-control" placeholder="e.g. Frontend Developer" required>
            </div>
            <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                <button type="submit" class="btn btn-primary" style="flex:1;">Save Position</button>
                <button type="button" onclick="document.getElementById('addPositionModal').style.display='none'" class="btn" style="flex:1; background: #e5e7eb;">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Position Modal -->
<div id="editPositionModal" style="display:none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);">
    <div style="background: white; width: 400px; margin: 10% auto; padding: 2rem; border-radius: 0.5rem;">
        <h3 style="margin-top:0;">Edit Position</h3>
        <form id="editPositionForm" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label class="form-label">Designation</label>
                <select name="designation_id" id="edit_designation_id" class="form-control" required>
                    <option value="">Select Designation</option>
                    @foreach($designations as $designation)
                        <option value="{{ $designation->id }}">{{ $designation->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Position Name</label>
                <input type="text" name="name" id="edit_position_name" class="form-control" required>
            </div>
            <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                <button type="submit" class="btn btn-primary" style="flex:1;">Update Position</button>
                <button type="button" onclick="document.getElementById('editPositionModal').style.display='none'" class="btn" style="flex:1; background: #e5e7eb;">Cancel</button>
            </div>
        </form>
    </div>
</div>

@section('scripts')
<script>
    function editPosition(id) {
        fetch(`/admin/positions/${id}/edit`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('edit_designation_id').value = data.designation_id;
                document.getElementById('edit_position_name').value = data.name;
                document.getElementById('editPositionForm').action = `/admin/positions/${id}`;
                document.getElementById('editPositionModal').style.display = 'block';
            });
    }

    // Close modals when clicking outside
    window.onclick = function(event) {
        if (event.target == document.getElementById('addPositionModal')) {
            document.getElementById('addPositionModal').style.display = "none";
        }
        if (event.target == document.getElementById('editPositionModal')) {
            document.getElementById('editPositionModal').style.display = "none";
        }
    }
</script>
@endsection
@endsection
