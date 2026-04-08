@extends('layouts.backend.master')

@section('styles')
<style>
    /* Baseline for grids to avoid alignment shift */
    .stats-grid, .analytical-grid, .recent-tickets-card {
        width: 100% !important;
        max-width: 100% !important;
        margin-left: 0 !important;
        margin-right: 0 !important;
        box-sizing: border-box !important;
    }

    /* Desktop layout for management roles */
    @media (min-width: 1025px) {
        .stats-grid {
            grid-template-columns: repeat(4, 1fr) !important;
        }
        .analytical-grid {
            grid-template-columns: 1.25fr 1fr !important;
            gap: 2rem !important;
        }
    }

    @media (max-width: 1280px) {
        .stats-grid {
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)) !important;
            margin-right: 0 !important;
            margin-left: 0 !important;
        }
    }

    @media (max-width: 1024px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr) !important;
            gap: 1.25rem !important;
            width: 100% !important;
        }
        .analytical-grid {
            grid-template-columns: 1fr !important;
            gap: 1.5rem !important;
            width: 100% !important;
        }
    }

    @media (max-width: 768px) {
        .dashboard-header {
            margin-bottom: 2rem !important;
        }
        .stats-grid {
            gap: 1rem !important;
        }
        .stats-grid > div {
            padding: 1rem !important;
        }
        .analytical-grid .card {
            padding: 1.25rem !important;
        }
        .chart-container {
            min-height: 260px !important;
        }
    }

    @media (max-width: 640px) {
        .dashboard-header {
            text-align: left;
            margin-bottom: 1.5rem !important;
        }
        .dashboard-header h1 {
            font-size: 1.25rem !important;
        }
        .stats-grid {
            grid-template-columns: 1fr 1fr !important;
            gap: 0.75rem !important;
        }
        .stats-grid > div {
            padding: 0.85rem !important;
            gap: 0.75rem !important;
            border-radius: 1rem !important;
        }
        .stats-grid div[style*="width: 56px"] {
            width: 40px !important;
            height: 40px !important;
            font-size: 0.9rem !important;
            border-radius: 0.75rem !important;
        }
        .stats-grid h2 {
            font-size: 1.15rem !important;
        }
        .stats-grid p {
            font-size: 0.6rem !important;
        }
        
        .kpi-grid {
            grid-template-columns: 1fr 1fr !important;
            gap: 0.75rem !important;
        }
        .kpi-grid > div {
            padding: 0.75rem !important;
        }
        .kpi-grid canvas {
            max-height: 50px !important;
        }
        .kpi-grid h4 {
            font-size: 1rem !important;
        }
    }

    @media (max-width: 480px) {
        .analytical-grid .card {
            padding: 1rem !important;
        }
        
        .chart-container {
            min-height: 220px !important;
        }
        
        .recent-tickets-card table {
            white-space: nowrap !important;
        }
    }
</style>
@endsection

@section('content')
@php
    $statusMap = \App\Models\TicketStatus::pluck('color', 'name')->mapWithKeys(function($c, $n) {
        return [strtolower($n) => $c];
    })->toArray();
    $priorityMap = \App\Models\TicketPriority::pluck('color', 'name')->mapWithKeys(function($c, $n) {
        return [strtolower($n) => $c];
    })->toArray();
@endphp
<div class="dashboard-header">
    <h1 style="font-size: 1.5rem; font-weight: 700; color: #111827;">
        @can('manage users') Admin Dashboard
        @elseif(Auth::user()->can('manage tickets')) Manager Dashboard
        @elseif(Auth::user()->can('edit tickets')) Staff Dashboard
        @else User Dashboard @endif
    </h1>
    <p style="color: #6b7280; font-size: 0.875rem;">
        @can('manage users') Welcome back, Admin! Here's what's happening today.
        @elseif(Auth::user()->can('manage tickets')) Global assessment of support operations and team equilibrium.
        @else Welcome back, {{ Auth::user()->name }}! Here's what's happening today. @endif
    </p>
</div>

<div class="stats-grid" style="display: grid; gap: 1.5rem; margin-bottom: 2.5rem; width: 100%;">
    <!-- Dynamic Stat Cards based on Permissions -->
    @if(Auth::user()->can('manage tickets'))
    <div style="background: linear-gradient(135deg, #e0e7ff 0%, #ffffff 100%); padding: 1.5rem; border-radius: 1.25rem; border: 1px solid #c7d2fe; display: flex; align-items: center; gap: 1.25rem; transition: transform 0.3s; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
        <div style="background: #6366f1; width: 56px; height: 56px; border-radius: 1rem; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.5rem; box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.3);">
            <i class="fas fa-users"></i>
        </div>
        <div>
            <p style="color: #4338ca; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin: 0;">Total Users</p>
            <h2 style="font-size: 1.75rem; font-weight: 800; color: #1e1b4b; margin: 0.25rem 0 0 0;">{{ $stats['users'] ?? 0 }}</h2>
        </div>
    </div>

    <div style="background: linear-gradient(135deg, #dcfce7 0%, #ffffff 100%); padding: 1.5rem; border-radius: 1.25rem; border: 1px solid #bbf7d0; display: flex; align-items: center; gap: 1.25rem; transition: transform 0.3s; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
        <div style="background: #22c55e; width: 56px; height: 56px; border-radius: 1rem; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.5rem; box-shadow: 0 10px 15px -3px rgba(34, 197, 94, 0.3);">
            <i class="fas fa-box-open"></i>
        </div>
        <div>
            <p style="color: #15803d; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin: 0;">Total Products</p>
            <h2 style="font-size: 1.75rem; font-weight: 800; color: #064e3b; margin: 0.25rem 0 0 0;">{{ $stats['products'] ?? 0 }}</h2>
        </div>
    </div>
    @endif

    <div style="background: linear-gradient(135deg, #fffbeb 0%, #ffffff 100%); padding: 1.5rem; border-radius: 1.25rem; border: 1px solid #fef3c7; display: flex; align-items: center; gap: 1.25rem; transition: transform 0.3s; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
        <div style="background: #f59e0b; width: 56px; height: 56px; border-radius: 1rem; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.5rem; box-shadow: 0 10px 15px -3px rgba(245, 158, 11, 0.3);">
            <i class="fas fa-ticket-alt"></i>
        </div>
        <div>
            <p style="color: #b45309; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin: 0;">{{ Auth::user()->can('edit tickets') && !Auth::user()->can('manage tickets') ? 'Assigned' : 'Tickets' }}</p>
            <h2 style="font-size: 1.75rem; font-weight: 800; color: #78350f; margin: 0.25rem 0 0 0;">{{ $stats['tickets'] ?? 0 }}</h2>
        </div>
    </div>

    <div style="background: linear-gradient(135deg, #fee2e2 0%, #ffffff 100%); padding: 1.5rem; border-radius: 1.25rem; border: 1px solid #fecaca; display: flex; align-items: center; gap: 1.25rem; transition: transform 0.3s; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
        <div style="background: #ef4444; width: 56px; height: 56px; border-radius: 1rem; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.5rem; box-shadow: 0 10px 15px -3px rgba(239, 68, 68, 0.3);">
            <i class="fas fa-clock"></i>
        </div>
        <div>
            <p style="color: #b91c1c; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin: 0;">Open Tickets</p>
            <h2 style="font-size: 1.75rem; font-weight: 800; color: #7f1d1d; margin: 0.25rem 0 0 0;">{{ $stats['open_tickets'] ?? 0 }}</h2>
        </div>
    </div>
</div>

@if(Auth::user()->can('manage tickets'))
<!-- Analytical Section for Management Roles -->
<div class="analytical-grid" style="display: grid; gap: 1.5rem; margin-bottom: 2rem; align-items: stretch; width: 100%;">
    <div class="card" style="min-width: 0; padding: 2rem; border: none; border-radius: 1.5rem; background: white; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05); border: 1px solid #f1f5f9; display: flex; flex-direction: column;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 2rem;">
            <div>
                <h3 style="font-size: 1.125rem; font-weight: 800; color: #0f172a; margin: 0;">Support Performance</h3>
                <p style="font-size: 0.8125rem; color: #64748b; margin-top: 0.25rem;">Live ticket inflow & resolution tracking</p>
            </div>
        </div>
        <div class="chart-container" style="flex: 1; min-height: 320px; position: relative; width: 100%;">
            <canvas id="mainActivityChart"></canvas>
        </div>
    </div>

    <div class="card" style="min-width: 0; padding: 2rem; border: none; border-radius: 1.5rem; background: white; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05); border: 1px solid #f1f5f9; display: flex; flex-direction: column;">
        <h3 style="font-size: 1.125rem; font-weight: 800; color: #0f172a; margin-bottom: 2rem;">Key Performance</h3>
        <div class="kpi-grid" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem; flex: 1; align-content: start;">
            <div style="background: #eef7f2; padding: 1rem; border-radius: 1.25rem; text-align: center; display: flex; flex-direction: column; align-items: center; justify-content: space-between; height: 100%;">
                <span style="font-size: 0.7rem; font-weight: 700; color: #166534; display: block; margin-bottom: 0.5rem;">Open Requests</span>
                <canvas id="kpiOpenChart" style="max-height: 60px; max-width: 60px;"></canvas>
                <h4 style="margin: 0.5rem 0 0 0; font-size: 1.25rem;">{{ $stats['open_tickets'] ?? 0 }}</h4>
            </div>
            <div style="background: #f0f4ff; padding: 1rem; border-radius: 1.25rem; text-align: center; display: flex; flex-direction: column; align-items: center; justify-content: space-between; height: 100%;">
                <span style="font-size: 0.7rem; font-weight: 700; color: #1e40af; display: block; margin-bottom: 0.5rem;">Closed Stats</span>
                <canvas id="kpiResolvedChart" style="max-height: 60px; max-width: 60px;"></canvas>
                <h4 style="margin: 0.5rem 0 0 0; font-size: 1.25rem;">{{ $stats['closed_tickets'] ?? 0 }}</h4>
            </div>
            @if(Auth::user()->can('manage tickets'))
            <div style="background: #fff9e6; padding: 1rem; border-radius: 1.25rem; text-align: center; display: flex; flex-direction: column; align-items: center; justify-content: space-between; height: 100%;">
                <span style="font-size: 0.7rem; font-weight: 700; color: #854d0e; display: block; margin-bottom: 0.5rem;">High Priority</span>
                <canvas id="kpiPriorityChart" style="max-height: 60px; max-width: 60px;"></canvas>
                <h4 style="margin: 0.5rem 0 0 0; font-size: 1.25rem;">{{ $tickets_by_priority['high'] ?? 0 }}</h4>
            </div>
            @endif
            @can('manage users')
            <div style="background: #fff0f0; padding: 1rem; border-radius: 1.25rem; text-align: center; display: flex; flex-direction: column; align-items: center; justify-content: space-between; height: 100%;">
                <span style="font-size: 0.7rem; font-weight: 700; color: #9f1239; display: block; margin-bottom: 0.5rem;">System Agents</span>
                <div style="flex: 1; display: flex; align-items: center; justify-content: center; position: relative; width: 100%;">
                    <h4 style="margin: 0; font-size: 1.5rem;">{{ $stats['agents'] ?? 0 }}</h4>
                </div>
            </div>
            @endcan
        </div>
    </div>
</div>
@endif

<div class="card recent-tickets-card" style="border-radius: 1.25rem; border: 1px solid #f1f5f9; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); background: white; width: 100%;">
    <div style="padding: 1.5rem; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center;">
        <h3 style="margin: 0; font-size: 1.1rem; font-weight: 800; color: #1e293b;">Recent Tickets</h3>
        @php 
            $route = Auth::user()->can('manage users') ? 'admin.dashboard' : (Auth::user()->can('manage tickets') ? 'manager.tickets' : (Auth::user()->can('edit tickets') ? 'staff.dashboard' : 'user.dashboard'));
        @endphp
    </div>
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f8fafc;">
                    <th style="padding: 1rem; text-align: left; font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase;">Ticket ID</th>
                    <th style="padding: 1rem; text-align: left; font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase;">User</th>
                    <th style="padding: 1rem; text-align: left; font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase;">Subject</th>
                    <th style="padding: 1rem; text-align: left; font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase;">Status</th>
                    <th style="padding: 1rem; text-align: left; font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase;">Priority</th>
                    <th style="padding: 1rem; text-align: left; font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase;">Date</th>
                    <th style="padding: 1rem; text-align: center; font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase;">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recent_tickets as $ticket)
                <tr style="border-bottom: 1px solid #f1f5f9;">
                    <td style="padding: 1rem; font-weight: 700; color: #1e293b;">
                        <a href="{{ route('ticket.show', $ticket->id) }}" style="color: #3b82f6; text-decoration: none;">#{{ $ticket->ticket_id }}</a>
                    </td>
                    <td style="padding: 1rem;">{{ $ticket->user->name }}</td>
                    <td style="padding: 1rem;">{{ $ticket->subject }}</td>
                    <td style="padding: 1rem;">
                        @php
                            $sName = strtolower(str_replace('-', ' ', $ticket->status));
                            $sColor = $statusMap[$sName] ?? ($statusMap[strtolower($ticket->status)] ?? null);
                            if($sColor) {
                                $sBg = $sColor . '20'; // 12% opacity
                                $sText = $sColor;
                            } else {
                                $sBg = $ticket->status == 'open' ? '#fee2e2' : ($ticket->status == 'closed' ? '#dcfce7' : '#fef3c7');
                                $sText = $ticket->status == 'open' ? '#991b1b' : ($ticket->status == 'closed' ? '#166534' : '#92400e');
                            }
                        @endphp
                        <span style="padding: 0.35rem 0.85rem; border-radius: 2rem; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; 
                            background: {{ $sBg }};
                            color: {{ $sText }};">
                            {{ ucfirst($ticket->status) }}
                        </span>
                    </td>
                    <td style="padding: 1rem;">
                        @php
                            $pName = strtolower(str_replace('-', ' ', $ticket->priority));
                            $pColor = $priorityMap[$pName] ?? ($priorityMap[strtolower($ticket->priority)] ?? null);
                            if($pColor) {
                                $pBg = $pColor . '20';
                                $pText = $pColor;
                            } else {
                                $pBg = $ticket->priority == 'high' ? '#fee2e2' : ($ticket->priority == 'medium' ? '#fef3c7' : '#e0e7ff');
                                $pText = $ticket->priority == 'high' ? '#991b1b' : ($ticket->priority == 'medium' ? '#92400e' : '#3730a3');
                            }
                        @endphp
                        <span style="padding: 0.35rem 0.85rem; border-radius: 2rem; font-size: 0.7rem; font-weight: 700; text-transform: uppercase;
                            background: {{ $pBg }};
                            color: {{ $pText }};">
                            {{ ucfirst($ticket->priority) }}
                        </span>
                    </td>
                    <td style="padding: 1rem; color: #64748b; font-size: 0.85rem;">{{ $ticket->created_at->format('M d, Y') }}</td>
                    <td style="padding: 1rem; text-align: center;">
                        <div style="display: flex; flex-direction: column; gap: 0.5rem; align-items: center;">
                            <a href="{{ route('ticket.show', $ticket->id) }}" style="color: #64748b; font-weight: 700; text-decoration: none; font-size: 0.7rem; display: flex; align-items: center; justify-content: center; gap: 0.25rem;">
                                <i class="fas fa-eye"></i> Details
                            </a>
                            @if(Auth::user()->can('manage tickets'))
                                <a href="{{ route('manager.tickets') }}" style="color: #3b82f6; font-weight: 700; text-decoration: none; font-size: 0.75rem; display: flex; align-items: center; justify-content: center; gap: 0.35rem;">
                                    <i class="fas fa-tasks"></i> All Tickets
                                </a>
                            @endif
                        </div>
                    </td>
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

@if(Auth::user()->can('manage tickets'))
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const activityCtx = document.getElementById('mainActivityChart').getContext('2d');
    const activityGradient = activityCtx.createLinearGradient(0, 0, 0, 250);
    activityGradient.addColorStop(0, 'rgba(99, 102, 241, 0.2)');
    activityGradient.addColorStop(1, 'rgba(99, 102, 241, 0)');

    const isMobile = window.innerWidth < 768;

    new Chart(activityCtx, {
        type: 'line',
        data: {
            labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
            datasets: [{
                data: [45, 52, 48, 70, 65, 85, 78, 95, 88, 105, 98, 115],
                borderColor: '#6366f1',
                borderWidth: 4,
                tension: 0.45,
                fill: true,
                backgroundColor: activityGradient,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            layout: {
                padding: {
                    left: isMobile ? 5 : 0,
                    right: isMobile ? 5 : 0,
                    top: 10,
                    bottom: 0
                }
            },
            plugins: { legend: { display: false } },
            scales: {
                x: { 
                    grid: { display: false }, 
                    ticks: { 
                        color: '#94a3b8', 
                        font: { size: 10 },
                        autoSkip: isMobile,
                        maxRotation: 0,
                        minRotation: 0
                    } 
                },
                y: { grid: { color: '#f1f5f9' }, ticks: { color: '#94a3b8', font: { size: 10 } } }
            }
        }
    });

    const createRadial = (id, color, value) => {
        const ctx = document.getElementById(id).getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: [value, 100 - value],
                    backgroundColor: [color, '#e2e8f0'],
                    borderWidth: 0
                }]
            },
            options: {
                cutout: '70%',
                maintainAspectRatio: false,
                plugins: { legend: { display: false }, tooltip: { enabled: false } }
            }
        });
    };

    @php 
        $total = $stats['tickets'] ?: 1;
        $openP = (($stats['open_tickets'] ?? 0) / $total) * 100;
        $closedP = (($stats['closed_tickets'] ?? 0) / $total) * 100;
        $highP = (($tickets_by_priority['high'] ?? 0) / $total) * 100;
    @endphp

    createRadial('kpiOpenChart', '#10b981', {{ $openP }});
    createRadial('kpiResolvedChart', '#6366f1', {{ $closedP }});
    if(document.getElementById('kpiPriorityChart')) createRadial('kpiPriorityChart', '#fbbf24', {{ $highP }});
</script>
@endif
@endsection
