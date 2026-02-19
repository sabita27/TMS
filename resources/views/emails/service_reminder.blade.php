<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Renewal Reminder — {{ $clientService->service->name ?? 'Service' }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #1e293b;
            background-color: #f1f5f9;
        }
        .wrapper {
            width: 100%;
            padding: 48px 20px;
            background-color: #f1f5f9;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px -10px rgba(0,0,0,0.15);
        }
        /* Header */
        .header {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            padding: 48px 40px 40px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 200px;
            height: 200px;
            border-radius: 50%;
            background: rgba(255,255,255,0.06);
        }
        .header::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -5%;
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background: rgba(255,255,255,0.04);
        }
        .header-icon {
            width: 64px;
            height: 64px;
            background: rgba(255,255,255,0.2);
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
            font-size: 28px;
        }
        .header h1 {
            color: #ffffff;
            font-size: 26px;
            font-weight: 800;
            letter-spacing: -0.5px;
            margin-bottom: 6px;
        }
        .header p {
            color: rgba(255,255,255,0.75);
            font-size: 14px;
            font-weight: 500;
        }

        /* Body */
        .body {
            padding: 40px;
        }
        .greeting {
            font-size: 20px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 12px;
        }
        .intro-text {
            color: #64748b;
            font-size: 15px;
            line-height: 1.7;
            margin-bottom: 32px;
        }
        .intro-text strong {
            color: #4f46e5;
            font-weight: 700;
        }

        /* Details Card */
        .details-card {
            background: #f8fafc;
            border-radius: 16px;
            padding: 28px;
            margin-bottom: 32px;
            border: 1px solid #e2e8f0;
        }
        .details-card-title {
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #94a3b8;
            margin-bottom: 20px;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #f1f5f9;
        }
        .detail-row:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }
        .detail-row:first-of-type {
            padding-top: 0;
        }
        .detail-label {
            color: #64748b;
            font-size: 13px;
            font-weight: 600;
        }
        .detail-value {
            color: #0f172a;
            font-size: 14px;
            font-weight: 700;
            text-align: right;
        }

        /* Status Badge */
        .badge {
            display: inline-block;
            padding: 5px 14px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .badge-expired   { background: #fee2e2; color: #dc2626; }
        .badge-today     { background: #fff7ed; color: #ea580c; }
        .badge-critical  { background: #fef9c3; color: #ca8a04; }
        .badge-warning   { background: #fff7ed; color: #c2410c; }
        .badge-ok        { background: #f0fdf4; color: #15803d; }

        /* Alert Box */
        .alert-box {
            border-radius: 12px;
            padding: 16px 20px;
            margin-bottom: 32px;
            display: flex;
            align-items: flex-start;
            gap: 14px;
            font-size: 14px;
            line-height: 1.6;
        }
        .alert-box.warning {
            background: #fff7ed;
            border: 1px solid #fed7aa;
            color: #9a3412;
        }
        .alert-box.danger {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
        }
        .alert-box.success {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #14532d;
        }
        .alert-icon {
            font-size: 20px;
            flex-shrink: 0;
            margin-top: 1px;
        }

        /* CTA */
        .cta-section {
            text-align: center;
            margin-bottom: 32px;
        }
        .btn {
            display: inline-block;
            padding: 14px 36px;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 10px;
            font-weight: 700;
            font-size: 15px;
            letter-spacing: 0.01em;
            box-shadow: 0 4px 15px rgba(79, 70, 229, 0.35);
        }

        /* Divider */
        .divider {
            height: 1px;
            background: #f1f5f9;
            margin: 0 40px;
        }

        /* Footer */
        .footer {
            padding: 28px 40px;
            text-align: center;
            background: #f8fafc;
        }
        .footer p {
            color: #94a3b8;
            font-size: 12px;
            line-height: 1.8;
        }
        .footer a {
            color: #4f46e5;
            text-decoration: none;
        }
        .company-name {
            font-weight: 700;
            color: #64748b;
            font-size: 13px;
            margin-bottom: 4px;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">

            <!-- Header -->
            <div class="header">
                <div class="header-icon">🔔</div>
                <h1>Service Renewal Reminder</h1>
                <p>{{ config('app.name') }} — Automated Notification</p>
            </div>

            <!-- Body -->
            <div class="body">
                <div class="greeting">Hello, {{ $clientService->client->name }}! 👋</div>
                <p class="intro-text">
                    This is a friendly reminder from <strong>{{ config('app.name') }}</strong> that your subscription for
                    <strong>{{ $clientService->service->name }}</strong> is approaching its renewal date.
                    Please review the details below to ensure uninterrupted service.
                </p>

                <!-- Service Details Card -->
                <div class="details-card">
                    <div class="details-card-title">📋 Service Subscription Details</div>

                    <div class="detail-row">
                        <span class="detail-label">Service Name</span>
                        <span class="detail-value">{{ $clientService->service->name }}</span>
                    </div>

                    <div class="detail-row">
                        <span class="detail-label">Client</span>
                        <span class="detail-value">{{ $clientService->client->name }}</span>
                    </div>

                    <div class="detail-row">
                        <span class="detail-label">Start Date</span>
                        <span class="detail-value">
                            {{ $clientService->start_date ? \Carbon\Carbon::parse($clientService->start_date)->format('d M, Y') : 'N/A' }}
                        </span>
                    </div>

                    <div class="detail-row">
                        <span class="detail-label">Expiry Date</span>
                        <span class="detail-value">
                            {{ $clientService->end_date ? \Carbon\Carbon::parse($clientService->end_date)->format('d M, Y') : 'Open-Ended' }}
                        </span>
                    </div>

                    @php
                        $diff = $clientService->end_date
                            ? \Carbon\Carbon::now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($clientService->end_date)->startOfDay(), false)
                            : null;
                    @endphp

                    <div class="detail-row">
                        <span class="detail-label">Status</span>
                        <span class="detail-value">
                            @if($diff === null)
                                <span class="badge badge-ok">ACTIVE</span>
                            @elseif($diff < 0)
                                <span class="badge badge-expired">EXPIRED ({{ abs($diff) }} days ago)</span>
                            @elseif($diff === 0)
                                <span class="badge badge-today">EXPIRES TODAY</span>
                            @elseif($diff <= 7)
                                <span class="badge badge-critical">{{ $diff }} DAYS LEFT</span>
                            @elseif($diff <= 30)
                                <span class="badge badge-warning">{{ $diff }} DAYS LEFT</span>
                            @else
                                <span class="badge badge-ok">{{ $diff }} DAYS LEFT</span>
                            @endif
                        </span>
                    </div>
                </div>

                <!-- Alert Message based on Days -->
                @if($diff !== null)
                    @if($diff < 0)
                        <div class="alert-box danger">
                            <span class="alert-icon">⚠️</span>
                            <div>
                                <strong>Service Expired!</strong> Your <strong>{{ $clientService->service->name }}</strong> subscription has expired
                                {{ abs($diff) }} day(s) ago. Please renew immediately to continue enjoying uninterrupted service.
                            </div>
                        </div>
                    @elseif($diff <= 7)
                        <div class="alert-box danger">
                            <span class="alert-icon">🚨</span>
                            <div>
                                <strong>Urgent Renewal Required!</strong> Your service expires in <strong>{{ $diff }} day(s)</strong>.
                                Please renew immediately to avoid service interruption.
                            </div>
                        </div>
                    @elseif($diff <= 30)
                        <div class="alert-box warning">
                            <span class="alert-icon">⏰</span>
                            <div>
                                <strong>Renewal Recommended.</strong> Your service subscription expires in <strong>{{ $diff }} day(s)</strong>.
                                We recommend renewing soon to avoid any last-minute issues.
                            </div>
                        </div>
                    @else
                        <div class="alert-box success">
                            <span class="alert-icon">✅</span>
                            <div>
                                Your service is active for <strong>{{ $diff }} more days</strong>. This is an advance reminder so you
                                can plan your renewal at your convenience.
                            </div>
                        </div>
                    @endif
                @endif

                <!-- CTA Button -->
                <div class="cta-section">
                    <a href="{{ config('app.url') }}" class="btn">Contact Support / Renew Service</a>
                </div>

                <p style="color: #94a3b8; font-size: 13px; text-align: center; line-height: 1.7;">
                    If you have already renewed your service or have any questions, please
                    contact our support team and we'll be happy to assist you.
                </p>
            </div>

            <div class="divider"></div>

            <!-- Footer -->
            <div class="footer">
                <p class="company-name">{{ config('app.name') }}</p>
                <p>
                    © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.<br>
                    This is an automated notification. Please do not reply directly to this email.<br>
                    <a href="{{ config('app.url') }}">Visit our website</a>
                </p>
            </div>

        </div>
    </div>
</body>
</html>
