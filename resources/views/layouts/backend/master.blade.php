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
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <div class="header-user-text" style="text-align: right;">
                    <div style="font-weight: 600; font-size: 0.85rem; line-height: 1.2;">{{ Auth::user()->name }}</div>
                    <div style="font-size: 0.7rem; color: var(--text-muted);">{{ ucfirst(Auth::user()->getRoleNames()->first() ?? 'User') }}</div>
                </div>
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=4f46e5&color=fff" class="user-avatar" alt="Avatar">
            </div>
        </header>

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
        }
    </style>
</body>
</html>
