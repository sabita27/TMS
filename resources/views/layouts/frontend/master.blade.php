@include('layouts.frontend.header')

<body class="antialiased" style="min-height: 100vh; display: flex; flex-direction: column; background: radial-gradient(at 0% 0%, rgba(99, 102, 241, 0.05) 0, transparent 50%), radial-gradient(at 100% 100%, rgba(168, 85, 247, 0.05) 0, transparent 50%), #f8fafc; font-family: 'Inter', sans-serif;">
    @php
        $sys_name = \App\Models\Setting::get('system_name', 'TMS PRO');
        $sys_logo = \App\Models\Setting::get('system_logo');
    @endphp
    
    <nav style="background: white; border-bottom: 1px solid #e2e8f0; padding: 1.25rem 5%; display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; z-index: 100; backdrop-filter: blur(10px); background: rgba(255, 255, 255, 0.9); box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);">
        <a href="/" style="display: flex; align-items: center; gap: 0.75rem; text-decoration: none;">
            @if($sys_logo)
                <img src="{{ asset('storage/' . $sys_logo) }}" style="height: 32px; object-fit: contain;">
            @endif
            <span style="font-size: 1.35rem; font-weight: 800; color: #0f172a; letter-spacing: -0.025em;">{{ $sys_name }}</span>
        </a>
        
        <div style="display: flex; align-items: center; gap: 1.5rem;">
            @auth
                <a href="{{ route('user.dashboard') }}" style="text-decoration: none; color: #475569; font-size: 0.95rem; font-weight: 600; transition: color 0.2s;" onmouseover="this.style.color='#6366f1'" onmouseout="this.style.color='#475569'">Control Center</a>
            @else
                <a href="{{ route('login') }}" style="text-decoration: none; color: #475569; font-size: 0.95rem; font-weight: 600; transition: color 0.2s;" onmouseover="this.style.color='#6366f1'" onmouseout="this.style.color='#475569'">Login</a>
                <a href="{{ route('register') }}" style="background: #0f172a; color: white; padding: 0.7rem 1.5rem; border-radius: 0.75rem; text-decoration: none; font-size: 0.95rem; font-weight: 700; transition: all 0.2s; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);" onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 10px 15px -3px rgba(0, 0, 0, 0.1)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px -1px rgba(0, 0, 0, 0.1)';">
                    Register
                </a>
            @endauth
        </div>
    </nav>

    <main style="flex: 1; padding: 4rem 0;">
        @yield('content')
    </main>

    @include('layouts.frontend.footer')
</body>
