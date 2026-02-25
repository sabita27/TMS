@extends('layouts.backend.master')

@section('page_title', 'My Workdesk')
@section('header_height', '120px')

@section('content')
<div style="padding: 0 1rem;">
    <!-- Welcome Header -->
    <div style="margin-bottom: 2.5rem;">
        <h1 style="font-size: 1.875rem; font-weight: 800; color: #0f172a; margin: 0; letter-spacing: -0.025em;">Mission Hub</h1>
        <p style="color: #64748b; font-size: 0.95rem; margin-top: 0.25rem;">Hello, {{ Auth::user()->name }}. You have <span style="color: #6366f1; font-weight: 700;">{{ $stats['open_tickets'] }}</span> active tickets requiring your expertise today.</p>
    </div>

    <!-- Personal Stats Grid -->
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; margin-bottom: 3rem;">
        <!-- Total Assigned -->
        <div class="card" style="padding: 1.5rem; border-radius: 1.25rem; background: white; border: 1px solid #f1f5f9; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); position: relative;">
            <div style="color: #94a3b8; font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Total Portfolio</div>
            <div style="font-size: 2rem; font-weight: 800; color: #1e293b;">{{ $stats['total_assigned'] }}</div>
            <div style="font-size: 0.8rem; color: #64748b; margin-top: 0.25rem;">Total tickets assigned to you</div>
            <i class="fas fa-briefcase" style="position: absolute; right: 1.5rem; top: 1.5rem; color: #f1f5f9; font-size: 2rem;"></i>
        </div>

        <!-- Open / In-Progress -->
        <div class="card" style="padding: 1.5rem; border-radius: 1.25rem; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); color: white; border: none; box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.3);">
            <div style="font-size: 0.75rem; font-weight: 700; opacity: 0.9; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Active Missions</div>
            <div style="font-size: 2rem; font-weight: 800;">{{ $stats['open_tickets'] }}</div>
            <div style="font-size: 0.8rem; opacity: 0.8; margin-top: 0.25rem;">Requires immediate action</div>
            <i class="fas fa-bolt" style="position: absolute; right: 1.5rem; top: 1.5rem; font-size: 2rem; opacity: 0.2;"></i>
        </div>

        <!-- Resolved -->
        <div class="card" style="padding: 1.5rem; border-radius: 1.25rem; background: #ecfdf5; border: 1px solid #d1fae5; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
            <div style="color: #059669; font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Total Resolved</div>
            <div style="font-size: 2rem; font-weight: 800; color: #065f46;">{{ $stats['resolved_tickets'] }}</div>
            <div style="font-size: 0.8rem; color: #059669; margin-top: 0.25rem;">Successfully completed cases</div>
            <i class="fas fa-check-circle" style="position: absolute; right: 1.5rem; top: 1.5rem; color: #d1fae5; font-size: 2rem;"></i>
        </div>
    </div>

    <!-- Assignments List -->
    <div class="card" style="border-radius: 1.5rem; border: 1px solid #f1f5f9; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.04); background: white; overflow: hidden;">
        <div style="padding: 1.5rem 2rem; border-bottom: 1px solid #f1f5f9;">
            <h3 style="margin: 0; font-size: 1.1rem; font-weight: 800; color: #1e293b;">Active Assignments</h3>
            <p style="margin: 0; font-size: 0.85rem; color: #94a3b8; font-weight: 500;">Manage your current workload and update progress.</p>
        </div>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8fafc;">
                        <th style="padding: 1rem 2rem; text-align: left; font-size: 0.7rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">Client & ID</th>
                        <th style="padding: 1rem 2rem; text-align: left; font-size: 0.7rem; font-weight: 800; color: #64748b; text-transform: uppercase;">Subject</th>
                        <th style="padding: 1rem 2rem; text-align: left; font-size: 0.7rem; font-weight: 800; color: #64748b; text-transform: uppercase;">Priority</th>
                        <th style="padding: 1rem 2rem; text-align: left; font-size: 0.7rem; font-weight: 800; color: #64748b; text-transform: uppercase;">Action / Status</th>
                        <th style="padding: 1rem 2rem; text-align: left; font-size: 0.7rem; font-weight: 800; color: #64748b; text-transform: uppercase;">Received</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($assigned_tickets as $ticket)
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 1.25rem 2rem;">
                            <div style="font-weight: 800; color: #1e293b; font-size: 0.85rem;">#{{ $ticket->ticket_id }}</div>
                            <div style="font-size: 0.75rem; color: #6366f1; font-weight: 700;">{{ $ticket->user->name }}</div>
                        </td>
                        <td style="padding: 1.25rem 2rem;">
                            <div style="font-weight: 600; color: #334155; font-size: 0.9rem;">{{ $ticket->subject }}</div>
                        </td>
                        <td style="padding: 1.25rem 2rem;">
                            @php
                                $pColors = [
                                    'high' => ['bg' => '#fef2f2', 'text' => '#ef4444'],
                                    'medium' => ['bg' => '#fffbeb', 'text' => '#f59e0b'],
                                    'low' => ['bg' => '#f0fdf4', 'text' => '#10b981'],
                                ];
                                $pc = $pColors[$ticket->priority] ?? ['bg' => '#f8fafc', 'text' => '#64748b'];
                            @endphp
                            <span style="background: {{ $pc['bg'] }}; color: {{ $pc['text'] }}; padding: 0.25rem 0.75rem; border-radius: 0.5rem; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; border: 1px solid currentColor; opacity: 0.8;">
                                {{ $ticket->priority }}
                            </span>
                        </td>
                        <td style="padding: 1.25rem 2rem;">
                            <form action="{{ route('staff.tickets.status', $ticket->id) }}" method="POST">
                                @csrf
                                <select name="status" onchange="this.form.submit()" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 0.75rem; padding: 0.4rem 0.75rem; font-size: 0.8rem; font-weight: 700; color: #475569; outline: none; cursor: pointer;">
                                    <option value="open" {{ $ticket->status == 'open' ? 'selected' : '' }}>OPEN</option>
                                    <option value="in-progress" {{ $ticket->status == 'in-progress' ? 'selected' : '' }}>IN-PROGRESS</option>
                                    <option value="resolved" {{ $ticket->status == 'resolved' ? 'selected' : '' }}>RESOLVED</option>
                                    <option value="closed" {{ $ticket->status == 'closed' ? 'selected' : '' }}>CLOSED</option>
                                </select>
                            </form>
                        </td>
                        <td style="padding: 1.25rem 2rem; color: #94a3b8; font-size: 0.8rem; font-weight: 500;">
                            {{ $ticket->created_at->diffForHumans() }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="padding: 4rem 2rem; text-align: center;">
                            <div style="color: #cbd5e1; font-size: 3rem; margin-bottom: 1rem;"><i class="fas fa-mug-hot"></i></div>
                            <div style="font-weight: 700; color: #94a3b8; font-size: 1.1rem;">Clear Desk!</div>
                            <p style="color: #cbd5e1; font-size: 0.9rem;">You have no active assignments at the moment.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="padding: 1.5rem 2rem; border-top: 1px solid #f1f5f9; background: #fdfdfd;">
            {{ $assigned_tickets->links() }}
        </div>
    </div>
</div>
@endsection
