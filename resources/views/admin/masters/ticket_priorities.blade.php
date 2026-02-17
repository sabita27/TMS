@extends('layouts.backend.master')

@section('content')
<div class="card" style="max-width: 800px; margin: 0 auto;">
    <div class="card-header">
        <h3 class="card-title">Ticket Priorities</h3>
        <button onclick="document.getElementById('addPriorityModal').style.display='block'" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Priority
        </button>
    </div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Priority Name</th>
                    <th>Color</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($priorities as $priority)
                <tr>
                    <td>{{ $priority->name }}</td>
                    <td>
                        <div style="width: 30px; height: 30px; background: {{ $priority->color }}; border-radius: 4px; border: 1px solid #ddd;"></div>
                    </td>
                    <td><span class="badge badge-success">Active</span></td>
                    <td>
                        <div style="display: flex; gap: 0.5rem;">
                            <button onclick="editPriority({{ $priority->id }})" class="btn btn-primary" style="padding: 0.4rem 0.7rem; font-size: 0.75rem;">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form action="{{ route('admin.ticket_priorities.delete', $priority->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this priority?')">
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
        {{ $priorities->links() }}
    </div>
</div>

<!-- Add Priority Modal -->
<div id="addPriorityModal" style="display:none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);">
    <div style="background: white; width: 400px; margin: 10% auto; padding: 2rem; border-radius: 0.5rem;">
        <h3 style="margin-top:0;">Add New Priority</h3>
        <form action="{{ route('admin.ticket_priorities.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Priority Name</label>
                <input type="text" name="name" class="form-control" placeholder="e.g. High" required>
            </div>
            <div class="form-group" style="margin-top: 1rem;">
                <label class="form-label">Priority Color</label>
                <div style="display: flex; gap: 0.5rem;">
                    <input type="color" name="color" class="form-control" style="width: 50px; padding: 2px; height: 38px;" value="#ef4444" required>
                    <input type="text" id="add_priority_color_text" class="form-control" placeholder="#000000" readonly>
                </div>
            </div>
            <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                <button type="submit" class="btn btn-primary" style="flex:1;">Save</button>
                <button type="button" onclick="document.getElementById('addPriorityModal').style.display='none'" class="btn" style="flex:1; background: #e5e7eb;">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Priority Modal -->
<div id="editPriorityModal" style="display:none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);">
    <div style="background: white; width: 400px; margin: 10% auto; padding: 2rem; border-radius: 0.5rem;">
        <h3 style="margin-top:0;">Edit Priority</h3>
        <form id="editPriorityForm" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label class="form-label">Priority Name</label>
                <input type="text" name="name" id="edit_priority_name" class="form-control" placeholder="e.g. High" required>
            </div>
            <div class="form-group" style="margin-top: 1rem;">
                <label class="form-label">Priority Color</label>
                <div style="display: flex; gap: 0.5rem;">
                    <input type="color" name="color" id="edit_priority_color" class="form-control" style="width: 50px; padding: 2px; height: 38px;" required>
                    <input type="text" id="edit_priority_color_text" class="form-control" placeholder="#000000" readonly>
                </div>
            </div>
            <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                <button type="submit" class="btn btn-primary" style="flex:1;">Update</button>
                <button type="button" onclick="document.getElementById('editPriorityModal').style.display='none'" class="btn" style="flex:1; background: #e5e7eb;">Cancel</button>
            </div>
        </form>
    </div>
</div>

@section('scripts')
<script>
    // Sync color picker with text input
    document.querySelector('input[name="color"]').addEventListener('input', function() {
        document.getElementById('add_priority_color_text').value = this.value;
    });
    document.getElementById('add_priority_color_text').value = document.querySelector('input[name="color"]').value;

    document.getElementById('edit_priority_color').addEventListener('input', function() {
        document.getElementById('edit_priority_color_text').value = this.value;
    });

    function editPriority(id) {
        fetch(`/admin/ticket-priorities/${id}/edit`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('edit_priority_name').value = data.name;
                document.getElementById('edit_priority_color').value = data.color;
                document.getElementById('edit_priority_color_text').value = data.color;
                document.getElementById('editPriorityForm').action = `/admin/ticket-priorities/${id}`;
                document.getElementById('editPriorityModal').style.display = 'block';
            })
            .catch(error => console.error('Error:', error));
    }
</script>
@endsection
@endsection
