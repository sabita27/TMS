@extends('layouts.backend.master')

@section('page_title', 'My Support Tickets')
@section('header_height', '85px')
@section('header_padding', '0 2.5rem')

@section('content')
<div class="card" style="border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.04);">
    <div class="card-header" style="padding: 2rem; border-bottom: 2px solid #f8fafc;">
        <div>
            <h3 class="card-title" style="font-size: 1.5rem; font-weight: 800; color: #0f172a;">Ticket Management</h3>
            <p style="margin: 0.25rem 0 0 0; color: #64748b; font-size: 0.95rem;">Review, track, and manage your technical support history.</p>
        </div>
        <a href="{{ route('user.tickets.create') }}" class="btn btn-primary" style="padding: 0.75rem 1.5rem; border-radius: 0.75rem; font-weight: 700;">
            <i class="fas fa-plus-circle"></i> Create New Request
        </a>
    </div>

    <div class="table-container" style="padding: 1rem;">
        <table style="border-spacing: 0 0.75rem; border-collapse: separate;">
            <thead>
                <tr style="background: none;">
                    <th style="padding: 1rem; border: none; font-size: 0.75rem; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.1em;">Reference</th>
                    <th style="padding: 1rem; border: none; font-size: 0.75rem; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.1em;">Case Details</th>
                    <th style="padding: 1rem; border: none; font-size: 0.75rem; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.1em;">Product</th>
                    <th style="padding: 1rem; border: none; font-size: 0.75rem; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.1em;">Priority</th>
                    <th style="padding: 1rem; border: none; font-size: 0.75rem; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.1em;">Live Status</th>
                    <th style="padding: 1rem; border: none; font-size: 0.75rem; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.1em; text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tickets as $ticket)
                <tr class="ticket-row">
                    <td style="padding: 1.25rem 1rem; font-weight: 800; color: var(--primary-color);">{{ $ticket->ticket_id }}</td>
                    <td style="padding: 1.25rem 1rem;">
                        <div style="font-weight: 700; color: #1e293b; font-size: 1rem;">{{ $ticket->subject }}</div>
                        <div style="display: flex; gap: 1rem; align-items: center; margin-top: 0.25rem;">
                            <div style="font-size: 0.8rem; color: #94a3b8;">
                                <i class="far fa-clock"></i> Opened {{ $ticket->created_at->format('M d, Y') }}
                            </div>
                            @if($ticket->attachment)
                                <a href="{{ asset('storage/' . $ticket->attachment) }}" target="_blank" style="font-size: 0.8rem; color: var(--primary-color); text-decoration: none; font-weight: 700; display: flex; align-items: center; gap: 0.3rem;">
                                    <i class="fas fa-paperclip"></i> View Attachment
                                </a>
                            @endif
                        </div>
                    </td>
                    <td style="padding: 1.25rem 1rem;">
                        <div style="display: flex; align-items: center; gap: 0.5rem; font-weight: 600; color: #475569;">
                            <i class="fas fa-box" style="color: #cbd5e1;"></i>
                            {{ $ticket->product->name ?? 'General' }}
                        </div>
                    </td>
                    <td style="padding: 1.25rem 1rem;">
                        @php
                            $prioMap = [
                                'low' => ['bg' => '#f1f5f9', 'text' => '#475569'],
                                'medium' => ['bg' => '#fef3c7', 'text' => '#92400e'],
                                'high' => ['bg' => '#fee2e2', 'text' => '#991b1b']
                            ];
                            $p = $prioMap[$ticket->priority] ?? $prioMap['low'];
                        @endphp
                        <span style="background: {{ $p['bg'] }}; color: {{ $p['text'] }}; padding: 0.35rem 0.75rem; border-radius: 0.5rem; font-size: 0.7rem; font-weight: 800; text-transform: uppercase;">
                            {{ $ticket->priority }}
                        </span>
                    </td>
                    <td style="padding: 1.25rem 1rem;">
                        @php
                            $stMap = [
                                'open' => ['bg' => '#eef2ff', 'text' => '#4f46e5', 'dot' => '#4f46e5'],
                                'in-progress' => ['bg' => '#fffbeb', 'text' => '#d97706', 'dot' => '#f59e0b'],
                                'resolved' => ['bg' => '#ecfdf5', 'text' => '#059669', 'dot' => '#10b981'],
                                'closed' => ['bg' => '#f8fafc', 'text' => '#64748b', 'dot' => '#94a3b8'],
                            ];
                            $s = $stMap[$ticket->status] ?? $stMap['open'];
                        @endphp
                        <div style="background: {{ $s['bg'] }}; color: {{ $s['text'] }}; padding: 0.4rem 0.8rem; border-radius: 2rem; display: inline-flex; align-items: center; gap: 0.5rem; font-size: 0.75rem; font-weight: 700;">
                            <div style="width: 8px; height: 8px; border-radius: 50%; background: {{ $s['dot'] }};"></div>
                            {{ ucfirst($ticket->status) }}
                        </div>
                    </td>
                    <td style="padding: 1.25rem 1rem; text-align: right;">
                        @if($ticket->status !== 'closed')
                            <form action="{{ route('user.tickets.close', $ticket->id) }}" method="POST" onsubmit="return confirm('Attention: Are you sure you want to finalize and close this ticket? This action indicates the issue is resolved.')">
                                @csrf
                                <button type="submit" class="close-btn-premium">
                                    <i class="fas fa-check-circle"></i> Mark as Resolved
                                </button>
                            </form>
                        @else
                            <div style="display: flex; flex-direction: column; align-items: flex-end;">
                                <span style="font-size: 0.7rem; color: #94a3b8; font-weight: 700; text-transform: uppercase;">Closed</span>
                                <span style="font-size: 0.75rem; color: #475569;">{{ $ticket->closed_at ? $ticket->closed_at->diffForHumans() : 'N/A' }}</span>
                            </div>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 5rem 1rem;">
                        <img src="https://cdn-icons-png.flaticon.com/512/5058/5058432.png" style="width: 120px; opacity: 0.2; margin-bottom: 2rem;">
                        <h4 style="color: #64748b; font-weight: 800; font-size: 1.25rem;">No Active Support Cases</h4>
                        <p style="color: #94a3b8; margin-top: 0.5rem;">When you encounter an issue, raise a ticket to get assistance from our experts.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="padding: 1.5rem 2rem; border-top: 1px solid #f1f5f9;">
        {{ $tickets->links() }}
    </div>
</div>

<style>
    .ticket-row { background: #fff; transition: 0.2s; }
    .ticket-row:hover { background: #fafafa !important; transform: scale(1.002); box-shadow: 0 4px 15px rgba(0,0,0,0.02); }
    .ticket-row td { border-bottom: 1px solid #f8fafc; }
    
    .close-btn-premium { 
        background: #fff; border: 1.5px solid #fee2e2; color: #ef4444; 
        padding: 0.6rem 1.2rem; border-radius: 0.75rem; font-weight: 700; font-size: 0.8rem;
        cursor: pointer; transition: 0.3s; display: inline-flex; align-items: center; gap: 0.5rem;
    }
    .close-btn-premium:hover { background: #ef4444; color: #fff; border-color: #ef4444; box-shadow: 0 4px 12px rgba(239, 68, 68, 0.2); }
</style>
@endsection
