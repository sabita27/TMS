@extends('layouts.backend.master')

@section('page_title', 'User Dashboard')
@section('header_height', '85px')
@section('header_padding', '0 2.5rem')

@section('content')
<!-- Welcome Banner -->
<div style="background: linear-gradient(135deg, var(--primary-color) 0%, #7c3aed 100%); padding: 2.5rem; border-radius: 1.25rem; margin-bottom: 2.5rem; color: white; position: relative; overflow: hidden; box-shadow: 0 10px 20px rgba(79, 70, 229, 0.15);">
    <div style="position: relative; z-index: 2;">
        <h2 style="margin: 0; font-size: 2rem; font-weight: 800; letter-spacing: -0.5px;">Welcome back, {{ Auth::user()->name }}!</h2>
        <p style="margin: 0.5rem 0 0 0; opacity: 0.9; font-size: 1.1rem;">Manage your support requests and browse our solutions in one place.</p>
    </div>
    <div style="position: absolute; right: -50px; top: -50px; width: 250px; height: 250px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
    <div style="position: absolute; right: 50px; bottom: -80px; width: 150px; height: 150px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; margin-bottom: 2.5rem;">
    <!-- Stat Cards -->
    <div class="stat-premium-card">
        <div class="card-icon" style="background: #eef2ff; color: var(--primary-color);">
            <i class="fas fa-ticket-alt"></i>
        </div>
        <div class="card-info">
            <span class="label">Total Tickets</span>
            <span class="value">{{ $stats['total_tickets'] }}</span>
        </div>
        <div class="card-trend text-primary">Lifetime Support</div>
    </div>

    <div class="stat-premium-card">
        <div class="card-icon" style="background: #fffbeb; color: #f59e0b;">
            <i class="fas fa-clock"></i>
        </div>
        <div class="card-info">
            <span class="label">Pending Requests</span>
            <span class="value">{{ $stats['open_tickets'] }}</span>
        </div>
        <div class="card-trend text-warning">Action Required</div>
    </div>

    <div class="stat-premium-card">
        <div class="card-icon" style="background: #ecfdf5; color: #10b981;">
            <i class="fas fa-check-double"></i>
        </div>
        <div class="card-info">
            <span class="label">Resolved Cases</span>
            <span class="value">{{ $stats['resolved_tickets'] }}</span>
        </div>
        <div class="card-trend text-success">Excellent Service</div>
    </div>
</div>

<div class="dashboard-main-grid">
    <!-- Recent Tickets -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Latest Support Activity</h3>
            <a href="{{ route('user.tickets') }}" class="view-all-link">View All Tickets</a>
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Reference</th>
                        <th>Subject</th>
                        <th>Status</th>
                        <th>Logged On</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recent_tickets as $ticket)
                    <tr>
                        <td style="font-weight: 700; color: var(--primary-color);">{{ $ticket->ticket_id }}</td>
                        <td>
                            <div style="font-weight: 500;">{{ Str::limit($ticket->subject, 35) }}</div>
                            <div style="font-size: 0.75rem; color: #94a3b8;">{{ $ticket->product->name ?? 'General Support' }}</div>
                        </td>
                        <td>
                            @php
                                $statusMap = [
                                    'open' => ['bg' => '#dbeafe', 'text' => '#1e40af', 'icon' => 'fa-envelope-open'],
                                    'in-progress' => ['bg' => '#fef3c7', 'text' => '#92400e', 'icon' => 'fa-spinner fa-spin'],
                                    'resolved' => ['bg' => '#d1fae5', 'text' => '#065f46', 'icon' => 'fa-check-circle'],
                                    'closed' => ['bg' => '#f3f4f6', 'text' => '#4b5563', 'icon' => 'fa-times-circle'],
                                ];
                                $s = $statusMap[$ticket->status] ?? $statusMap['open'];
                            @endphp
                            <span class="status-badge" style="background: {{ $s['bg'] }}; color: {{ $s['text'] }};">
                                <i class="fas {{ $s['icon'] }}"></i> {{ ucfirst($ticket->status) }}
                            </span>
                        </td>
                        <td style="color: #64748b; font-size: 0.85rem;">{{ $ticket->created_at->diffForHumans() }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 4rem;">
                            <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" style="width: 80px; opacity: 0.2; margin-bottom: 1rem;">
                            <div style="color: #94a3b8; font-weight: 500;">No support history found.</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Quick Support Card -->
    <div style="display: flex; flex-direction: column; gap: 2rem;">
        <div class="card" style="padding: 1.5rem; border-radius: 1.25rem;">
            <h4 style="margin: 0 0 1rem 0; font-size: 1rem; font-weight: 800; color: #1e293b;">Your Support Activity</h4>
            <canvas id="supportChart" height="250"></canvas>
        </div>

        <div class="support-action-card">
            <h3 style="margin: 0; font-size: 1.5rem; font-weight: 800;">Need Help?</h3>
            <p style="margin: 1rem 0 2rem 0; opacity: 0.85; line-height: 1.6;">Our dedicated support team is available 24/7 to resolve your technical issues.</p>
            <a href="{{ route('user.tickets.create') }}" class="btn-raise-premium">
                <i class="fas fa-plus-circle"></i> Raise New Ticket
            </a>
            <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid rgba(255,255,255,0.2);">
                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                    <div style="width: 40px; height: 40px; border-radius: 50%; background: rgba(255,255,255,0.1); display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-phone-alt"></i>
                    </div>
                    <div style="font-size: 0.85rem;">
                        <span style="display: block; opacity: 0.7;">Emergency Contact</span>
                        <span style="font-weight: 700;">+1 800 123 4567</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('supportChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Pending', 'Resolved', 'Other'],
                datasets: [{
                    data: [{{ $stats['open_tickets'] }}, {{ $stats['resolved_tickets'] }}, {{ max(0, $stats['total_tickets'] - $stats['open_tickets'] - $stats['resolved_tickets']) }}],
                    backgroundColor: ['#f59e0b', '#10b981', '#4f46e5'],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            padding: 20,
                            font: { family: 'Outfit', size: 12, weight: '600' }
                        }
                    }
                },
                cutout: '70%'
            }
        });
    });
</script>

<style>
    .dashboard-main-grid { display: grid; grid-template-columns: 1fr 340px; gap: 2rem; }
    @media (max-width: 1200px) {
        .dashboard-main-grid { grid-template-columns: 1fr; }
    }
    .stat-premium-card { background: white; padding: 1.75rem; border-radius: 1rem; display: flex; flex-direction: column; gap: 1.25rem; transition: 0.3s; position: relative; overflow: hidden; border: 1px solid #f1f5f9; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
    .stat-premium-card:hover { transform: translateY(-5px); box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); }
    .card-icon { width: 48px; height: 48px; border-radius: 0.75rem; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; }
    .card-info { display: flex; flex-direction: column; }
    .card-info .label { font-size: 0.875rem; font-weight: 600; color: #64748b; }
    .card-info .value { font-size: 2rem; font-weight: 800; color: #1e293b; line-height: 1; margin-top: 0.25rem; }
    .card-trend { font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; }
    
    .status-badge { padding: 0.35rem 0.75rem; border-radius: 999px; font-size: 0.75rem; font-weight: 700; display: inline-flex; align-items: center; gap: 0.4rem; white-space: nowrap; }
    .view-all-link { font-size: 0.875rem; font-weight: 700; color: var(--primary-color); text-decoration: none; border-bottom: 2px solid transparent; transition: 0.2s; }
    .view-all-link:hover { border-bottom-color: var(--primary-color); }
    
    .support-action-card { background: var(--primary-color); color: white; border-radius: 1.25rem; padding: 2.5rem; display: flex; flex-direction: column; box-shadow: 0 20px 25px -5px rgba(79, 70, 229, 0.2); }
    .btn-raise-premium { background: white; color: var(--primary-color); padding: 1rem; border-radius: 0.75rem; text-decoration: none; display: flex; align-items: center; justify-content: center; gap: 0.75rem; font-weight: 800; transition: 0.3s; box-shadow: 0 10px 15px rgba(0,0,0,0.1); }
    .btn-raise-premium:hover { transform: scale(1.03); box-shadow: 0 15px 20px rgba(0,0,0,0.15); background: #f8fafc; }
    
    .text-primary { color: var(--primary-color); }
    .text-warning { color: #f59e0b; }
    .text-success { color: #10b981; }
</style>
@endsection
