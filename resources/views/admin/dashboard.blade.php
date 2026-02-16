@extends('layouts.backend.master')

@section('content')
<div class="dashboard-header" style="margin-bottom: 2rem;">
    <h1 style="font-size: 1.5rem; font-weight: 700; color: #111827;">Admin Dashboard</h1>
    <p style="color: #6b7280; font-size: 0.875rem;">Welcome back, Admin! Here's what's happening today.</p>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
    <!-- Stat Cards -->
    <div class="card" style="border-left: 4px solid #4f46e5;">
        <div style="color: #6b7280; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; margin-bottom: 0.5rem;">Total Users</div>
        <div style="font-size: 1.5rem; font-weight: 700;">{{ $stats['users'] }}</div>
    </div>
    <div class="card" style="border-left: 4px solid #10b981;">
        <div style="color: #6b7280; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; margin-bottom: 0.5rem;">Total Products</div>
        <div style="font-size: 1.5rem; font-weight: 700;">{{ $stats['products'] }}</div>
    </div>
    <div class="card" style="border-left: 4px solid #f59e0b;">
        <div style="color: #6b7280; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; margin-bottom: 0.5rem;">Total Tickets</div>
        <div style="font-size: 1.5rem; font-weight: 700;">{{ $stats['tickets'] }}</div>
    </div>
    <div class="card" style="border-left: 4px solid #ef4444;">
        <div style="color: #6b7280; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; margin-bottom: 0.5rem;">Open Tickets</div>
        <div style="font-size: 1.5rem; font-weight: 700;">{{ $stats['open_tickets'] }}</div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Recent Tickets</h3>
        <a href="#" class="btn btn-primary" style="font-size: 0.75rem;">View All</a>
    </div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Ticket ID</th>
                    <th>User</th>
                    <th>Product</th>
                    <th>Subject</th>
                    <th>Status</th>
                    <th>Priority</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recent_tickets as $ticket)
                <tr>
                    <td>#{{ $ticket->ticket_id }}</td>
                    <td>{{ $ticket->user->name }}</td>
                    <td>{{ $ticket->product->name ?? 'N/A' }}</td>
                    <td>{{ $ticket->subject }}</td>
                    <td>
                        <span class="badge {{ $ticket->status == 'open' ? 'badge-danger' : ($ticket->status == 'closed' ? 'badge-success' : 'badge-warning') }}">
                            {{ ucfirst($ticket->status) }}
                        </span>
                    </td>
                    <td>
                        <span class="badge {{ $ticket->priority == 'high' ? 'badge-danger' : ($ticket->priority == 'medium' ? 'badge-warning' : 'badge-info') }}">
                            {{ ucfirst($ticket->priority) }}
                        </span>
                    </td>
                    <td>{{ $ticket->created_at->format('M d, Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 2rem; color: #6b7280;">No tickets found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
