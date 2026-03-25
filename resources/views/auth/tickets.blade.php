@extends('layouts.backend.master')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Manage All Tickets</h3>
    </div>
    <div>
        <table id="ticketsTable">
            <thead>
                <tr>
                    <th>Ticket ID</th>
                    <th>Customer</th>
                    <th>Subject</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Assign to Staff</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#ticketsTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('manager.tickets') }}",
            columns: [
                {data: 'ticket_id', name: 'ticket_id'},
                {data: 'customer', name: 'user.name'},
                {data: 'subject', name: 'subject'},
                {data: 'priority', name: 'priority'},
                {data: 'status', name: 'status'},
                {data: 'assign_to_staff', name: 'assign_to_staff', orderable: false, searchable: false}
            ],
            "pageLength": 15,
            "order": [[0, 'desc']],
            "dom": '<"top"Bf><"table-container"rt><"bottom"ip><"clear">',
            "buttons": [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ],
            "language": {
                "search": "_INPUT_",
                "searchPlaceholder": "Search tickets..."
            }
        });
    });
</script>
@endsection
