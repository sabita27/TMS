@include('layouts.frontend.header')

<body class="antialiased">
    <nav style="background: white; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 1rem 2rem; display: flex; justify-content: space-between; align-items: center;">
        <a href="/" style="font-size: 1.5rem; font-weight: 800; color: var(--primary-color); text-decoration: none;">TMS PRO</a>
        <div>
            @auth
                <a href="{{ route('user.dashboard') }}" style="text-decoration: none; color: #374151; font-weight: 500;">Dashboard</a>
            @else
                <a href="{{ route('login') }}" style="text-decoration: none; color: #374151; font-weight: 500; margin-right: 1.5rem;">Login</a>
                <a href="{{ route('register') }}" style="background: var(--primary-color); color: white; padding: 0.5rem 1.5rem; border-radius: 0.5rem; text-decoration: none; font-weight: 500;">Register</a>
            @endauth
        </div>
    </nav>

    <main class="py-10">
        @yield('content')
    </main>

    @include('layouts.frontend.footer')
</body>
