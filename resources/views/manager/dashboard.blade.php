@extends('layouts.backend.master')

@section('content')
<div class="dashboard-header" style="margin-bottom: 2rem;">
    <h1 style="font-size: 1.5rem; font-weight: 700; color: #111827;">Manager Dashboard</h1>
    <p style="color: #6b7280; font-size: 0.875rem;">Global assessment of support operations and team equilibrium.</p>
</div>

<div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem; margin-bottom: 2.5rem;">
    <!-- Enhanced Stat Cards -->
    <div style="background: linear-gradient(135deg, #e0e7ff 0%, #ffffff 100%); padding: 1.5rem; border-radius: 1.25rem; border: 1px solid #c7d2fe; display: flex; align-items: center; gap: 1.25rem; transition: transform 0.3s; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
        <div style="background: #6366f1; width: 56px; height: 56px; border-radius: 1rem; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.5rem; box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.3);">
            <i class="fas fa-users"></i>
        </div>
        <div>
            <p style="color: #4338ca; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin: 0;">Total Users</p>
            <h2 style="font-size: 1.75rem; font-weight: 800; color: #1e1b4b; margin: 0.25rem 0 0 0;">{{ $stats['users'] }}</h2>
        </div>
    </div>

    <div style="background: linear-gradient(135deg, #dcfce7 0%, #ffffff 100%); padding: 1.5rem; border-radius: 1.25rem; border: 1px solid #bbf7d0; display: flex; align-items: center; gap: 1.25rem; transition: transform 0.3s; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
        <div style="background: #22c55e; width: 56px; height: 56px; border-radius: 1rem; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.5rem; box-shadow: 0 10px 15px -3px rgba(34, 197, 94, 0.3);">
            <i class="fas fa-box-open"></i>
        </div>
        <div>
            <p style="color: #15803d; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin: 0;">Total Products</p>
            <h2 style="font-size: 1.75rem; font-weight: 800; color: #064e3b; margin: 0.25rem 0 0 0;">{{ $stats['products'] }}</h2>
        </div>
    </div>

    <div style="background: linear-gradient(135deg, #fffbeb 0%, #ffffff 100%); padding: 1.5rem; border-radius: 1.25rem; border: 1px solid #fef3c7; display: flex; align-items: center; gap: 1.25rem; transition: transform 0.3s; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
        <div style="background: #f59e0b; width: 56px; height: 56px; border-radius: 1rem; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.5rem; box-shadow: 0 10px 15px -3px rgba(245, 158, 11, 0.3);">
            <i class="fas fa-ticket-alt"></i>
        </div>
        <div>
            <p style="color: #b45309; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin: 0;">Total Tickets</p>
            <h2 style="font-size: 1.75rem; font-weight: 800; color: #78350f; margin: 0.25rem 0 0 0;">{{ $stats['tickets'] }}</h2>
        </div>
    </div>

    <div style="background: linear-gradient(135deg, #fee2e2 0%, #ffffff 100%); padding: 1.5rem; border-radius: 1.25rem; border: 1px solid #fecaca; display: flex; align-items: center; gap: 1.25rem; transition: transform 0.3s; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
        <div style="background: #ef4444; width: 56px; height: 56px; border-radius: 1rem; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.5rem; box-shadow: 0 10px 15px -3px rgba(239, 68, 68, 0.3);">
            <i class="fas fa-clock"></i>
        </div>
        <div>
            <p style="color: #b91c1c; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin: 0;">Open Tickets</p>
            <h2 style="font-size: 1.75rem; font-weight: 800; color: #7f1d1d; margin: 0.25rem 0 0 0;">{{ $stats['open_tickets'] }}</h2>
        </div>
    </div>
</div>

<!-- Analytical Section -->
<div style="display: grid; grid-template-columns: 1.6fr 1fr; gap: 1.5rem; margin-bottom: 2rem; align-items: stretch;">
    <!-- Left Column: Support Performance -->
    <div class="card" style="padding: 1.75rem; border: none; border-radius: 1.5rem; background: white; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05); border: 1px solid #f1f5f9; display: flex; flex-direction: column;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 2rem;">
            <div>
                <h3 style="font-size: 1.125rem; font-weight: 800; color: #0f172a; margin: 0;">Support Performance</h3>
                <p style="font-size: 0.8125rem; color: #64748b; margin-top: 0.25rem;">Live ticket inflow & resolution tracking</p>
            </div>
            <div style="display: flex; align-items: center; gap: 0.5rem; background: #f8fafc; padding: 0.4rem 0.8rem; border-radius: 0.75rem; border: 1px solid #f1f5f9; cursor: pointer;">
                <span style="font-size: 0.75rem; font-weight: 700; color: #475569;">Annual View</span>
                <i class="fas fa-chevron-down" style="font-size: 0.65rem; color: #94a3b8;"></i>
            </div>
        </div>
        <div style="flex: 1; min-height: 320px; position: relative;">
            <canvas id="mainActivityChart"></canvas>
        </div>
    </div>

    <!-- Right Column: KPI Grid -->
    <div class="card" style="padding: 1.75rem; border: none; border-radius: 1.5rem; background: white; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05); border: 1px solid #f1f5f9; display: flex; flex-direction: column;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 2rem;">
            <div>
                <h3 style="font-size: 1.125rem; font-weight: 800; color: #0f172a; margin: 0;">Key Performance</h3>
                <p style="font-size: 0.8125rem; color: #64748b; margin-top: 0.25rem;">Core operational metrics</p>
            </div>
            <span style="background: #eff6ff; color: #3b82f6; padding: 0.35rem 0.75rem; border-radius: 0.75rem; font-size: 0.65rem; font-weight: 800; letter-spacing: 0.05em; border: 1px solid #dbeafe;">LIVE</span>
        </div>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; flex: 1;">
            <!-- Open Tickets KPI -->
            <div style="background: #eef7f2; padding: 1.25rem; border-radius: 1.5rem; display: flex; flex-direction: column; justify-content: space-between; position: relative; transition: all 0.3s ease; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); cursor: pointer;" onmouseover="this.style.transform='translateY(-5px)';" onmouseout="this.style.transform='translateY(0)';">
                <h4 style="font-size: 0.85rem; font-weight: 700; color: #166534; margin: 0;">Open Requests</h4>
                <div style="width: 80px; height: 80px; align-self: center; margin: 1rem 0; position: relative; display: flex; align-items: center; justify-content: center;">
                    <canvas id="kpiOpenChart"></canvas>
                    <div style="position: absolute; text-align: center;">
                        @php $openP = ($stats['tickets'] > 0) ? round(($stats['open_tickets'] / $stats['tickets']) * 100) : 0; @endphp
                        <span style="display: block; font-size: 0.9rem; font-weight: 800; color: #064e3b;">{{ $openP }}%</span>
                    </div>
                </div>
                <p style="font-size: 1.25rem; font-weight: 800; color: #1e1b4b; margin: 0;">{{ $stats['open_tickets'] }}</p>
            </div>

            <!-- Resolved KPI -->
            <div style="background: #f0f4ff; padding: 1.25rem; border-radius: 1.5rem; display: flex; flex-direction: column; justify-content: space-between; position: relative; transition: all 0.3s ease; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); cursor: pointer;" onmouseover="this.style.transform='translateY(-5px)';" onmouseout="this.style.transform='translateY(0)';">
                <h4 style="font-size: 0.85rem; font-weight: 700; color: #1e40af; margin: 0;">Closed Stats</h4>
                <div style="width: 80px; height: 80px; align-self: center; margin: 1rem 0; position: relative; display: flex; align-items: center; justify-content: center;">
                    <canvas id="kpiResolvedChart"></canvas>
                    <div style="position: absolute; text-align: center;">
                        @php $closedP = ($stats['tickets'] > 0) ? round(($stats['closed_tickets'] / $stats['tickets']) * 100) : 0; @endphp
                        <span style="display: block; font-size: 0.9rem; font-weight: 800; color: #1e3a8a;">{{ $closedP }}%</span>
                    </div>
                </div>
                <p style="font-size: 1.25rem; font-weight: 800; color: #1e1b4b; margin: 0;">{{ $stats['closed_tickets'] }}</p>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const activityCtx = document.getElementById('mainActivityChart').getContext('2d');
    const activityGradient = activityCtx.createLinearGradient(0, 0, 0, 250);
    activityGradient.addColorStop(0, 'rgba(99, 102, 241, 0.2)');
    activityGradient.addColorStop(1, 'rgba(99, 102, 241, 0)');

    new Chart(activityCtx, {
        type: 'line',
        data: {
            labels: ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'],
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
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { display: false }, ticks: { color: '#94a3b8', font: { size: 10 } } },
                y: { grid: { color: '#f1f5f9' }, ticks: { color: '#94a3b8', font: { size: 10 } } }
            }
        }
    });

    const createMiniRadial = (id, color, value) => {
        const ctx = document.getElementById(id).getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: [value, 100 - value],
                    backgroundColor: [color, '#e2e8f0'],
                    borderWidth: 0,
                }]
            },
            options: {
                cutout: '75%',
                maintainAspectRatio: false,
                plugins: { legend: { display: false }, tooltip: { enabled: false } },
            }
        });
    };

    @php 
        $total = $stats['tickets'] ?: 1;
        $openP = ($stats['open_tickets'] / $total) * 100;
        $closedP = ($stats['closed_tickets'] / $total) * 100;
    @endphp

    createMiniRadial('kpiOpenChart', '#10b981', {{ $openP }});
    createMiniRadial('kpiResolvedChart', '#6366f1', {{ $closedP }});
</script>

<div class="card" style="border-radius: 1.5rem; border: 1px solid #f1f5f9; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.04); background: white;">
    <div style="padding: 1.5rem 2rem; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center;">
        <h3 style="margin: 0; font-size: 1.1rem; font-weight: 800; color: #1e293b;">Recent Mission Updates</h3>
        <a href="{{ route('manager.tickets') }}" style="background: #f1f5f9; color: #475569; padding: 0.5rem 1.25rem; border-radius: 0.75rem; text-decoration: none; font-size: 0.85rem; font-weight: 700;">View All</a>
    </div>
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f8fafc;">
                    <th style="padding: 1rem 2rem; text-align: left; font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase;">ID & Customer</th>
                    <th style="padding: 1rem 2rem; text-align: left; font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase;">Subject</th>
                    <th style="padding: 1rem 2rem; text-align: left; font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase;">Status</th>
                    <th style="padding: 1rem 2rem; text-align: left; font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase;">Timestamp</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recent_tickets as $ticket)
                <tr style="border-bottom: 1px solid #f1f5f9;">
                    <td style="padding: 1.25rem 2rem;">
                        <div style="font-weight: 800; color: #1e293b;">#{{ $ticket->ticket_id }}</div>
                        <div style="font-size: 0.75rem; color: #94a3b8;">{{ $ticket->user->name }}</div>
                    </td>
                    <td style="padding: 1.25rem 2rem;">
                        <div style="font-weight: 600; color: #334155;">{{ $ticket->subject }}</div>
                    </td>
                    <td style="padding: 1.25rem 2rem;">
                        <span style="padding: 0.35rem 0.85rem; border-radius: 2rem; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; background: #f1f5f9;">
                            {{ $ticket->status }}
                        </span>
                    </td>
                    <td style="padding: 1.25rem 2rem; color: #64748b; font-size: 0.85rem;">
                        {{ $ticket->created_at->diffForHumans() }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
