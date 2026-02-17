@extends('layouts.backend.master')

@section('content')
<div class="dashboard-header" style="margin-bottom: 2rem;">
    <h1 style="font-size: 1.5rem; font-weight: 700; color: #111827;">Admin Dashboard</h1>
    <p style="color: #6b7280; font-size: 0.875rem;">Welcome back, Admin! Here's what's happening today.</p>
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

<!-- High-Performance Analytical Section -->
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
            <!-- Open Tickets KPI (Green Theme) -->
            <div style="background: #eef7f2; padding: 1.25rem; border-radius: 1.5rem; display: flex; flex-direction: column; justify-content: space-between; position: relative; transition: all 0.3s ease; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); cursor: pointer;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 20px 25px -5px rgba(0, 0, 0, 0.05)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px -1px rgba(0,0,0,0.02)';">
                <h4 style="font-size: 0.85rem; font-weight: 700; color: #166534; margin: 0;">Open Requests</h4>
                <div style="width: 80px; height: 80px; align-self: center; margin: 1rem 0; position: relative; display: flex; align-items: center; justify-content: center;">
                    <canvas id="kpiOpenChart"></canvas>
                    <div style="position: absolute; text-align: center;">
                        @php $openP = ($stats['tickets'] > 0) ? round(($stats['open_tickets'] / $stats['tickets']) * 100) : 0; @endphp
                        <span style="display: block; font-size: 0.9rem; font-weight: 800; color: #064e3b;">{{ $openP }}%</span>
                        <span style="display: block; font-size: 0.5rem; font-weight: 700; color: #15803d; text-transform: uppercase;">Open</span>
                    </div>
                </div>
                <p style="font-size: 1.25rem; font-weight: 800; color: #1e1b4b; margin: 0;">{{ $stats['open_tickets'] }}</p>
            </div>

            <!-- Resolved KPI (Blue Theme) -->
            <div style="background: #f0f4ff; padding: 1.25rem; border-radius: 1.5rem; display: flex; flex-direction: column; justify-content: space-between; position: relative; transition: all 0.3s ease; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); cursor: pointer;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 20px 25px -5px rgba(0, 0, 0, 0.05)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px -1px rgba(0,0,0,0.02)';">
                <h4 style="font-size: 0.85rem; font-weight: 700; color: #1e40af; margin: 0;">Closed Stats</h4>
                <div style="width: 80px; height: 80px; align-self: center; margin: 1rem 0; position: relative; display: flex; align-items: center; justify-content: center;">
                    <canvas id="kpiResolvedChart"></canvas>
                    <div style="position: absolute; text-align: center;">
                        @php $closedP = ($stats['tickets'] > 0) ? round(($stats['closed_tickets'] / $stats['tickets']) * 100) : 0; @endphp
                        <span style="display: block; font-size: 0.9rem; font-weight: 800; color: #1e3a8a;">{{ $closedP }}%</span>
                        <span style="display: block; font-size: 0.5rem; font-weight: 700; color: #1d4ed8; text-transform: uppercase;">Done</span>
                    </div>
                </div>
                <p style="font-size: 1.25rem; font-weight: 800; color: #1e1b4b; margin: 0;">{{ $stats['closed_tickets'] }}</p>
            </div>

            <!-- Priority KPI (Yellow Theme) -->
            <div style="background: #fff9e6; padding: 1.25rem; border-radius: 1.5rem; display: flex; flex-direction: column; justify-content: space-between; position: relative; transition: all 0.3s ease; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); cursor: pointer;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 20px 25px -5px rgba(0, 0, 0, 0.05)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px -1px rgba(0,0,0,0.02)';">
                <h4 style="font-size: 0.85rem; font-weight: 700; color: #854d0e; margin: 0;">High Priority</h4>
                <div style="width: 80px; height: 80px; align-self: center; margin: 1rem 0; position: relative; display: flex; align-items: center; justify-content: center;">
                    <canvas id="kpiStaffChart"></canvas>
                    <div style="position: absolute; text-align: center;">
                        @php $urgentP = ($stats['tickets'] > 0) ? round((($tickets_by_priority['high'] ?? 0) / $stats['tickets']) * 100) : 0; @endphp
                        <span style="display: block; font-size: 0.9rem; font-weight: 800; color: #713f12;">{{ $urgentP }}%</span>
                        <span style="display: block; font-size: 0.5rem; font-weight: 700; color: #a16207; text-transform: uppercase;">Urgent</span>
                    </div>
                </div>
                <p style="font-size: 1.25rem; font-weight: 800; color: #1e1b4b; margin: 0;">{{ $tickets_by_priority['high'] ?? 0 }}</p>
            </div>

            <!-- Critical KPI (Red Theme) -->
            <div style="background: #fff0f0; padding: 1.25rem; border-radius: 1.5rem; display: flex; flex-direction: column; justify-content: space-between; position: relative; transition: all 0.3s ease; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); cursor: pointer;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 20px 25px -5px rgba(0, 0, 0, 0.05)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px -1px rgba(0,0,0,0.02)';">
                <h4 style="font-size: 0.85rem; font-weight: 700; color: #9f1239; margin: 0;">System Agents</h4>
                <div style="width: 80px; height: 80px; align-self: center; margin: 1rem 0; position: relative; display: flex; align-items: center; justify-content: center;">
                    <canvas id="kpiPriorityChart"></canvas>
                    <div style="position: absolute; text-align: center;">
                        <span style="display: block; font-size: 0.9rem; font-weight: 800; color: #881337;">Active</span>
                        <span style="display: block; font-size: 0.5rem; font-weight: 700; color: #be123c; text-transform: uppercase;">Staff</span>
                    </div>
                </div>
                <p style="font-size: 1.25rem; font-weight: 800; color: #1e1b4b; margin: 0;">{{ $stats['agents'] }}</p>
            </div>
        </div>
    </div>
</div>

<!-- New Analytical Charts Row -->
<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
    <!-- Agent Wise Tickets (Bar Chart) -->
    <div class="card" style="padding: 1.75rem; border: none; border-radius: 1.5rem; background: white; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05); border: 1px solid #f1f5f9; display: flex; flex-direction: column;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.5rem;">
            <div>
                <h3 style="font-size: 1.125rem; font-weight: 800; color: #0f172a; margin: 0;">Agent Distributions</h3>
                <p style="font-size: 0.8125rem; color: #64748b; margin-top: 0.25rem;">Workload per support representative</p>
            </div>
            <div style="background: #fef2f2; color: #ef4444; padding: 0.35rem 0.75rem; border-radius: 0.75rem; font-size: 0.65rem; font-weight: 800; border: 1px solid #fee2e2;">STAFF</div>
        </div>
        <div style="height: 250px;">
            <canvas id="agentBarChart"></canvas>
        </div>
    </div>

    <!-- This Year Tickets (Line Chart) -->
    <div class="card" style="padding: 1.75rem; border: none; border-radius: 1.5rem; background: white; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05); border: 1px solid #f1f5f9; display: flex; flex-direction: column;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.5rem;">
            <div>
                <h3 style="font-size: 1.125rem; font-weight: 800; color: #0f172a; margin: 0;">Yearly Overview</h3>
                <p style="font-size: 0.8125rem; color: #64748b; margin-top: 0.25rem;">Ticket volume trends for {{ date('Y') }}</p>
            </div>
            <div style="background: #f0fdf4; color: #10b981; padding: 0.35rem 0.75rem; border-radius: 0.75rem; font-size: 0.65rem; font-weight: 800; border: 1px solid #dcfce7;">TREND</div>
        </div>
        <div style="height: 250px;">
            <canvas id="yearlyTrendChart"></canvas>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Existing Support Performance Chart
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
                pointRadius: 0,
                fill: true,
                backgroundColor: activityGradient,
                borderCapStyle: 'round'
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: { legend: { display: false }, tooltip: { mode: 'index', intersect: false } },
            scales: {
                x: { grid: { display: false }, border: { display: false }, ticks: { color: '#94a3b8', font: { size: 10 } } },
                y: { grid: { color: '#f1f5f9', drawBorder: false }, border: { display: false }, ticks: { color: '#94a3b8', font: { size: 10 }, stepSize: 25 } }
            }
        }
    });

    // 1. Agent Distributions (Line Chart)
    const agentCtx = document.getElementById('agentBarChart').getContext('2d');
    const agentGradient = agentCtx.createLinearGradient(0, 0, 0, 250);
    agentGradient.addColorStop(0, 'rgba(79, 70, 229, 0.2)');
    agentGradient.addColorStop(1, 'rgba(79, 70, 229, 0)');

    new Chart(agentCtx, {
        type: 'line',
        data: {
            labels: @json($tickets_by_agent->keys()),
            datasets: [{
                label: 'Tickets',
                data: @json($tickets_by_agent->values()),
                borderColor: '#4f46e5',
                borderWidth: 4,
                tension: 0.45,
                pointRadius: 6,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#4f46e5',
                pointBorderWidth: 3,
                fill: true,
                backgroundColor: agentGradient,
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { display: false }, border: { display: false }, ticks: { font: { weight: '600', size: 10 } } },
                y: { grid: { color: '#f1f5f9' }, border: { display: false }, ticks: { stepSize: 1, color: '#94a3b8', font: { size: 10 } } }
            }
        }
    });

    // 2. Yearly Overview Tickets (Line Chart)
    const yearlyCtx = document.getElementById('yearlyTrendChart').getContext('2d');
    const yearlyGradient = yearlyCtx.createLinearGradient(0, 0, 0, 250);
    yearlyGradient.addColorStop(0, 'rgba(16, 185, 129, 0.2)');
    yearlyGradient.addColorStop(1, 'rgba(16, 185, 129, 0)');

    const monthlyData = @json($tickets_this_year);
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    const plotData = months.map((m, i) => monthlyData[i+1] || 0);

    new Chart(yearlyCtx, {
        type: 'line',
        data: {
            labels: months,
            datasets: [{
                label: 'Volume',
                data: plotData,
                borderColor: '#10b981',
                borderWidth: 4,
                tension: 0.4,
                fill: true,
                backgroundColor: yearlyGradient,
                pointRadius: 0,
                borderCapStyle: 'round'
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { display: false }, border: { display: false }, ticks: { font: { size: 10 } } },
                y: { grid: { color: '#f1f5f9' }, border: { display: false }, ticks: { stepSize: 5, color: '#94a3b8', font: { size: 10 } } }
            }
        }
    });

    // 3. Mini Radial Gauges (Existing)
    const createMiniRadial = (id, color, value) => {
        const ctx = document.getElementById(id).getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: [value, 100 - value],
                    backgroundColor: [color, '#e2e8f0'],
                    borderWidth: 0,
                    circumference: 360,
                    rotation: 0
                }]
            },
            options: {
                cutout: '75%',
                maintainAspectRatio: false,
                plugins: { legend: { display: false }, tooltip: { enabled: false } },
                animation: { duration: 1500, easing: 'easeOutQuart' }
            }
        });
    };

    @php 
        $total = $stats['tickets'] ?: 1;
        $openP = ($stats['open_tickets'] / $total) * 100;
        $closedP = (($stats['closed_tickets'] ?? 0) / $total) * 100;
        $highP = (($tickets_by_priority['high'] ?? 0) / $total) * 100;
        $staffP = 85; 
    @endphp

    createMiniRadial('kpiOpenChart', '#10b981', {{ $openP }});
    createMiniRadial('kpiResolvedChart', '#6366f1', {{ $closedP }});
    createMiniRadial('kpiPriorityChart', '#fbbf24', {{ $urgentP ?? 0 }});
    createMiniRadial('kpiStaffChart', '#ef4444', 100); // For mapping color to specific staff kpis as shown previously
</script>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Recent Tickets</h3>
        <a href="#" class="btn btn-primary" style="font-size: 0.75rem;">View All</a>
    </div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Ticket ID</th>
                    <th>User</th>
                    <th>Product</th>
                    <th>Subject</th>
                    <th>Status</th>
                    <th>Priority</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recent_tickets as $ticket)
                <tr>
                    <td>#{{ $ticket->ticket_id }}</td>
                    <td>{{ $ticket->user->name }}</td>
                    <td>{{ $ticket->product->name ?? 'N/A' }}</td>
                    <td>{{ $ticket->subject }}</td>
                    <td>
                        <span class="badge {{ $ticket->status == 'open' ? 'badge-danger' : ($ticket->status == 'closed' ? 'badge-success' : 'badge-warning') }}">
                            {{ ucfirst($ticket->status) }}
                        </span>
                    </td>
                    <td>
                        <span class="badge {{ $ticket->priority == 'high' ? 'badge-danger' : ($ticket->priority == 'medium' ? 'badge-warning' : 'badge-info') }}">
                            {{ ucfirst($ticket->priority) }}
                        </span>
                    </td>
                    <td>{{ $ticket->created_at->format('M d, Y') }}</td>
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
@endsection
