@extends('layouts.backend.master')

@section('styles')
<style>
    .conversation-wrapper {
        position: relative; 
        display: flex; 
        align-items: center; 
        border-bottom: 1px solid #f1f5f9;
        transition: background 0.3s;
    }

    .conversation-wrapper:hover {
        background: #f8fafc;
    }

    .conversation-card {
        background: white;
        border-radius: 1.5rem;
        border: none;
        box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05);
        overflow: hidden;
    }

    .conversation-list {
        display: flex;
        flex-direction: column;
    }

    .conversation-item {
        padding: 1.5rem 2rem;
        display: flex;
        align-items: center;
        gap: 1.5rem;
        transition: 0.3s;
        text-decoration: none;
        flex-grow: 1;
        min-width: 0;
    }

    .user-avatar-init {
        width: 54px;
        height: 54px;
        background: #3b82f6;
        border-radius: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.25rem;
        font-weight: 800;
        flex-shrink: 0;
        box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.2);
    }

    .convo-content {
        flex-grow: 1;
        min-width: 0;
    }

    .convo-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 0.25rem;
    }

    .convo-name {
        font-weight: 800;
        color: #0f172a;
        font-size: 1rem;
        margin: 0;
    }

    .convo-meta {
        font-size: 0.75rem;
        color: #94a3b8;
        font-weight: 600;
    }

    .convo-subject {
        font-size: 0.9rem;
        font-weight: 700;
        color: #334155;
        margin-bottom: 0.25rem;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .convo-last-msg {
        font-size: 0.85rem;
        color: #64748b;
        margin: 0;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .convo-status-area {
        text-align: right; 
        display: flex; 
        flex-direction: column; 
        gap: 0.5rem; 
        align-items: flex-end; 
        margin-right: 1.5rem;
        flex-shrink: 0;
    }

    .convo-actions {
        padding-right: 2rem;
        flex-shrink: 0;
    }

    .status-pill {
        padding: 0.35rem 0.85rem;
        border-radius: 2rem;
        font-size: 0.65rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        white-space: nowrap;
    }

    .solved-badge {
        background: #dcfce7;
        color: #15803d;
        border: 1px solid #bbf7d0;
    }

    .unsolved-badge {
        background: #fef2f2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }

    @media (max-width: 768px) {
        .hub-header h2 {
            font-size: 1.4rem !important;
        }
        
        .conversation-item {
            padding: 1.25rem;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .user-avatar-init {
            width: 44px;
            height: 44px;
            font-size: 1rem;
        }

        .convo-content {
            width: calc(100% - 60px);
        }

        .convo-header {
            flex-direction: column;
            gap: 2px;
        }

        .convo-meta {
            font-size: 0.7rem;
        }

        .convo-status-area {
            width: 100%;
            margin-top: 0.5rem;
            margin-right: 0;
            padding-left: 3.75rem; /* Align with content */
            text-align: left;
            align-items: center;
            flex-direction: row;
            flex-wrap: wrap;
            gap: 0.75rem;
        }

        .convo-status-area > div, .convo-status-area > span {
            margin: 0 !important;
        }

        .convo-actions {
            padding-right: 1rem;
            position: absolute;
            top: 1.25rem;
            right: 0;
        }

        .convo-actions button {
            width: 32px !important;
            height: 32px !important;
            border-radius: 0.5rem !important;
        }
    }

</style>
@endsection

@section('content')
<div class="hub-header" style="margin-bottom: 2rem;">
    <h2 style="margin: 0; font-size: 1.75rem; font-weight: 900; color: #0f172a;">Support Messaging Hub</h2>
    <p style="margin: 0.5rem 0 0 0; color: #64748b; font-size: 0.95rem;">Monitor and engage in all customer support conversations.</p>
</div>

<div class="conversation-card">
    <div class="conversation-list">
        @forelse($tickets as $ticket)
            @php
                $lastReply = $ticket->replies->first();
                $lastMsg = $lastReply ? $lastReply->replay : $ticket->description;
                $lastMsgSender = $lastReply ? ($lastReply->reply_by == 'staff' ? 'Staff: ' : 'User: ') : 'User: ';
                $isSolved = in_array(strtolower($ticket->status), ['resolved', 'closed']);
                
                // Color mapping for avatars
                $colors = ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899'];
                $avatarColor = $colors[$ticket->id % count($colors)];
            @endphp
            <div class="conversation-wrapper">
                <a href="{{ route('ticket.show', $ticket->id) }}" class="conversation-item">
                    <div class="user-avatar-init" style="background: {{ $avatarColor }};">
                        {{ strtoupper(substr($ticket->user->name, 0, 1)) }}
                    </div>
                    
                    <div class="convo-content">
                        <div class="convo-header">
                            <h4 class="convo-name">{{ $ticket->user->name }} <span style="color: #cbd5e1; font-weight: 400; font-size: 0.8rem; margin-left: 0.5rem;">#{{ $ticket->ticket_id }}</span></h4>
                            <span class="convo-meta">{{ $ticket->created_at->diffForHumans() }}</span>
                        </div>
                        
                        <div class="convo-subject">{{ $ticket->subject }}</div>
                        
                        <p class="convo-last-msg">
                            <span style="font-weight: 700; color: #475569;">{{ $lastMsgSender }}</span>
                            {!! \Illuminate\Support\Str::limit(strip_tags($lastMsg), 100) !!}
                        </p>
                    </div>

                    <div class="convo-status-area">
                        <span class="status-pill {{ $isSolved ? 'solved-badge' : 'unsolved-badge' }}">
                            <i class="fas {{ $isSolved ? 'fa-check-circle' : 'fa-clock' }}"></i>
                            {{ $isSolved ? 'Solved' : 'Active' }}
                        </span>
                        
                        @if($isSolved && $ticket->closed_at)
                            <div style="font-size: 0.65rem; color: #15803d; font-weight: 700; text-transform: uppercase;">
                                Resolved: {{ $ticket->closed_at->diffForHumans() }}
                            </div>
                        @endif

                        @if($ticket->assigned_to)
                            <div style="font-size: 0.65rem; color: #94a3b8; font-weight: 700; text-transform: uppercase;">
                                Resource: <span style="color: #64748b;">{{ $ticket->assignedStaff->name ?? 'Unknown' }}</span>
                            </div>
                        @else
                            <div style="font-size: 0.65rem; color: #ef4444; font-weight: 700; text-transform: uppercase;">
                                Unassigned
                            </div>
                        @endif
                    </div>
                </a>
                
                <div class="convo-actions">
                    <form action="{{ route('manager.tickets.delete', $ticket->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete this ticket and all its conversations?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="background: #fee2e2; color: #ef4444; border: 1px solid #fecaca; width: 40px; height: 40px; border-radius: 0.75rem; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: 0.2s;" onmouseover="this.style.background='#ef4444'; this.style.color='white';" onmouseout="this.style.background='#fee2e2'; this.style.color='#ef4444';" title="Delete Ticket">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </form>
                </div>
            </div>

        @empty
            <div style="padding: 5rem 2rem; text-align: center;">
                <div style="width: 80px; height: 80px; background: #f1f5f9; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; color: #cbd5e1; font-size: 2rem;">
                    <i class="fas fa-comments"></i>
                </div>
                <h3 style="margin: 0; color: #1e293b; font-weight: 800;">No Conversations Found</h3>
                <p style="margin: 0.5rem 0 0 0; color: #64748b;">Start by raising or assigning a support ticket.</p>
            </div>
        @endforelse
    </div>
</div>

<div style="margin-top: 2rem;">
    {{ $tickets->links() }}
</div>
@endsection
