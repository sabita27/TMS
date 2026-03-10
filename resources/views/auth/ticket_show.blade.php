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
    
    <div style="display: flex; gap: 1rem;">
        @if(Auth::user()->hasRole('user') && strtolower($ticket->status) != 'closed')
            <form action="{{ route('user.tickets.close', $ticket->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to resolve this ticket?')">
                @csrf
                <button type="submit" style="background: #ef4444; color: white; border: none; padding: 0.6rem 1.25rem; border-radius: 1rem; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-lock"></i> Close Ticket
                </button>
            </form>
        @endif
    </div>
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
            <div style="margin-bottom: 3.5rem;">
                <span class="meta-label">Ticket Subject</span>
                <h2 style="margin: 0; font-size: 2rem; font-weight: 900; color: #0f172a; line-height: 1.2;">{{ $ticket->subject }}</h2>
            </div>

            <div style="margin-bottom: 3rem;">
                <span class="meta-label">Detailed Description</span>
                <div class="description-box">
                    {!! $ticket->description !!}
                </div>
            </div>

            @if($ticket->attachment)
            <div>
                <span class="meta-label">Attached Document</span>
                <div style="background: #f1f5f9; padding: 1.5rem; border-radius: 1.25rem; display: flex; align-items: center; justify-content: space-between;">
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <div style="width: 40px; height: 40px; background: white; border-radius: 0.75rem; display: flex; align-items: center; justify-content: center; color: #64748b; font-size: 1.1rem; border: 1px solid #e2e8f0;">
                            <i class="fas fa-file-pdf"></i>
                        </div>
                        <div>
                            <p style="margin: 0; font-weight: 700; color: #334155; font-size: 0.9rem;">Supporting Media/File</p>
                            <p style="margin: 0; font-size: 0.75rem; color: #94a3b8;">{{ strtoupper(pathinfo($ticket->attachment, PATHINFO_EXTENSION)) }} Document</p>
                        </div>
                    </div>
                    <a href="{{ asset('storage/' . $ticket->attachment) }}" target="_blank" style="background: white; color: #3b82f6; border: 1.5px solid #dbeafe; padding: 0.6rem 1.25rem; border-radius: 0.75rem; font-weight: 700; font-size: 0.85rem; text-decoration: none; transition: 0.3s;" onmouseover="this.style.background='#3b82f6'; this.style.color='white';" onmouseout="this.style.background='white'; this.style.color='#3b82f6';">
                        <i class="fas fa-download"></i> Download
                    </a>
                </div>
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

                <div>
                    <span class="meta-label">Product Context</span>
                    <p class="meta-value" style="margin-bottom: 0.25rem;">{{ $ticket->product->name ?? 'None' }}</p>
                    <p style="margin: 0; font-size: 0.8rem; color: #64748b;">System Module</p>
                </div>

                <div>
                    <span class="meta-label">Project & Service</span>
                    <div style="background: white; border-radius: 1rem; padding: 1.25rem; border: 1px solid #eef2f6;">
                        <p style="margin: 0 0 0.5rem 0; font-weight: 800; color: #0f172a; font-size: 0.95rem;">
                            <i class="fas fa-project-diagram" style="color: #6366f1; margin-right: 0.4rem; font-size: 0.8rem;"></i>
                            {{ $ticket->project->name ?? 'Global' }}
                        </p>
                        <p style="margin: 0; font-weight: 600; color: #64748b; font-size: 0.85rem;">
                            <i class="fas fa-concierge-bell" style="color: #f59e0b; margin-right: 0.4rem; font-size: 0.8rem;"></i>
                            {{ $ticket->service->name ?? 'General Support' }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Contextual Actions for Admin/Staff --}}
            @if(Auth::user()->hasAnyRole(['admin', 'manager', 'staff']))
            <div style="margin-top: 3.5rem;">
                <h4 style="margin: 0 0 1.5rem 0; font-size: 0.9rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em;">Console Management</h4>
                
                {{-- Staff Solve Button --}}
                @if(Auth::user()->hasRole('staff') && $ticket->assigned_to == Auth::id() && !in_array(strtolower($ticket->status), ['resolved', 'closed']))
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
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
