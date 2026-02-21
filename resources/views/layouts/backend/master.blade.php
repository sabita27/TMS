@include('layouts.backend.header')

<body>
    @include('layouts.backend.navbar')

    <main style="flex-grow: 1; display: flex; flex-direction: column; overflow-y: auto; margin-left: 260px; transition: margin 0.3s; width: calc(100% - 260px);">
        @php
            $isUser = auth()->check() && auth()->user()->hasRole('user');
            $headerHeight = $isUser ? '100px' : '80px';
            $headerPadding = $isUser ? '0 3rem' : '0 1.5rem 0 3rem';
        @endphp
        <header style="background: #fff; height: {{ $headerHeight }}; padding: {{ $headerPadding }}; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 1px 3px rgba(0,0,0,0.1); position: sticky; top: 0; z-index: 1000;">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <!-- Hamburger Menu Button -->
                <button id="sidebar-toggle" onclick="document.getElementById('app-sidebar').classList.add('active'); document.getElementById('mobile-overlay').style.display='block';" style="display: none; background: none; border: none; font-size: 1.25rem; color: #4b5563; cursor: pointer;">
                    <i class="fas fa-bars"></i>
                </button>
                <div style="font-weight: 600; font-size: 1.1rem; color: #111827;">@yield('page_title', 'Dashboard')</div>
            </div>
            <div style="display: flex; align-items: center; gap: 1rem;">
                <div style="text-align: right;">
                    <div style="font-weight: 600; font-size: 0.9rem;">{{ Auth::user()->name }}</div>
                    <div style="font-size: 0.75rem; color: var(--text-muted);">{{ ucfirst(Auth::user()->getRoleNames()->first() ?? 'User') }}</div>
                </div>
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=4f46e5&color=fff" style="width: 42px; height: 42px; border-radius: 50%; border: 2px solid #eef2ff; box-shadow: 0 0 0 1px #4f46e5;">
            </div>
        </header>

        <div style="padding: 2rem; width: 100%; box-sizing: border-box; flex-grow: 1;">
            @include('layouts.backend.notifications') {{-- Simple notifications if needed --}}
            @yield('content')
        </div>

        @include('layouts.backend.footer')
    </main>

    <style>
        @media (max-width: 1024px) {
            main {
                margin-left: 0 !important;
                width: 100% !important;
            }

            #sidebar-toggle {
                display: block !important;
            }

            header {
                padding: 1rem !important; /* Reduce padding on mobile */
            }
        }
    </style>
</body>
</html>
