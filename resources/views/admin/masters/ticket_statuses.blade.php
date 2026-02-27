@extends('layouts.backend.master')

@section('content')
<div class="card" style="max-width: 800px; margin: 0 auto;">
    <div class="card-header">
        <h3 class="card-title">Ticket Statuses</h3>
        <button onclick="document.getElementById('addStatusModal').style.display='block'" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Status
        </button>
    </div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Status Name</th>
                    <th>Color</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($statuses as $status)
                <tr>
                    <td>{{ $status->name }}</td>
                    <td>
                        <div style="width: 30px; height: 30px; background: {{ $status->color }}; border-radius: 4px; border: 1px solid #ddd;"></div>
                    </td>
                    <td><span class="badge badge-success">Active</span></td>
                    <td>
                        <div style="display: flex; gap: 0.5rem;">
                            <button onclick="editStatus({{ $status->id }})" class="btn btn-primary" style="padding: 0.4rem 0.7rem; font-size: 0.75rem;">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form action="{{ route('admin.ticket_statuses.delete', $status->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this status?')">
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
        {{ $statuses->links() }}
    </div>
</div>

<!-- Add Status Modal -->
<div id="addStatusModal" style="display:none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);">
    <div style="background: white; width: 400px; margin: 10% auto; padding: 2rem; border-radius: 0.5rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; border-bottom: 1px solid #eee; padding-bottom: 1rem;">
            <h3 style="margin: 0;">Add New ticket Status</h3>
            <button type="button" onclick="document.getElementById('addStatusModal').style.display='none'" style="background: none; border: none; font-size: 1.25rem; color: #94a3b8; cursor: pointer;">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form action="{{ route('admin.ticket_statuses.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Status Name</label>
                <input type="text" name="name" class="form-control" placeholder="e.g. Open" required>
            </div>
            <div class="form-group" style="margin-top: 1rem;">
                <label class="form-label">Status Color</label>
                <div style="display: flex; gap: 0.5rem;">
                    <input type="color" name="color" class="form-control" style="width: 50px; padding: 2px; height: 38px;" value="#4f46e5" required>
                    <input type="text" id="add_status_color_text" class="form-control" placeholder="#000000" readonly>
                </div>
            </div>
            <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                <button type="submit" class="btn btn-primary" style="flex:1;">Save Status</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Status Modal -->
<div id="editStatusModal" style="display:none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);">
    <div style="background: white; width: 400px; margin: 10% auto; padding: 2rem; border-radius: 0.5rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; border-bottom: 1px solid #eee; padding-bottom: 1rem;">
            <h3 style="margin: 0;">Edit Ticket Status</h3>
            <button type="button" onclick="document.getElementById('editStatusModal').style.display='none'" style="background: none; border: none; font-size: 1.25rem; color: #94a3b8; cursor: pointer;">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="editStatusForm" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label class="form-label">Status Name</label>
                <input type="text" name="name" id="edit_status_name" class="form-control" placeholder="e.g. Open" required>
            </div>
            <div class="form-group" style="margin-top: 1rem;">
                <label class="form-label">Status Color</label>
                <div style="display: flex; gap: 0.5rem;">
                    <input type="color" name="color" id="edit_status_color" class="form-control" style="width: 50px; padding: 2px; height: 38px;" required>
                    <input type="text" id="edit_status_color_text" class="form-control" placeholder="#000000" readonly>
                </div>
            </div>
            <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                <button type="submit" class="btn btn-primary" style="flex:1;">Update Status</button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Sync color picker with text input
    document.querySelector('input[name="color"]').addEventListener('input', function() {
        document.getElementById('add_status_color_text').value = this.value;
    });
    document.getElementById('add_status_color_text').value = document.querySelector('input[name="color"]').value;

    document.getElementById('edit_status_color').addEventListener('input', function() {
        document.getElementById('edit_status_color_text').value = this.value;
    });

    function editStatus(id) {
        fetch(`{{ url('admin/ticket-statuses') }}/${id}/edit`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('edit_status_name').value = data.name;
                document.getElementById('edit_status_color').value = data.color;
                document.getElementById('edit_status_color_text').value = data.color;
                document.getElementById('editStatusForm').action = `{{ url('admin/ticket-statuses') }}/${id}`;
                document.getElementById('editStatusModal').style.display = 'block';
            })
            .catch(error => console.error('Error:', error));
    }
</script>
@endsection
