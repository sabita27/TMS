@extends('layouts.backend.master')

@section('content')
<div class="dashboard-header" style="margin-bottom: 2rem;">
    <h1 style="font-size: 1.5rem; font-weight: 700; color: #111827;">Staff Dashboard</h1>
    <p style="color: #6b7280; font-size: 0.875rem;">Your assigned tasks and support tickets.</p>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">My Assigned Tickets</h3>
    </div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Ticket ID</th>
                    <th>Customer</th>
                    <th>Subject</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($assigned_tickets as $ticket)
                <tr>
                    <td>#{{ $ticket->ticket_id }}</td>
                    <td>{{ $ticket->user->name }}</td>
                    <td>{{ $ticket->subject }}</td>
                    <td><span class="badge {{ $ticket->priority == 'high' ? 'badge-danger' : 'badge-warning' }}">{{ ucfirst($ticket->priority) }}</span></td>
                    <td><span class="badge badge-info">{{ ucfirst($ticket->status) }}</span></td>
                    <td>
                        <form action="{{ route('staff.tickets.status', $ticket->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <select name="status" onchange="this.form.submit()" class="form-control" style="padding: 0.25rem; font-size: 0.75rem; width: auto; display: inline-block;">
                                <option value="open" {{ $ticket->status == 'open' ? 'selected' : '' }}>Open</option>
                                <option value="in-progress" {{ $ticket->status == 'in-progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="resolved" {{ $ticket->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                <option value="closed" {{ $ticket->status == 'closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 2rem; color: #6b7280;">No tickets assigned to you yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top: 1rem;">
        {{ $assigned_tickets->links() }}
    </div>
</div>
@endsection
