@extends('layouts.backend.master')

@section('styles')
<style>
    .dashboard-card-header {
        padding: 2rem; 
        background: white; 
        border-bottom: 1px solid #f1f5f9; 
        display: flex; 
        justify-content: space-between; 
        align-items: center;
    }
    
    .ticket-table-container {
        padding: 1rem 2rem 2rem 2rem;
    }

    @media (max-width: 1024px) {
        .dashboard-card-header {
            flex-direction: column;
            align-items: flex-start !important;
            gap: 1.25rem;
            padding: 1.25rem !important;
        }
        .dashboard-card-header a.btn {
            width: 100%;
            justify-content: center;
        }
        .ticket-table-container {
            padding: 1rem !important;
            overflow-x: auto !important;
        }
        .ticket-table-container table {
            min-width: 800px !important;
        }
        /* Right-align Laravel pagination on mobile */
        .pagination-container {
            display: flex !important;
            justify-content: flex-end !important;
            padding: 1rem !important;
            width: 100% !important;
        }
        .pagination-container nav {
            width: 100% !important;
            display: flex !important;
            justify-content: flex-end !important;
        }
        .pagination-container ul {
            margin: 0 !important;
            justify-content: flex-end !important;
        }
    }
</style>
@endsection

@section('content')
<div class="card" style="border-radius: 1.5rem; border: none; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05); background: white;">
    <div class="card-header dashboard-card-header">
        <div>
            <h3 style="margin: 0; font-size: 1.5rem; font-weight: 800; color: #0f172a;">My Support Tickets</h3>
            <p style="margin: 0.25rem 0 0 0; color: #64748b; font-size: 0.875rem;">View and manage your support requests.</p>
        </div>
        <a href="{{ route('user.tickets.create') }}" class="btn btn-primary" style="padding: 0.75rem 1.5rem; border-radius: 0.75rem; font-weight: 700; display: flex; align-items: center; gap: 0.5rem; transition: 0.2s;">
            <i class="fas fa-plus-circle"></i> Raise New Ticket
        </a>
    </div>

    <div class="table-container ticket-table-container">
        <table style="width: 100%; border-collapse: separate; border-spacing: 0;">
            <thead>
                <tr style="background: #f8fafc;">
                    <th style="padding: 1.25rem 1rem; text-align: left; font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px solid #e2e8f0; border-top-left-radius: 0.75rem;">Ticket ID</th>
                    <th style="padding: 1.25rem 1rem; text-align: left; font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px solid #e2e8f0;">Product</th>
                    <th style="padding: 1.25rem 1rem; text-align: left; font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px solid #e2e8f0;">Project</th>
                    <th style="padding: 1.25rem 1rem; text-align: left; font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px solid #e2e8f0;">Service</th>
                    <th style="padding: 1.25rem 1rem; text-align: left; font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px solid #e2e8f0;">Subject</th>
                    <th style="padding: 1.25rem 1rem; text-align: left; font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px solid #e2e8f0;">Status</th>
                    <th style="padding: 1.25rem 1rem; text-align: left; font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px solid #e2e8f0;">Priority</th>
                    <th style="padding: 1.25rem 1rem; text-align: left; font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px solid #e2e8f0;">Date</th>
                    <th style="padding: 1.25rem 1rem; text-align: right; font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px solid #e2e8f0; border-top-right-radius: 0.75rem;">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tickets as $ticket)
                <tr style="transition: background 0.2s;">
                    <td style="padding: 1.25rem 1rem; border-bottom: 1px solid #f1f5f9; font-weight: 700; color: #1e293b;">
                        <a href="{{ route('ticket.show', $ticket->id) }}" style="color: #3b82f6; text-decoration: none;">#{{ $ticket->ticket_id }}</a>
                    </td>
                    <td style="padding: 1.25rem 1rem; border-bottom: 1px solid #f1f5f9; color: #475569;">{{ $ticket->product->name ?? '-' }}</td>
                    <td style="padding: 1.25rem 1rem; border-bottom: 1px solid #f1f5f9; color: #475569;">{{ $ticket->project->name ?? '-' }}</td>
                    <td style="padding: 1.25rem 1rem; border-bottom: 1px solid #f1f5f9; color: #475569;">{{ $ticket->service->name ?? '-' }}</td>
                    <td style="padding: 1.25rem 1rem; border-bottom: 1px solid #f1f5f9; color: #334155; font-weight: 500;">{{ $ticket->subject }}</td>
                    <td style="padding: 1.25rem 1rem; border-bottom: 1px solid #f1f5f9;">
                        @php
                            $statusColors = [
                                'open' => ['bg' => '#eff6ff', 'text' => '#1d4ed8'],
                                'in-progress' => ['bg' => '#fef3c7', 'text' => '#92400e'],
                                'resolved' => ['bg' => '#dcfce7', 'text' => '#15803d'],
                                'closed' => ['bg' => '#f1f5f9', 'text' => '#475569']
                            ];
                            $color = $statusColors[strtolower($ticket->status)] ?? ['bg' => '#f1f5f9', 'text' => '#475569'];
                        @endphp
                        <span style="padding: 0.4rem 0.85rem; border-radius: 2rem; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; background: {{ $color['bg'] }}; color: {{ $color['text'] }}; border: 1px solid transparent;">
                            {{ ucfirst($ticket->status) }}
                        </span>
                    </td>
                    <td style="padding: 1.25rem 1rem; border-bottom: 1px solid #f1f5f9;">
                        @php
                            $prioColors = [
                                'high' => ['bg' => '#fee2e2', 'text' => '#991b1b', 'border' => '#fecaca'],
                                'medium' => ['bg' => '#fffbeb', 'text' => '#92400e', 'border' => '#fef3c7'],
                                'low' => ['bg' => '#eff6ff', 'text' => '#1d4ed8', 'border' => '#dbeafe']
                            ];
                            $pColor = $prioColors[strtolower($ticket->priority)] ?? $prioColors['low'];
                        @endphp
                        <span style="padding: 0.3rem 0.75rem; border-radius: 0.5rem; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; background: {{ $pColor['bg'] }}; color: {{ $pColor['text'] }}; border: 1px solid {{ $pColor['border'] }};">
                            {{ ucfirst($ticket->priority) }}
                        </span>
                    </td>
                    <td style="padding: 1.25rem 1rem; border-bottom: 1px solid #f1f5f9; color: #64748b; font-size: 0.85rem;">{{ $ticket->created_at->format('M d, Y') }}</td>
                    <td style="padding: 1.25rem 1rem; border-bottom: 1px solid #f1f5f9; text-align: right;">
                        <div style="display: flex; gap: 0.5rem; justify-content: flex-end; align-items: center;">
                            <a href="{{ route('ticket.show', $ticket->id) }}" style="color: #3b82f6; font-weight: 700; text-decoration: none; font-size: 0.75rem; padding: 0.4rem 0.75rem; border: 1px solid #dbeafe; border-radius: 0.5rem; background: #eff6ff;">
                                <i class="fas fa-eye"></i> Details 
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" style="text-align: center; padding: 4rem 2rem;">
                        <div style="background: #f8fafc; width: 64px; height: 64px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; color: #94a3b8; font-size: 1.5rem;">
                            <i class="fas fa-ticket-alt"></i>
                        </div>
                        <h4 style="margin: 0; color: #1e293b; font-weight: 700;">No Tickets Found</h4>
                        <p style="margin: 0.5rem 0 1.5rem 0; color: #64748b; font-size: 0.875rem;">You haven't raised any support tickets yet.</p>
                        <a href="{{ route('user.tickets.create') }}" class="btn btn-primary" style="padding: 0.65rem 1.25rem; border-radius: 0.5rem; font-weight: 700;">Raise Your First Ticket</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($tickets->hasPages())
    <div class="pagination-container" style="padding: 1.5rem 2rem; border-top: 1px solid #f1f5f9;">
        {{ $tickets->links() }}
    </div>
    @endif
</div>
@endsection
