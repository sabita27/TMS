@include('layouts.backend.header')

<body>
    @include('layouts.backend.navbar')

    <main class="main-wrapper">
        @php
            $isUser = auth()->check() && auth()->user()->hasRole('user');
            $headerHeight = $isUser ? '100px' : '80px';
            $headerPadding = $isUser ? '0 3rem' : '0 1.5rem 0 3rem';
        @endphp
        <header class="header-main">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <!-- Hamburger Menu Button -->
                <button id="sidebar-toggle" onclick="document.getElementById('app-sidebar').classList.add('active'); document.getElementById('mobile-overlay').style.display='block';">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="page-title-text">@yield('page_title', 'Dashboard')</div>
            </div>
            <div style="display: flex; align-items: center; gap: 1.5rem;">
                {{-- Notifications Bell --}}
                <div style="position: relative; cursor: pointer;" onclick="document.getElementById('notification-dropdown').classList.toggle('show')">
                    <i class="fas fa-bell" style="font-size: 1.25rem; color: #64748b; transition: 0.2s;" onmouseover="this.style.color='#3b82f6'" onmouseout="this.style.color='#64748b'"></i>
                    @if(Auth::user()->unreadNotifications->count() > 0)
                        <span style="position: absolute; top: -6px; right: -6px; background: #ef4444; color: white; font-size: 0.6rem; font-weight: 800; padding: 0.15rem 0.35rem; border-radius: 1rem; border: 2px solid white;">
                            {{ Auth::user()->unreadNotifications->count() }}
                        </span>
                    @endif
                    
                    {{-- Dropdown --}}
                    <div id="notification-dropdown" class="notification-dropdown" onclick="event.stopPropagation()">
                        <div style="padding: 1rem 1.25rem; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center;">
                            <h4 style="margin: 0; font-size: 0.95rem; font-weight: 800; color: #0f172a;">Notifications</h4>
                            @if(Auth::user()->unreadNotifications->count() > 0)
                                <form action="{{ route('notifications.markAllRead') }}" method="POST" style="margin: 0;">
                                    @csrf
                                    <button type="submit" style="background: none; border: none; color: #3b82f6; font-size: 0.75rem; font-weight: 700; cursor: pointer; padding: 0;">Mark all read</button>
                                </form>
                            @endif
                        </div>
                        <div style="max-height: 350px; overflow-y: auto;">
                            @forelse(Auth::user()->unreadNotifications as $notification)
                                @php
                                    $type = $notification->data['type'] ?? 'ticket_reply';
                                    $icon = 'fa-comment-dots';
                                    $iconBg = '#eff6ff';
                                    $iconColor = '#3b82f6';
                                    $title = 'New Notification';
                                    $desc = $notification->data['message'] ?? '';
                                    
                                    if ($type === 'ticket_created') {
                                        $icon = 'fa-plus-circle';
                                        $iconBg = '#fff7ed';
                                        $iconColor = '#f59e0b';
                                        $title = 'New Ticket Raised';
                                    } elseif ($type === 'ticket_assigned') {
                                        $icon = 'fa-user-tag';
                                        $iconBg = '#f5f3ff';
                                        $iconColor = '#8b5cf6';
                                        $title = 'Ticket Assigned to You';
                                    } elseif ($type === 'status_updated') {
                                        $icon = 'fa-info-circle';
                                        $iconBg = '#f0fdf4';
                                        $iconColor = '#22c55e';
                                        $title = 'Ticket Status Updated';
                                    } else {
                                        $title = 'New Reply in #' . ($notification->data['ticket_number'] ?? '');
                                    }
                                @endphp
                                <a href="{{ route('ticket.show', $notification->data['ticket_id']) }}" style="display: block; padding: 1rem 1.25rem; border-bottom: 1px solid #f8fafc; text-decoration: none; transition: 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='white'">
                                    <div style="display: flex; gap: 0.75rem;">
                                        <div style="width: 36px; height: 36px; border-radius: 50%; background: {{ $iconBg }}; color: {{ $iconColor }}; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 0.85rem;">
                                            <i class="fas {{ $icon }}"></i>
                                        </div>
                                        <div>
                                            <p style="margin: 0 0 0.25rem 0; font-size: 0.85rem; color: #1e293b; font-weight: 700;">
                                                {{ $title }}
                                            </p>
                                            <p style="margin: 0 0 0.4rem 0; font-size: 0.8rem; color: #64748b; line-height: 1.4;">
                                                {{ $desc }}
                                            </p>
                                            <p style="margin: 0; font-size: 0.7rem; color: #94a3b8; font-weight: 600;">
                                                {{ $notification->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                </a>
                            @empty
                                <div style="padding: 2rem; text-align: center; color: #94a3b8;">
                                    <i class="fas fa-bell-slash" style="font-size: 1.5rem; margin-bottom: 0.75rem; opacity: 0.5;"></i>
                                    <p style="margin: 0; font-size: 0.85rem; font-weight: 600;">No new notifications</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div style="display: flex; align-items: center; gap: 0.75rem; border-left: 2px solid #f1f5f9; padding-left: 1.5rem;">
                    <div class="header-user-text" style="text-align: right;">
                        <div style="font-weight: 600; font-size: 0.85rem; line-height: 1.2;">{{ Auth::user()->name }}</div>
                        <div style="font-size: 0.7rem; color: var(--text-muted);">{{ ucfirst(Auth::user()->getRoleNames()->first() ?? 'User') }}</div>
                    </div>
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=4f46e5&color=fff" class="user-avatar" alt="Avatar">
                </div>
            </div>
        </header>

        <script>
            // Close notification dropdown when clicking outside
            document.addEventListener('click', function(event) {
                var dropdown = document.getElementById('notification-dropdown');
                var bell = dropdown.parentElement;
                if (!bell.contains(event.target)) {
                    dropdown.classList.remove('show');
                }
            });
        </script>

        <div class="content-inner">
            @include('layouts.backend.notifications') {{-- Simple notifications if needed --}}
            @yield('content')
        </div>

        @include('layouts.backend.footer')
    </main>

    <style>
        .main-wrapper {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            margin-left: 260px;
            transition: all 0.3s ease;
            width: calc(100% - 260px);
        }

        .content-inner {
            padding: 2rem;
            width: 100%;
            box-sizing: border-box;
            flex-grow: 1;
        }

        .header-main {
            height: {{ $isUser ? '100px' : '80px' }};
            padding: {{ $isUser ? '0 3rem' : '0 1.5rem 0 3rem' }};
            background: #fff; 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            box-shadow: 0 1px 3px rgba(0,0,0,0.1); 
            position: sticky; 
            top: 0; 
            z-index: 1001; 
            transition: all 0.3s ease;
        }

        #sidebar-toggle {
            display: none; 
            background: #f3f4f6; 
            border: none; 
            width: 40px; 
            height: 40px; 
            border-radius: 8px; 
            font-size: 1.1rem; 
            color: #4b5563; 
            cursor: pointer; 
            align-items: center; 
            justify-content: center;
        }

        .page-title-text {
            font-weight: 600; 
            font-size: 1.1rem; 
            color: #111827;
        }

        .user-avatar {
            width: 38px; 
            height: 38px; 
            border-radius: 50%; 
            border: 2px solid #eef2ff; 
            box-shadow: 0 0 0 1px #4f46e5;
        }

        .footer-main {
            margin-top: 2rem; 
            padding: 1.5rem 2rem; 
            border-top: 1px solid #e2e8f0; 
            background: #fff; 
            text-align: center;
        }

        .notification-dropdown {
            display: none;
            position: absolute;
            top: 150%;
            right: -10px;
            width: 320px;
            background: white;
            border-radius: 1rem;
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04);
            border: 1px solid #e2e8f0;
            z-index: 1000;
            overflow: hidden;
            animation: dropdownFade 0.2s ease-out;
        }

        .notification-dropdown.show {
            display: block;
        }

        @keyframes dropdownFade {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 1024px) {
            body { 
                display: block !important;
                overflow-y: auto !important;
                height: auto !important;
            }
            .sidebar-main { 
                position: fixed !important;
                z-index: 2000 !important;
            }
            .sidebar-overlay {
                z-index: 1999 !important;
            }
            .main-wrapper {
                margin-left: 0 !important;
                width: 100% !important;
                min-width: 100% !important;
                display: block !important;
            }

            .content-inner {
                padding: 1rem !important;
            }

            #sidebar-toggle {
                display: flex !important;
                width: 38px !important;
                height: 38px !important;
            }

            .header-main {
                padding: 0 1rem !important;
                height: 60px !important;
                width: 100% !important;
                box-sizing: border-box !important;
                background: #ffffff !important;
                border-bottom: 1px solid #f1f5f9 !important;
            }
            
            .page-title-text {
                font-size: 0.95rem !important;
                max-width: 180px;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .header-user-text {
                display: none;
            }
            
            .user-avatar {
                width: 32px !important;
                height: 32px !important;
            }

            .footer-main {
                padding: 1.25rem !important;
            }

            /* Notification Dropdown Mobile Optimization */
            .notification-dropdown {
                width: 300px !important;
                right: -80px !important;
            }
        }

        @media (max-width: 480px) {
            .notification-dropdown {
                position: fixed !important;
                top: 65px !important;
                left: 1rem !important;
                right: 1rem !important;
                width: calc(100% - 2rem) !important;
                max-width: none !important;
                max-height: 80vh !important;
                border-radius: 1.25rem !important;
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25) !important;
            }

            .notification-dropdown h4 {
                font-size: 0.85rem !important;
            }

            .notification-dropdown a {
                padding: 0.75rem 1rem !important;
            }

            .notification-dropdown a img, 
            .notification-dropdown a .fas {
                width: 32px !important;
                height: 32px !important;
            }
        }
    </style>
</body>
</html>
