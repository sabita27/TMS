@extends('layouts.backend.master')

@section('content')
<div class="dashboard-header" style="margin-bottom: 2rem;">
    <h1 style="font-size: 1.5rem; font-weight: 700; color: #111827;">Manager Dashboard</h1>
    <p style="color: #6b7280; font-size: 0.875rem;">Oversee all support activities and team performance.</p>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
    <div class="card" style="border-left: 4px solid #4f46e5;">
        <div style="color: #6b7280; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; margin-bottom: 0.5rem;">Total Tickets</div>
        <div style="font-size: 1.5rem; font-weight: 700;">{{ $stats['total_tickets'] }}</div>
    </div>
    <div class="card" style="border-left: 4px solid #ef4444;">
        <div style="color: #6b7280; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; margin-bottom: 0.5rem;">Unassigned</div>
        <div style="font-size: 1.5rem; font-weight: 700;">{{ $stats['unassigned_tickets'] }}</div>
    </div>
    <div class="card" style="border-left: 4px solid #f59e0b;">
        <div style="color: #6b7280; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; margin-bottom: 0.5rem;">In Progress</div>
        <div style="font-size: 1.5rem; font-weight: 700;">{{ $stats['in_progress'] }}</div>
    </div>
    <div class="card" style="border-left: 4px solid #10b981;">
        <div style="color: #6b7280; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; margin-bottom: 0.5rem;">Resolved</div>
        <div style="font-size: 1.5rem; font-weight: 700;">{{ $stats['resolved'] }}</div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Recent Activity</h3>
        <a href="{{ route('manager.tickets') }}" class="btn btn-primary" style="font-size: 0.75rem;">Manage All Tickets</a>
    </div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Subject</th>
                    <th>Status</th>
                    <th>Assigned To</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recent_tickets as $ticket)
                <tr>
                    <td>#{{ $ticket->ticket_id }}</td>
                    <td>{{ $ticket->user->name }}</td>
                    <td>{{ $ticket->subject }}</td>
                    <td>
                        <span class="badge {{ $ticket->status == 'open' ? 'badge-danger' : ($ticket->status == 'closed' ? 'badge-success' : 'badge-warning') }}">
                            {{ ucfirst($ticket->status) }}
                        </span>
                    </td>
                    <td>{{ $ticket->assignedStaff->name ?? 'Unassigned' }}</td>
                    <td>{{ $ticket->created_at->diffForHumans() }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
