@extends('layouts.backend.master')

@section('content')
<div class="card" style="border-radius: 1.5rem; border: none; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05); background: white;">
    <div class="card-header" style="padding: 2rem; background: white; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; border-radius: 1.5rem 1.5rem 0 0;">
        <div>
            <h3 style="margin: 0; font-size: 1.5rem; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 0.75rem;">
                <span style="width: 40px; height: 40px; background: #eff6ff; border-radius: 0.75rem; display: inline-flex; align-items: center; justify-content: center; color: #3b82f6;">
                    <i class="fas fa-clipboard-list"></i>
                </span>
                My Assigned Tickets
            </h3>
            <p style="margin: 0.25rem 0 0 0; color: #64748b; font-size: 0.875rem;">Tickets assigned to you by the manager. Solve them to keep the queue clear.</p>
        </div>
        <div style="background: #f8fafc; border-radius: 1rem; padding: 0.75rem 1.25rem; text-align: center; border: 1px solid #f1f5f9;">
            <span style="display: block; font-size: 1.75rem; font-weight: 800; color: #0f172a;">{{ $tickets->total() }}</span>
            <span style="font-size: 0.7rem; color: #64748b; font-weight: 700; text-transform: uppercase;">Total Assigned</span>
        </div>
    </div>

    @if(session('success'))
    <div style="margin: 1.5rem 2rem 0; padding: 1rem 1.5rem; background: #dcfce7; border: 1px solid #bbf7d0; border-radius: 0.75rem; color: #166534; font-weight: 600; display: flex; align-items: center; gap: 0.75rem;">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
    @endif

    <div style="overflow-x: auto; padding: 1.5rem 2rem 2rem;">
        @if($tickets->isEmpty())
        <div style="text-align: center; padding: 4rem 2rem;">
            <div style="width: 80px; height: 80px; background: #f8fafc; border-radius: 2rem; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; color: #94a3b8; font-size: 2rem;">
                <i class="fas fa-inbox"></i>
            </div>
            <h4 style="margin: 0; color: #1e293b; font-weight: 700;">No Tickets Assigned Yet</h4>
            <p style="margin: 0.5rem 0 0; color: #64748b;">The manager hasn't assigned any tickets to you yet. Check back soon!</p>
        </div>
        @else
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f8fafc;">
                    <th style="padding: 1rem 1.25rem; text-align: left; font-size: 0.7rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">Ticket ID</th>
                    <th style="padding: 1rem 1.25rem; text-align: left; font-size: 0.7rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">Customer</th>
                    <th style="padding: 1rem 1.25rem; text-align: left; font-size: 0.7rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">Subject</th>
                    <th style="padding: 1rem 1.25rem; text-align: left; font-size: 0.7rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">Status</th>
                    <th style="padding: 1rem 1.25rem; text-align: left; font-size: 0.7rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">Priority</th>
                    <th style="padding: 1rem 1.25rem; text-align: left; font-size: 0.7rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">Assigned On</th>
                    <th style="padding: 1rem 1.25rem; text-align: center; font-size: 0.7rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tickets as $ticket)
                @php
                    $statusName = strtolower($ticket->status);
                    $statusColor = '#3b82f6';
                    foreach($ticketStatuses as $stat) {
                        if(strtolower($stat->name) == $statusName) {
                            $statusColor = $stat->color;
                            break;
                        }
                    }
                    $priorityColors = [
                        'high'   => ['bg' => '#fee2e2', 'text' => '#991b1b'],
                        'medium' => ['bg' => '#fef3c7', 'text' => '#92400e'],
                        'low'    => ['bg' => '#e0e7ff', 'text' => '#3730a3'],
                    ];
                    $pc = $priorityColors[strtolower($ticket->priority)] ?? ['bg' => '#f1f5f9', 'text' => '#475569'];
                    $isSolved = in_array($statusName, ['resolved', 'closed']);
                @endphp
                <tr style="border-bottom: 1px solid #f1f5f9; {{ $isSolved ? 'opacity: 0.7;' : '' }}">
                    <td style="padding: 1.1rem 1.25rem; font-weight: 700;">
                        <a href="{{ route('ticket.show', $ticket->id) }}" style="color: #3b82f6; text-decoration: none; font-size: 0.875rem;">#{{ $ticket->ticket_id }}</a>
                    </td>
                    <td style="padding: 1.1rem 1.25rem; color: #475569; font-weight: 600; font-size: 0.875rem;">{{ $ticket->user->name }}</td>
                    <td style="padding: 1.1rem 1.25rem; color: #334155; font-weight: 500; font-size: 0.875rem; max-width: 250px;">
                        <span style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">{{ $ticket->subject }}</span>
                    </td>
                    <td style="padding: 1.1rem 1.25rem;">
                        <span style="padding: 0.35rem 0.85rem; border-radius: 2rem; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; background: {{ $statusColor }}15; color: {{ $statusColor }}; border: 1.5px solid {{ $statusColor }}30;">
                            {{ $ticket->status }}
                        </span>
                    </td>
                    <td style="padding: 1.1rem 1.25rem;">
                        <span style="padding: 0.35rem 0.75rem; border-radius: 0.5rem; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; background: {{ $pc['bg'] }}; color: {{ $pc['text'] }};">
                            {{ ucfirst($ticket->priority) }}
                        </span>
                    </td>
                    <td style="padding: 1.1rem 1.25rem; color: #64748b; font-size: 0.8rem;">
                        {{ $ticket->updated_at->format('M d, Y') }}<br>
                        <span style="font-size: 0.7rem; color: #94a3b8;">{{ $ticket->updated_at->diffForHumans() }}</span>
                    </td>
                    <td style="padding: 1.1rem 1.25rem; text-align: center;">
                        <div style="display: flex; gap: 0.5rem; justify-content: center; align-items: center; flex-wrap: wrap;">
                            <a href="{{ route('ticket.show', $ticket->id) }}" style="display: inline-flex; align-items: center; gap: 0.35rem; padding: 0.45rem 1rem; background: #eff6ff; color: #3b82f6; border-radius: 0.6rem; font-size: 0.75rem; font-weight: 700; text-decoration: none; border: 1px solid #dbeafe; transition: 0.2s;" onmouseover="this.style.background='#3b82f6'; this.style.color='white';" onmouseout="this.style.background='#eff6ff'; this.style.color='#3b82f6';">
                                <i class="fas fa-eye"></i> View
                            </a>
                            @if(!$isSolved)
                            <form action="{{ route('staff.tickets.solve', $ticket->id) }}" method="POST" style="margin: 0;">
                                @csrf
                                <button type="submit" style="display: inline-flex; align-items: center; gap: 0.35rem; padding: 0.45rem 1rem; background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; border-radius: 0.6rem; font-size: 0.75rem; font-weight: 700; cursor: pointer; transition: 0.2s;" onmouseover="this.style.background='#10b981'; this.style.color='white';" onmouseout="this.style.background='#dcfce7'; this.style.color='#166534';">
                                    <i class="fas fa-check-double"></i> Solve
                                </button>
                            </form>
                            @else
                            <span style="font-size: 0.7rem; color: #10b981; font-weight: 700; display: flex; align-items: center; gap: 0.3rem;">
                                <i class="fas fa-check-circle"></i> Done
                            </span>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>

    @if($tickets->hasPages())
    <div style="padding: 1.5rem 2rem; border-top: 1px solid #f1f5f9;">
        {{ $tickets->links() }}
    </div>
    @endif
</div>
@endsection
