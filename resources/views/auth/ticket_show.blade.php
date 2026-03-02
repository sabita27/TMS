@extends('layouts.backend.master')

@section('content')
@php
    $backRoute = route('manager.tickets');
    if (Auth::user()->hasRole('user')) {
        $backRoute = route('user.tickets');
    } elseif (Auth::user()->hasRole('staff')) {
        $backRoute = route('staff.assigned_tickets');
    }
@endphp

<div style="margin-bottom: 1.5rem; display: flex; align-items: center; justify-content: space-between;">
    <a href="{{ $backRoute }}" style="display: flex; align-items: center; gap: 0.5rem; color: #64748b; text-decoration: none; font-weight: 600; font-size: 0.9rem; transition: 0.2s;" onmouseover="this.style.color='#3b82f6';" onmouseout="this.style.color='#64748b';">
        <i class="fas fa-arrow-left"></i> Back to List
    </a>
</div>

<div class="card" style="max-width: 1000px; margin: 0 auto; border-radius: 1.5rem; border: none; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05); overflow: hidden;">
    <div class="card-header" style="background: white; padding: 2rem; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center;">
        <div style="display: flex; align-items: center; gap: 1rem;">
            <div style="width: 48px; height: 48px; background: #eff6ff; border-radius: 1rem; display: flex; align-items: center; justify-content: center; color: #3b82f6;">
                <i class="fas fa-ticket-alt fa-lg"></i>
            </div>
            <div>
                <h3 style="margin: 0; font-size: 1.25rem; font-weight: 800; color: #1e293b;">#{{ $ticket->ticket_id }}</h3>
                <p style="margin: 0; color: #64748b; font-size: 0.85rem;">Raised by {{ $ticket->user->name }} • {{ $ticket->created_at->diffForHumans() }}</p>
            </div>
        </div>
        <div style="display: flex; align-items: center; gap: 1.5rem;">
            @php
                $currentStatusName = strtolower($ticket->status);
                $statusColor = '#3b82f6'; // Default blue
                if(isset($ticketStatuses)) {
                    foreach($ticketStatuses as $stat) {
                        if(strtolower($stat->name) == $currentStatusName) {
                            $statusColor = $stat->color;
                            break;
                        }
                    }
                }
            @endphp
            <span style="padding: 0.5rem 1.25rem; border-radius: 2rem; font-size: 0.75rem; font-weight: 800; text-transform: uppercase; background: {{ $statusColor }}15; color: {{ $statusColor }}; border: 1.5px solid {{ $statusColor }}30;">
                <i class="fas fa-circle" style="font-size: 0.5rem; margin-right: 0.4rem; vertical-align: middle;"></i> {{ $ticket->status }}
            </span>
            <a href="{{ $backRoute }}" title="Close" style="color: #94a3b8; font-size: 1.25rem; transition: 0.2s; text-decoration: none;" onmouseover="this.style.color='#ef4444';" onmouseout="this.style.color='#94a3b8';">
                <i class="fas fa-times"></i>
            </a>
        </div>
    </div>

    <div class="card-body" style="padding: 2.5rem;">
        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 3rem;">
            {{-- Left Side: Ticket Content --}}
            <div>
                <div style="margin-bottom: 2.5rem;">
                    <label style="display: block; font-size: 0.7rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 1rem;">Subject</label>
                    <h2 style="margin: 0; font-size: 1.75rem; font-weight: 800; color: #0f172a; line-height: 1.3;">{{ $ticket->subject }}</h2>
                </div>

                <div style="margin-bottom: 2.5rem;">
                    <label style="display: block; font-size: 0.7rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 1rem;">Detailed Issue</label>
                    <div style="background: #f8fafc; padding: 2rem; border-radius: 1.25rem; color: #334155; line-height: 1.8; border: 1px solid #f1f5f9; font-size: 1rem;">
                        {!! $ticket->description !!}
                    </div>
                </div>

                @if($ticket->attachment)
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; font-size: 0.7rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 1rem;">Supporting File</label>
                    <a href="{{ asset('storage/' . $ticket->attachment) }}" target="_blank" style="display: inline-flex; align-items: center; gap: 0.75rem; padding: 1rem 1.5rem; background: white; border: 2px solid #e2e8f0; border-radius: 1rem; color: #1e293b; text-decoration: none; font-weight: 700; font-size: 0.9rem; transition: 0.2s;" onmouseover="this.style.borderColor='#3b82f6'; this.style.color='#3b82f6'; this.style.background='#eff6ff';" onmouseout="this.style.borderColor='#e2e8f0'; this.style.color='#1e293b'; this.style.background='white';">
                        <i class="fas fa-paperclip" style="font-size: 1.1rem;"></i> View Attachment
                    </a>
                </div>
                @endif
            </div>

            {{-- Right Side: Meta Data & Actions --}}
            <div>
                <div style="background: #f8fafc; border-radius: 1.5rem; border: 1px solid #f1f5f9; padding: 2rem; margin-bottom: 2rem;">
                    <h4 style="margin: 0 0 1.5rem 0; font-size: 0.95rem; font-weight: 800; color: #1e293b; border-bottom: 2px solid #e2e8f0; padding-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fas fa-info-circle" style="color: #64748b;"></i> Metadata
                    </h4>
                    
                    <div style="margin-bottom: 1.5rem;">
                        <span style="display: block; font-size: 0.65rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; margin-bottom: 0.35rem;">Assigned Staff</span>
                        <div style="display: flex; align-items: center; gap: 0.6rem;">
                            <div style="width: 32px; height: 32px; background: #e0e7ff; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #4338ca; font-size: 0.8rem; font-weight: 700;">
                                {{ strtoupper(substr($ticket->assignedStaff->name ?? '?', 0, 1)) }}
                            </div>
                            <span style="font-weight: 700; color: #334155;">{{ $ticket->assignedStaff->name ?? 'Unassigned' }}</span>
                        </div>
                    </div>

                    <div style="margin-bottom: 1.5rem;">
                        <span style="display: block; font-size: 0.65rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; margin-bottom: 0.35rem;">Category & Product</span>
                        <span style="font-weight: 700; color: #334155; display: block;">{{ $ticket->product->name ?? 'General' }}</span>
                        <span style="font-size: 0.8rem; color: #64748b;">{{ $ticket->category->name ?? 'Uncategorized' }}</span>
                    </div>

                    <div style="margin-bottom: 1.5rem;">
                        <span style="display: block; font-size: 0.65rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; margin-bottom: 0.35rem;">Priority Level</span>
                        @php
                            $p = [
                                'high' => ['bg' => '#fee2e2', 'text' => '#991b1b'],
                                'medium' => ['bg' => '#fef3c7', 'text' => '#92400e'],
                                'low' => ['bg' => '#e0e7ff', 'text' => '#3730a3']
                            ];
                            $pc = $p[strtolower($ticket->priority)] ?? ['bg' => '#f1f5f9', 'text' => '#475569'];
                        @endphp
                        <span style="display: inline-flex; align-items: center; padding: 0.4rem 0.85rem; border-radius: 0.75rem; font-size: 0.75rem; font-weight: 800; background: {{ $pc['bg'] }}; color: {{ $pc['text'] }};">
                            <i class="fas fa-bolt" style="margin-right: 0.4rem;"></i> {{ strtoupper($ticket->priority) }}
                        </span>
                    </div>
                </div>

                {{-- Solution Button for Staff --}}
                @if(Auth::user()->hasRole('staff') && $ticket->assigned_to == Auth::id() && strtolower($ticket->status) != 'resolved' && strtolower($ticket->status) != 'closed')
                <div style="margin-bottom: 2rem;">
                    <form action="{{ route('staff.tickets.solve', $ticket->id) }}" method="POST">
                        @csrf
                        <button type="submit" style="width: 100%; height: 60px; background: #10b981; color: white; border: none; border-radius: 1.25rem; font-size: 1.1rem; font-weight: 800; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.75rem; box-shadow: 0 10px 15px -3px rgba(16, 185, 129, 0.3); transition: 0.3s;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 15px 20px -3px rgba(16, 185, 129, 0.4)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 15px -3px rgba(16, 185, 129, 0.3)';">
                            <i class="fas fa-check-double"></i> Mark as Solved
                        </button>
                    </form>
                </div>
                @endif

                {{-- Action Panels --}}
                @if(Auth::user()->hasAnyRole(['admin', 'manager']))
                <div style="background: white; border-radius: 1.5rem; border: 2px solid #f1f5f9; padding: 1.75rem; margin-bottom: 2rem;">
                    <h4 style="margin: 0 0 1.25rem 0; font-size: 0.95rem; font-weight: 800; color: #1e293b;">Re-assign Ticket</h4>
                    <form action="{{ route('manager.tickets.assign', $ticket->id) }}" method="POST">
                        @csrf
                        <select name="assigned_to" class="form-control" style="width: 100%; margin-bottom: 1.25rem; border-radius: 0.75rem; height: 48px; border: 1.5px solid #e2e8f0; font-weight: 600;">
                            <option value="">Select Staff Member</option>
                            @foreach($staffMembers as $staff)
                                <option value="{{ $staff->id }}" {{ $ticket->assigned_to == $staff->id ? 'selected' : '' }}>{{ $staff->name }}</option>
                            @endforeach
                        </select>
                        <button type="submit" style="width: 100%; background: #3b82f6; color: white; border: none; padding: 1rem; border-radius: 0.75rem; font-weight: 800; cursor: pointer; transition: 0.2s;" onmouseover="this.style.background='#2563eb';" onmouseout="this.style.background='#3b82f6';">
                             Update Assignment
                        </button>
                    </form>
                </div>
                @endif

                @if(Auth::user()->hasAnyRole(['staff', 'admin', 'manager']))
                <div style="background: white; border-radius: 1.5rem; border: 2px solid #f1f5f9; padding: 1.75rem;">
                    <h4 style="margin: 0 0 1.25rem 0; font-size: 0.95rem; font-weight: 800; color: #1e293b;">Force Update Status</h4>
                    <form action="{{ route('staff.tickets.status', $ticket->id) }}" method="POST">
                        @csrf
                        <select name="status" class="form-control" style="width: 100%; margin-bottom: 1.25rem; border-radius: 0.75rem; height: 48px; border: 1.5px solid #e2e8f0; font-weight: 600;">
                            @foreach($ticketStatuses as $stat)
                                <option value="{{ $stat->name }}" {{ strtolower($ticket->status) == strtolower($stat->name) ? 'selected' : '' }}>{{ $stat->name }}</option>
                            @endforeach
                        </select>
                        <button type="submit" style="width: 100%; background: #64748b; color: white; border: none; padding: 1rem; border-radius: 0.75rem; font-weight: 800; cursor: pointer; transition: 0.2s;" onmouseover="this.style.background='#475569';" onmouseout="this.style.background='#64748b';">
                            Push Status Update
                        </button>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
