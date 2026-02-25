@extends('layouts.backend.master')

@section('page_title', 'Manager Overview')
@section('header_height', '120px')

@section('content')
<div style="padding: 0 1rem;">
    <!-- Welcome Section -->
    <div style="margin-bottom: 2.5rem; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1 style="font-size: 1.875rem; font-weight: 800; color: #0f172a; margin: 0; letter-spacing: -0.025em;">Operations Control</h1>
            <p style="color: #64748b; font-size: 0.95rem; margin-top: 0.25rem;">Global assessment of support operations and team equilibrium.</p>
        </div>
        <div style="display: flex; gap: 0.75rem;">
            <div style="background: white; padding: 0.5rem 1rem; border-radius: 0.75rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); display: flex; align-items: center; gap: 0.5rem;">
                <div style="width: 8px; height: 8px; background: #10b981; border-radius: 50%;"></div>
                <span style="font-size: 0.85rem; font-weight: 600; color: #475569;">System Live</span>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem; margin-bottom: 3rem;">
        <!-- Total Tickets -->
        <div class="card" style="padding: 1.5rem; border-radius: 1.25rem; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); color: white; border: none; position: relative; overflow: hidden;">
            <i class="fas fa-ticket-alt" style="position: absolute; right: -10px; bottom: -10px; font-size: 5rem; opacity: 0.1;"></i>
            <div style="font-size: 0.85rem; font-weight: 600; opacity: 0.9; text-transform: uppercase; letter-spacing: 0.05em;">Total Pool</div>
            <div style="font-size: 2.25rem; font-weight: 800; margin: 0.5rem 0;">{{ $stats['total_tickets'] }}</div>
            <div style="font-size: 0.75rem; opacity: 0.8; font-weight: 500;">Lifetime volume</div>
        </div>

        <!-- Unassigned -->
        <div class="card" style="padding: 1.5rem; border-radius: 1.25rem; background: white; border: 1px solid #f1f5f9; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
            <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
                <div style="width: 40px; height: 40px; background: #fef2f2; color: #ef4444; border-radius: 0.75rem; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-user-slash"></i>
                </div>
                <span style="font-size: 0.85rem; font-weight: 700; color: #64748b;">Pending Assignment</span>
            </div>
            <div style="font-size: 1.75rem; font-weight: 800; color: #1e293b;">{{ $stats['unassigned_tickets'] }}</div>
            <div style="display: flex; align-items: center; gap: 0.25rem; margin-top: 0.5rem; color: #ef4444; font-size: 0.75rem; font-weight: 600;">
                <i class="fas fa-exclamation-circle"></i> Requires attention
            </div>
        </div>

        <!-- In Progress -->
        <div class="card" style="padding: 1.5rem; border-radius: 1.25rem; background: white; border: 1px solid #f1f5f9; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
            <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
                <div style="width: 40px; height: 40px; background: #fffbeb; color: #f59e0b; border-radius: 0.75rem; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-sync-alt fa-spin"></i>
                </div>
                <span style="font-size: 0.85rem; font-weight: 700; color: #64748b;">Active Handling</span>
            </div>
            <div style="font-size: 1.75rem; font-weight: 800; color: #1e293b;">{{ $stats['in_progress'] }}</div>
            <div style="display: flex; align-items: center; gap: 0.25rem; margin-top: 0.5rem; color: #f59e0b; font-size: 0.75rem; font-weight: 600;">
                <i class="fas fa-clock"></i> Currently being processed
            </div>
        </div>

        <!-- Resolved -->
        <div class="card" style="padding: 1.5rem; border-radius: 1.25rem; background: white; border: 1px solid #f1f5f9; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
            <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
                <div style="width: 40px; height: 40px; background: #f0fdf4; color: #10b981; border-radius: 0.75rem; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-check-double"></i>
                </div>
                <span style="font-size: 0.85rem; font-weight: 700; color: #64748b;">Resolved Cases</span>
            </div>
            <div style="font-size: 1.75rem; font-weight: 800; color: #1e293b;">{{ $stats['resolved'] }}</div>
            <div style="display: flex; align-items: center; gap: 0.25rem; margin-top: 0.5rem; color: #10b981; font-size: 0.75rem; font-weight: 600;">
                <i class="fas fa-arrow-up"></i> Performance positive
            </div>
        </div>
    </div>

    <!-- Activity Section -->
    <div class="card" style="border-radius: 1.5rem; border: 1px solid #f1f5f9; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.04); background: white; overflow: hidden;">
        <div style="padding: 1.5rem 2rem; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h3 style="margin: 0; font-size: 1.1rem; font-weight: 800; color: #1e293b;">Recent Mission Updates</h3>
                <p style="margin: 0; font-size: 0.85rem; color: #94a3b8; font-weight: 500;">Latest tickets submitted across the platform.</p>
            </div>
            <a href="{{ route('manager.tickets') }}" style="background: #f1f5f9; color: #475569; padding: 0.5rem 1.25rem; border-radius: 0.75rem; text-decoration: none; font-size: 0.85rem; font-weight: 700; transition: all 0.2s;">
                View All Operations
            </a>
        </div>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8fafc;">
                        <th style="padding: 1rem 2rem; text-align: left; font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">ID & Customer</th>
                        <th style="padding: 1rem 2rem; text-align: left; font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase;">Subject</th>
                        <th style="padding: 1rem 2rem; text-align: left; font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase;">Status</th>
                        <th style="padding: 1rem 2rem; text-align: left; font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase;">Specialist</th>
                        <th style="padding: 1rem 2rem; text-align: left; font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase;">Timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recent_tickets as $ticket)
                    <tr style="border-bottom: 1px solid #f1f5f9; transition: all 0.2s;" onmouseover="this.style.background='#fcfdfe'" onmouseout="this.style.background='white'">
                        <td style="padding: 1.25rem 2rem;">
                            <div style="font-weight: 800; color: #1e293b; font-size: 0.9rem;">#{{ $ticket->ticket_id }}</div>
                            <div style="font-size: 0.75rem; color: #94a3b8; margin-top: 0.25rem;">{{ $ticket->user->name }}</div>
                        </td>
                        <td style="padding: 1.25rem 2rem;">
                            <div style="font-weight: 600; color: #334155; font-size: 0.9rem; max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $ticket->subject }}</div>
                        </td>
                        <td style="padding: 1.25rem 2rem;">
                            @php
                                $colors = [
                                    'open' => ['bg' => '#fef2f2', 'text' => '#ef4444', 'border' => '#fee2e2'],
                                    'in-progress' => ['bg' => '#fffbeb', 'text' => '#f59e0b', 'border' => '#fef3c7'],
                                    'resolved' => ['bg' => '#f0fdf4', 'text' => '#10b981', 'border' => '#dcfce7'],
                                    'closed' => ['bg' => '#f8fafc', 'text' => '#64748b', 'border' => '#f1f5f9'],
                                ];
                                $c = $colors[$ticket->status] ?? $colors['open'];
                            @endphp
                            <span style="background: {{ $c['bg'] }}; color: {{ $c['text'] }}; border: 1px solid {{ $c['border'] }}; padding: 0.35rem 0.85rem; border-radius: 2rem; font-size: 0.75rem; font-weight: 700; text-transform: uppercase;">
                                {{ $ticket->status }}
                            </span>
                        </td>
                        <td style="padding: 1.25rem 2rem;">
                            @if($ticket->assignedStaff)
                                <div style="display: flex; align-items: center; gap: 0.5rem;">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($ticket->assignedStaff->name) }}&background=6366f1&color=fff&size=32" style="width: 24px; height: 24px; border-radius: 50%;">
                                    <span style="font-size: 0.85rem; font-weight: 600; color: #475569;">{{ $ticket->assignedStaff->name }}</span>
                                </div>
                            @else
                                <span style="font-size: 0.85rem; color: #cbd5e1; font-style: italic;">Unassigned</span>
                            @endif
                        </td>
                        <td style="padding: 1.25rem 2rem; color: #64748b; font-size: 0.85rem; font-weight: 500;">
                            {{ $ticket->created_at->diffForHumans() }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
