@extends('layouts.backend.master')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Manage All Tickets</h3>
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
                    <th>Assign to Staff</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tickets as $ticket)
                <tr>
                    <td>
                        <a href="{{ route('ticket.show', $ticket->id) }}" style="color: #3b82f6; font-weight: 700; text-decoration: none;">#{{ $ticket->ticket_id }}</a>
                    </td>
                    <td>{{ $ticket->user->name }}</td>
                    <td>{{ $ticket->subject }}</td>
                    <td><span class="badge {{ $ticket->priority == 'high' ? 'badge-danger' : 'badge-warning' }}">{{ ucfirst($ticket->priority) }}</span></td>
                    <td>
                        @php
                            $statusColors = [
                                'open' => 'badge-danger',
                                'in-progress' => 'badge-warning',
                                'resolved' => 'badge-success',
                                'closed' => 'badge-secondary'
                            ];
                            $badgeClass = $statusColors[strtolower($ticket->status)] ?? 'badge-info';
                        @endphp
                        <span class="badge {{ $badgeClass }}">{{ ucfirst($ticket->status) }}</span>
                        {{-- @if($ticket->assignedStaff)
                            <div style="font-size: 0.7rem; color: #64748b; margin-top: 0.25rem;">
                                <i class="fas fa-user" style="font-size: 0.6rem;"></i> {{ $ticket->assignedStaff->name }}
                            </div>
                        @endif --}}
                    </td>
                    <td>
                        <div style="display: flex; gap: 0.5rem;">
                            <form action="{{ route('manager.tickets.assign', $ticket->id) }}" method="POST" style="display:flex; gap:0.25rem;">
                                @csrf
                                <select name="assigned_to" class="form-control" style="padding: 0.25rem; font-size: 0.75rem; width: 120px;">
                                    <option value="">Select Staff</option>
                                    @foreach($staffMembers as $staff)
                                        <option value="{{ $staff->id }}" {{ $ticket->assigned_to == $staff->id ? 'selected' : '' }}>{{ $staff->name }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-primary" title="Assign" style="padding: 0.25rem 0.5rem; background: #3b82f6;"><i class="fas fa-user-check"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div style="margin-top: 1rem;">
        {{ $tickets->links() }}
    </div>
</div>
@endsection
