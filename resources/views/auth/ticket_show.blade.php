@extends('layouts.backend.master')

@section('styles')
<style>
    .ticket-view-card {
        max-width: 1100px;
        margin: 0 auto;
        background: white;
        border-radius: 1.75rem;
        border: none;
        box-shadow: 0 20px 25px -5px rgba(0,0,0,0.05), 0 10px 10px -5px rgba(0,0,0,0.04);
        overflow: hidden;
        animation: slideUp 0.5s ease-out;
    }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .meta-label {
        font-size: 0.65rem;
        font-weight: 800;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        margin-bottom: 0.5rem;
        display: block;
    }

    .meta-value {
        font-weight: 700;
        color: #1e293b;
        font-size: 0.95rem;
    }

    .status-badge {
        padding: 0.6rem 1.5rem;
        border-radius: 2rem;
        font-size: 0.75rem;
        font-weight: 800;
        text-transform: uppercase;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
    }

    .description-box {
        background: #fcfdfe;
        padding: 2.5rem;
        border-radius: 1.5rem;
        border: 1px solid #eef2f6;
        color: #334155;
        line-height: 1.8;
        font-size: 1.05rem;
        position: relative;
    }

    .description-box::after {
        content: '"';
        position: absolute;
        top: 1rem;
        left: 1rem;
        font-family: serif;
        font-size: 4rem;
        color: #e2e8f0;
        line-height: 1;
        opacity: 0.5;
    }

    .meta-sidebar {
        background: #f8fafc;
        border-left: 1px solid #f1f5f9;
        padding: 2.5rem;
    }

    .action-panel {
        background: white;
        border-radius: 1.25rem;
        padding: 1.5rem;
        border: 1px solid #e2e8f0;
        margin-bottom: 1.5rem;
        transition: 0.3s;
    }

    .action-panel:hover {
        border-color: #3b82f6;
        box-shadow: 0 10px 15px -3px rgba(59, 130, 246, 0.1);
    }

    .custom-btn {
        width: 100%;
        padding: 0.875rem;
        border-radius: 0.875rem;
        font-weight: 700;
        transition: 0.3s;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    @media (max-width: 992px) {
        .ticket-view-grid {
            grid-template-columns: 1fr !important;
        }
        .meta-sidebar {
            border-left: none;
            border-top: 1px solid #f1f5f9;
        }
    }
</style>
@endsection

@section('content')
@php
    $prevUrl = url()->previous();
    $currentUrl = url()->current();
    $defaultBack = route('manager.tickets');
    if (Auth::user()->hasRole('user')) {
        $defaultBack = route('user.tickets');
    } elseif (Auth::user()->hasRole('staff')) {
        $defaultBack = route('staff.assigned_tickets');
    }
    $backRoute = ($prevUrl != $currentUrl && str_contains($prevUrl, url('/'))) ? $prevUrl : $defaultBack;
@endphp

<div style="margin-bottom: 2rem; display: flex; align-items: center; justify-content: space-between;">
    <a href="{{ $backRoute }}" class="btn-back" style="display: inline-flex; align-items: center; gap: 0.6rem; color: #64748b; text-decoration: none; font-weight: 700; font-size: 0.95rem; background: white; padding: 0.6rem 1.25rem; border-radius: 1rem; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); transition: 0.3s;">
        <i class="fas fa-chevron-left" style="font-size: 0.8rem;"></i> Back to Tickets
    </a>
    
</div>

<div class="ticket-view-card">
    {{-- Header Section --}}
    <div style="background: linear-gradient(to right, #ffffff, #f9fafb); padding: 2.5rem; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 2rem;">
        <div style="display: flex; gap: 1.5rem;">
            <div style="width: 64px; height: 64px; background: #3b82f6; border-radius: 1.5rem; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.75rem; box-shadow: 0 10px 15px -3px rgba(59, 130, 246, 0.3);">
                <i class="fas fa-ticket-alt"></i>
            </div>
            <div>
                <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.5rem;">
                    <h3 style="margin: 0; font-size: 1.5rem; font-weight: 800; color: #0f172a;">#{{ $ticket->ticket_id }}</h3>
                    @php
                        $currentStatusName = strtolower($ticket->status);
                        $statusColor = '#3b82f6'; 
                        if(isset($ticketStatuses)) {
                            foreach($ticketStatuses as $stat) {
                                if(strtolower($stat->name) == $currentStatusName) {
                                    $statusColor = $stat->color;
                                    break;
                                }
                            }
                        }
                    @endphp
                    <span class="status-badge" style="background: {{ $statusColor }}15; color: {{ $statusColor }}; border: 1.5px solid {{ $statusColor }}30;">
                        <span style="width: 8px; height: 8px; background: {{ $statusColor }}; border-radius: 50%; display: inline-block;"></span>
                        {{ strtoupper($ticket->status) }}
                    </span>
                </div>
                <p style="margin: 0; color: #64748b; font-size: 0.95rem; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-user-circle"></i> {{ $ticket->user->name }} 
                    <span style="color: #cbd5e1;">•</span> 
                    <i class="far fa-clock"></i> {{ $ticket->created_at->diffForHumans() }}
                </p>
            </div>
        </div>
        
        <div style="text-align: right;">
            <span class="meta-label">Current Priority</span>
            @php
                $p = [
                    'high' => ['bg' => '#fee2e2', 'text' => '#991b1b', 'icon' => 'fa-fire'],
                    'medium' => ['bg' => '#fef3c7', 'text' => '#92400e', 'icon' => 'fa-bolt'],
                    'low' => ['bg' => '#e0e7ff', 'text' => '#3730a3', 'icon' => 'fa-leaf']
                ];
                $pc = $p[strtolower($ticket->priority)] ?? ['bg' => '#f1f5f9', 'text' => '#475569', 'icon' => 'fa-info-circle'];
            @endphp
            <div style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; background: {{ $pc['bg'] }}; color: {{ $pc['text'] }}; border-radius: 0.75rem; font-weight: 800; font-size: 0.85rem;">
                <i class="fas {{ $pc['icon'] }}"></i> {{ strtoupper($ticket->priority) }}
            </div>
        </div>
    </div>

    {{-- Main Grid --}}
    <div class="ticket-view-grid" style="display: grid; grid-template-columns: 1fr 340px;">
        {{-- Content Area --}}
        <div style="padding: 3rem;">
            <div style="margin-bottom: 2.5rem;">
                <span class="meta-label">Ticket Subject</span>
                <h2 style="margin: 0; font-size: 2rem; font-weight: 900; color: #0f172a; line-height: 1.2;">{{ $ticket->subject }}</h2>
            </div>

            <div class="description-box" style="margin-bottom: 2.5rem; background: #f8fafc; padding: 1.5rem; border-radius: 1rem; border-left: 4px solid #3b82f6;">
                <span style="font-size: 0.8rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 1rem; display: block;">Ticket Description</span>
                <div style="font-size: 1rem; color: #1e293b; line-height: 1.6;">
                    {!! $ticket->description !!}
                </div>
            </div>

            @if($ticket->attachment)
            <div style="margin-top: 1.5rem;">
                <span class="meta-label">Attached Resources</span>
                <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                    <a href="{{ asset('storage/' . $ticket->attachment) }}" target="_blank" style="text-decoration: none; display: flex; align-items: center; gap: 1rem; background: #ffffff; border: 1px solid #e2e8f0; padding: 0.75rem 1.25rem; border-radius: 0.75rem; min-width: 280px; transition: 0.2s;" onmouseover="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 4px 6px -1px rgba(0,0,0,0.05)';" onmouseout="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none';">
                        <div style="width: 40px; height: 40px; background: #fef2f2; color: #ef4444; border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; flex-shrink: 0;">
                            <i class="fas fa-file-pdf"></i>
                        </div>
                        <div style="overflow: hidden;">
                            <p style="margin: 0; font-weight: 700; color: #1e293b; font-size: 0.85rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                {{ basename($ticket->attachment) }}
                            </p>
                            <p style="margin: 0; font-size: 0.75rem; color: #94a3b8; font-weight: 600;">
                                {{ strtoupper(pathinfo($ticket->attachment, PATHINFO_EXTENSION)) }} • Support File
                            </p>
                        </div>
                    </a>
                </div>
            </div>
            @endif

            <hr style="margin: 3.5rem 0; border: 0; border-top: 1px solid #f1f5f9;">

            {{-- Conversation Log --}}
            <div style="margin-bottom: 1.5rem;">
                <span class="meta-label" style="margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem; color: #64748b;">
                    <i class="fas fa-comments"></i> Conversation Feed
                </span>
                
                <div style="background: #ffffff; border-radius: 1.25rem; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.05); overflow: hidden;">
                    
                    {{-- Resolution/Closed Banner --}}
                    @if(in_array(strtolower($ticket->status), ['resolved', 'closed']))
                        <div style="background: {{ strtolower($ticket->status) === 'resolved' ? '#f0fdf4' : '#f1f5f9' }}; padding: 1.25rem; border-bottom: 3px solid {{ strtolower($ticket->status) === 'resolved' ? '#22c55e' : '#94a3b8' }}; display: flex; align-items: center; justify-content: center; gap: 1rem; text-align: center;">
                            <div style="width: 48px; height: 48px; border-radius: 50%; background: {{ strtolower($ticket->status) === 'resolved' ? '#dcfce7' : '#e2e8f0' }}; display: flex; align-items: center; justify-content: center; color: {{ strtolower($ticket->status) === 'resolved' ? '#166534' : '#475569' }}; font-size: 1.25rem;">
                                <i class="fas {{ strtolower($ticket->status) === 'resolved' ? 'fa-check-circle' : 'fa-archive' }}"></i>
                            </div>
                            <div>
                                <h4 style="margin: 0; color: #0f172a; font-size: 1rem; font-weight: 800;">
                                    This ticket is {{ strtoupper($ticket->status) }}
                                </h4>
                                <p style="margin: 2px 0 0 0; color: #64748b; font-size: 0.85rem; font-weight: 600;">
                                    @if(strtolower($ticket->status) === 'resolved')
                                        This issue has been marked as completed by the support team.
                                    @else
                                        This conversation is archived and locked.
                                    @endif
                                </p>
                            </div>
                        </div>
                    @endif

                    <div id="chat-container" style="max-height: 550px; overflow-y: auto; display: flex; flex-direction: column; scroll-behavior: smooth;">
                        


                        {{-- Replies --}}
                        @php 
                            $currentDate = null;
                            $isStaffViewer = Auth::user()->hasAnyRole(['admin', 'manager', 'staff']);
                        @endphp

                        @foreach($ticket->replies as $reply)
                            @php
                                $replyDate = $reply->created_at->format('Y-m-d');
                                $showDateSeparator = ($replyDate !== $currentDate);
                                if($showDateSeparator) $currentDate = $replyDate;

                                $isStaffReply = ($reply->reply_by === 'staff');
                                $senderName = $isStaffReply ? ($reply->staff->name ?? 'Staff') : ($reply->user->name ?? 'User');
                                $avatarBg = $isStaffReply ? '4f46e5' : '3b82f6';
                                
                                $isSentByMe = false;
                                if ($isStaffReply && $reply->staff_id === Auth::id()) {
                                    $isSentByMe = true;
                                } elseif (!$isStaffReply && $reply->user_id === Auth::id()) {
                                    $isSentByMe = true;
                                }

                                // Alignment Logic:
                                // Staff Viewer: Staff Left, User Right
                                // User Viewer: User Left, Staff Right
                                $alignRight = ($isStaffViewer && !$isStaffReply) || (!$isStaffViewer && $isStaffReply);
                            @endphp

                            @if($showDateSeparator)
                                <div style="display: flex; align-items: center; justify-content: center; padding: 1.5rem 2rem; background: #fcfdfe;">
                                    <div style="flex-grow: 1; height: 1px; background: #f1f5f9;"></div>
                                    <span style="padding: 0 1.25rem; font-size: 0.7rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.1em; background: #fcfdfe;">
                                        {{ $reply->created_at->format('F d, Y') }}
                                    </span>
                                    <div style="flex-grow: 1; height: 1px; background: #f1f5f9;"></div>
                                </div>
                            @endif

                            <div style="display: flex; gap: 1.25rem; padding: 1.5rem; border-bottom: 1px solid #f1f5f9; transition: 0.2s; flex-direction: {{ $alignRight ? 'row-reverse' : 'row' }}; {{ $isSentByMe ? 'background: #fcfdfe;' : '' }}" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='{{ $isSentByMe ? '#fcfdfe' : 'transparent' }}'">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($senderName) }}&background={{ $avatarBg }}&color=fff&bold=true" style="width: 42px; height: 42px; border-radius: 50%; flex-shrink: 0; margin-top: 2px;" alt="Avatar">
                                <div style="flex-grow: 1; min-width: 0; text-align: {{ $alignRight ? 'right' : 'left' }};">
                                    <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 0.5rem; flex-direction: {{ $alignRight ? 'row-reverse' : 'row' }};">
                                        <div style="display: flex; align-items: center; gap: 0.5rem; flex-direction: {{ $alignRight ? 'row-reverse' : 'row' }};">
                                            <span style="font-weight: 700; color: #0f172a; font-size: 0.95rem;">
                                                {{ $isSentByMe ? 'You' : $senderName }}
                                            </span>
                                            @if($isStaffReply)
                                                <span style="font-size: 0.6rem; background: #eef2ff; color: #4f46e5; padding: 0.15rem 0.5rem; border-radius: 0.5rem; font-weight: 800; text-transform: uppercase;">Staff</span>
                                            @endif
                                        </div>
                                        <span style="font-size: 0.75rem; color: #94a3b8; font-weight: 600;">{{ $reply->created_at->format('g:i A') }}</span>
                                    </div>
                                    <div style="font-size: 0.95rem; color: #334155; line-height: 1.6; word-wrap: break-word; margin-bottom: 0.5rem;">
                                        {!! nl2br(e($reply->replay)) !!}
                                    </div>

                                    @if($reply->attachment)
                                        @php
                                            $extension = pathinfo($reply->attachment, PATHINFO_EXTENSION);
                                            $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                        @endphp
                                        
                                        <div style="display: flex; justify-content: {{ $alignRight ? 'flex-end' : 'flex-start' }}; margin-top: 0.5rem;">
                                            @if($isImage)
                                                <div style="max-width: 300px; border-radius: 0.75rem; overflow: hidden; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
                                                    <a href="{{ asset('storage/' . $reply->attachment) }}" target="_blank">
                                                        <img src="{{ asset('storage/' . $reply->attachment) }}" alt="Attachment" style="width: 100%; height: auto; display: block; transition: 0.3s;" onmouseover="this.style.opacity='0.9';" onmouseout="this.style.opacity='1';">
                                                    </a>
                                                </div>
                                            @else
                                                <a href="{{ asset('storage/' . $reply->attachment) }}" target="_blank" style="display: inline-flex; align-items: center; gap: 0.6rem; background: #fff; padding: 0.6rem 1rem; border-radius: 0.75rem; text-decoration: none; border: 1.5px solid #e2e8f0; transition: 0.2s; box-shadow: 0 1px 2px rgba(0,0,0,0.05);" onmouseover="this.style.borderColor='#3b82f6'; this.style.background='#f8fafc';" onmouseout="this.style.borderColor='#e2e8f0'; this.style.background='#fff';">
                                                    <div style="width: 32px; height: 32px; background: #f1f5f9; border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; color: #64748b;">
                                                        <i class="fas fa-file-alt" style="font-size: 0.9rem;"></i>
                                                    </div>
                                                    <div style="overflow: hidden; max-width: 200px;">
                                                        <p style="margin: 0; font-size: 0.8rem; font-weight: 700; color: #1e293b; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ basename($reply->attachment) }}</p>
                                                        <p style="margin: 0; font-size: 0.65rem; color: #94a3b8; text-transform: uppercase; font-weight: 700;">{{ strtoupper($extension) }} File</p>
                                                    </div>
                                                </a>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Integrated Reply Area (Disabled/Locked if Solved) --}}
            @if(!in_array(strtolower($ticket->status), ['closed', 'resolved']))
            <div style="background: #ffffff; border-radius: 1.25rem; border: 1px solid #e2e8f0; box-shadow: 0 4px 12px rgba(0,0,0,0.03); overflow: hidden;">
                <form id="reply-form" action="{{ route('ticket.reply', $ticket->id) }}" method="POST" enctype="multipart/form-data" style="margin: 0;">
                    @csrf
                    
                    {{-- Unified Input Container --}}
                    <div style="padding: 1.25rem;">
                        {{-- Live Media Preview (Integrated) --}}
                        <div id="reply-preview-area" style="display: none; margin-bottom: 1rem; padding: 0.75rem; background: #f8fafc; border-radius: 0.75rem; border: 1px solid #edf2f7;">
                            <div style="display: flex; align-items: center; gap: 1rem;">
                                <div id="preview-content" style="width: 60px; height: 60px; border-radius: 0.5rem; overflow: hidden; background: #fff; border: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                    <!-- Thumbnail injected here -->
                                </div>
                                <div style="flex-grow: 1; min-width: 0;">
                                    <div style="display: flex; justify-content: space-between; align-items: center;">
                                        <p id="preview-filename" style="margin: 0; font-size: 0.85rem; font-weight: 700; color: #1e293b; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"></p>
                                        <button type="button" onclick="clearAttachment()" style="background: #fee2e2; color: #ef4444; border: none; width: 22px; height: 22px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: 0.2s;">
                                            <i class="fas fa-times" style="font-size: 0.7rem;"></i>
                                        </button>
                                    </div>
                                    <p id="preview-filesize" style="margin: 2px 0 0 0; font-size: 0.7rem; color: #94a3b8; font-weight: 600;"></p>
                                </div>
                            </div>
                        </div>

                        <textarea id="reply-textarea" name="replay" rows="3" placeholder="Write a response..." style="width: 100%; box-sizing: border-box; border: none; padding: 0; font-family: inherit; font-size: 0.95rem; resize: none; outline: none; background: transparent; line-height: 1.6; display: block;" onkeydown="if(event.keyCode == 13 && !event.shiftKey) { event.preventDefault(); document.getElementById('reply-form').submit(); }"></textarea>
                    </div>

                    {{-- Actions Bar --}}
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.75rem 1.25rem; background: #fcfdfe; border-top: 1px solid #f1f5f9;">
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <div id="attachment-container" style="display: flex; align-items: center; gap: 0.5rem; background: #fff; padding: 0.4rem 0.8rem; border-radius: 0.5rem; border: 1px solid #cbd5e1; cursor: pointer; transition: 0.2s;" onmouseover="this.style.borderColor='#3b82f6'; this.style.background='#f8fafc';" onmouseout="this.style.borderColor='#cbd5e1'; this.style.background='#fff';">
                                <label for="reply-attachment" style="cursor: pointer; display: flex; align-items: center; gap: 0.4rem; color: #64748b; font-size: 0.8rem; font-weight: 700; margin: 0;">
                                    <i class="fas fa-paperclip" id="attachment-icon"></i> 
                                    <span id="attachment-text">Attach</span>
                                </label>
                                <input type="file" id="reply-attachment" name="attachment" style="display: none;" onchange="updateFileName(this)">
                                <button type="button" id="remove-attachment" onclick="clearAttachment()" style="display: none; background: none; border: none; padding: 0; cursor: pointer; color: #ef4444; font-size: 0.9rem;">
                                    <i class="fas fa-times-circle"></i>
                                </button>
                            </div>
                        </div>
                        <button type="submit" style="background: #0f172a; color: white; border: none; padding: 0.7rem 2rem; border-radius: 0.75rem; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 0.75rem; transition: 0.3s; font-size: 0.9rem;" onmouseover="this.style.background='#1e293b';" onmouseout="this.style.background='#0f172a';">
                            Send <i class="fas fa-paper-plane" style="font-size: 0.8rem;"></i>
                        </button>
                    </div>
                </form>
            </div>
            @elseif(strtolower($ticket->status) === 'resolved')
            <div style="padding: 2rem; background: #ffffff; border: 2px dashed #cbd5e1; border-radius: 1.25rem; text-align: center; color: #64748b;">
                <p style="margin: 0 0 1rem 0; font-size: 0.95rem; font-weight: 600;">If your issue is still not solved, you can re-open this ticket.</p>
                <form action="{{ route('staff.tickets.status', $ticket->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="status" value="In-Progress">
                    <button type="submit" style="background: #f8fafc; color: #0f172a; border: 1.5px solid #e2e8f0; padding: 0.6rem 2rem; border-radius: 0.75rem; font-weight: 800; cursor: pointer; transition: 0.2s;" onmouseover="this.style.background='#fff'; this.style.borderColor='#3b82f6'; this.style.color='#3b82f6'" onmouseout="this.style.background='#f8fafc'; this.style.borderColor='#e2e8f0'; this.style.color='#0f172a'">
                        <i class="fas fa-undo"></i> Re-open Ticket
                    </button>
                </form>
            </div>
            @else
            <div style="padding: 1.5rem; background: #fef2f2; border: 1px solid #fecaca; border-radius: 1.25rem; text-align: center; color: #991b1b; font-weight: 700; font-size: 0.9rem; display: flex; align-items: center; justify-content: center; gap: 0.75rem;">
                <i class="fas fa-lock"></i> This ticket is closed. No further replies are accepted.
            </div>
            @endif
        </div>

        {{-- Sidebar Area --}}
        <div class="meta-sidebar">
            <h4 style="margin: 0 0 2rem 0; font-size: 1.1rem; font-weight: 900; color: #1e293b; display: flex; align-items: center; gap: 0.75rem;">
                <i class="fas fa-swatchbook" style="color: #3b82f6;"></i> Ticket Assets
            </h4>

            <div style="display: grid; gap: 2rem;">
                <div>
                    <span class="meta-label">Service Owner</span>
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <div style="width: 40px; height: 40px; background: #cbd5e1; border-radius: 0.75rem; display: flex; align-items: center; justify-content: center; color: white; font-weight: 800;">
                            {{ strtoupper(substr($ticket->assignedStaff->name ?? '?', 0, 1)) }}
                        </div>
                        <div>
                            <p style="margin: 0; font-weight: 700; color: #334155;">{{ $ticket->assignedStaff->name ?? 'Unassigned' }}</p>
                            <p style="margin: 0; font-size: 0.75rem; color: #94a3b8;">Support Engineer</p>
                        </div>
                    </div>
                </div>

                @if($ticket->product)
                <div>
                    <span class="meta-label">Product Context</span>
                    <p class="meta-value" style="margin-bottom: 0.25rem;">{{ $ticket->product->name }}</p>
                    <p style="margin: 0; font-size: 0.8rem; color: #64748b;">System Module</p>
                </div>
                @endif

                @if($ticket->project || $ticket->service)
                <div>
                    <span class="meta-label">Project & Service</span>
                    <div style="background: white; border-radius: 1rem; padding: 1.25rem; border: 1px solid #eef2f6;">
                        @if($ticket->project)
                        <p style="margin: 0 0 0.5rem 0; font-weight: 800; color: #0f172a; font-size: 0.95rem;">
                            <i class="fas fa-project-diagram" style="color: #6366f1; margin-right: 0.4rem; font-size: 0.8rem;"></i>
                            {{ $ticket->project->name }}
                        </p>
                        @endif
                        @if($ticket->service)
                        <p style="margin: 0; font-weight: 600; color: #64748b; font-size: 0.85rem;">
                            <i class="fas fa-concierge-bell" style="color: #f59e0b; margin-right: 0.4rem; font-size: 0.8rem;"></i>
                            {{ $ticket->service->name }}
                        </p>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            {{-- Contextual Actions for Admin/Staff --}}
            @if(Auth::user()->hasAnyRole(['admin', 'manager', 'staff']))
            <div style="margin-top: 3.5rem;">
                <h4 style="margin: 0 0 1.5rem 0; font-size: 0.9rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em;">Console Management</h4>
                
                @if(in_array(strtolower($ticket->status), ['resolved', 'closed']))
                    <div style="padding: 1.5rem; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 1rem; text-align: center;">
                        <i class="fas fa-lock" style="color: #94a3b8; font-size: 1.5rem; margin-bottom: 0.75rem;"></i>
                        <p style="margin: 0; font-size: 0.85rem; font-weight: 600; color: #64748b; line-height: 1.4;">
                            Console is locked because the ticket is {{ strtoupper($ticket->status) }}.
                        </p>
                        {{-- Admins can still re-open if needed --}}
                        @if(Auth::user()->hasAnyRole(['admin', 'manager']))
                            <form action="{{ route('staff.tickets.status', $ticket->id) }}" method="POST" style="margin-top: 1rem;">
                                @csrf
                                <input type="hidden" name="status" value="In-Progress">
                                <button type="submit" style="background: none; border: none; color: #3b82f6; font-size: 0.8rem; font-weight: 700; cursor: pointer; text-decoration: underline;">
                                    Re-activate Management
                                </button>
                            </form>
                        @endif
                    </div>
                @else
                    {{-- Staff Solve Button --}}
                    @if(Auth::user()->hasRole('staff') && $ticket->assigned_to == Auth::id())
                    <div class="action-panel" style="background: #ecfdf5; border-color: #10b98130;">
                        <form action="{{ route('staff.tickets.solve', $ticket->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="custom-btn" style="background: #10b981; color: white; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);">
                                <i class="fas fa-check-circle"></i> Resolve Ticket
                            </button>
                        </form>
                    </div>
                    @endif

                    {{-- Management Assignment --}}
                    @if(Auth::user()->hasAnyRole(['admin', 'manager']))
                    <div class="action-panel">
                        <span class="meta-label" style="margin-bottom: 1rem;">Transfer Assignment</span>
                        <form action="{{ route('manager.tickets.assign', $ticket->id) }}" method="POST">
                            @csrf
                            <select name="assigned_to" style="width: 100%; height: 45px; border-radius: 0.75rem; border: 1.5px solid #e2e8f0; margin-bottom: 1rem; padding: 0 1rem; font-weight: 600; font-size: 0.85rem; background: #f8fafc;">
                                <option value="">Select Resource</option>
                                @foreach($staffMembers as $staff)
                                    <option value="{{ $staff->id }}" {{ $ticket->assigned_to == $staff->id ? 'selected' : '' }}>{{ $staff->name }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="custom-btn" style="background: #3b82f6; color: white;">
                                 Update Link
                            </button>
                        </form>
                    </div>
                    @endif

                    {{-- Admin Status Force --}}
                    <div class="action-panel">
                        <span class="meta-label" style="margin-bottom: 1rem;">Manual Status override</span>
                        <form action="{{ route('staff.tickets.status', $ticket->id) }}" method="POST">
                            @csrf
                            <select name="status" style="width: 100%; height: 45px; border-radius: 0.75rem; border: 1.5px solid #e2e8f0; margin-bottom: 1rem; padding: 0 1rem; font-weight: 600; font-size: 0.85rem; background: #f8fafc;">
                                @if(isset($ticketStatuses))
                                    @foreach($ticketStatuses as $stat)
                                        <option value="{{ $stat->name }}" {{ strtolower($ticket->status) == strtolower($stat->name) ? 'selected' : '' }}>{{ $stat->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <button type="submit" class="custom-btn" style="background: #64748b; color: white;">
                                 Force Update
                            </button>
                        </form>
                    </div>
                @endif
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const timeElements = document.querySelectorAll('.live-time');
        timeElements.forEach(el => {
            const utcString = el.getAttribute('data-time');
            if (utcString) {
                const date = new Date(utcString);
                if (!isNaN(date)) {
                    el.textContent = date.toLocaleTimeString('en-IN', { hour: 'numeric', minute: '2-digit', hour12: true });
                }
            }
        });

        // Force label click to trigger file input (safari/mobile fix)
        const attachmentLabel = document.querySelector('label[for="reply-attachment"]');
        const attachmentInput = document.getElementById('reply-attachment');
        if (attachmentLabel && attachmentInput) {
            attachmentLabel.addEventListener('click', function(e) {
                attachmentInput.click();
            });
        }
    });

    function updateFileName(input) {
        const container = document.getElementById('attachment-container');
        const text = document.getElementById('attachment-text');
        const icon = document.getElementById('attachment-icon');
        const removeBtn = document.getElementById('remove-attachment');
        const textarea = document.getElementById('reply-textarea');
        
        const previewArea = document.getElementById('reply-preview-area');
        const previewContent = document.getElementById('preview-content');
        const previewFilename = document.getElementById('preview-filename');
        const previewFilesize = document.getElementById('preview-filesize');
        
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const fileName = file.name;
            const fileSize = (file.size / 1024).toFixed(1) + ' KB';
            
            // Update button UI
            text.textContent = fileName;
            text.style.color = '#3b82f6';
            container.style.borderColor = '#3b82f6';
            container.style.background = '#eff6ff';
            container.style.borderStyle = 'solid';
            icon.style.color = '#3b82f6';
            removeBtn.style.display = 'block';

            // Update Preview Area
            previewFilename.textContent = fileName;
            previewFilesize.textContent = fileSize;
            previewArea.style.display = 'block';
            
            // Show Image Overlay or Icon
            if (file.type.match('image.*')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewContent.innerHTML = `<img src="${e.target.result}" style="width: 100%; height: 100%; object-fit: cover;">`;
                }
                reader.readAsDataURL(file);
            } else {
                previewContent.innerHTML = `<i class="fas fa-file-alt" style="font-size: 2rem; color: #64748b;"></i>`;
            }

            // Automatically fill textarea if empty
            if (textarea.value.trim() === '') {
                textarea.value = 'Attached: ' + fileName;
            }
        } else {
            resetAttachmentUI();
        }
    }

    function clearAttachment() {
        const input = document.getElementById('reply-attachment');
        const textarea = document.getElementById('reply-textarea');
        const previewArea = document.getElementById('reply-preview-area');
        
        // If textarea was auto-filled, clear it
        if (input.files[0] && textarea.value === 'Attached: ' + input.files[0].name) {
            textarea.value = '';
        }
        
        input.value = '';
        previewArea.style.display = 'none';
        resetAttachmentUI();
    }

    function resetAttachmentUI() {
        const container = document.getElementById('attachment-container');
        const text = document.getElementById('attachment-text');
        const icon = document.getElementById('attachment-icon');
        const removeBtn = document.getElementById('remove-attachment');
        const previewArea = document.getElementById('reply-preview-area');
        
        text.textContent = 'Attach File';
        text.style.color = '#64748b';
        container.style.borderColor = '#cbd5e1';
        container.style.borderStyle = 'dashed';
        container.style.background = '#f8fafc';
        icon.style.color = '#64748b';
        removeBtn.style.display = 'none';
        if (previewArea) previewArea.style.display = 'none';
    }
</script>
@endpush
