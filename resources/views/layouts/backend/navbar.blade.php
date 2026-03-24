<aside id="app-sidebar" class="sidebar-main" style="background: var(--sidebar-bg); color: var(--sidebar-text); display: flex; flex-direction: column; position: fixed; left: 0; top: 0; bottom: 0; transition: transform 0.3s ease-in-out;">
    <div style="padding: 2rem 1.5rem; text-align: center; border-bottom: 1px solid #374151; display: flex; justify-content: space-between; align-items: center;">
        @php
            $sys_logo = \App\Models\Setting::get('system_logo');
            $sys_name = \App\Models\Setting::get('system_name', 'TMS PRO');
            $logo_height = \App\Models\Setting::get('system_logo_height', '35');
            $logo_width = \App\Models\Setting::get('system_logo_width', '120');
        @endphp
        @if($sys_logo && file_exists(public_path('storage/' . $sys_logo)))
            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <img src="{{ asset('storage/' . $sys_logo) }}" style="height: {{ $logo_height }}px; width: {{ $logo_width }}px; object-fit: contain; border: none; outline: none; display: block;" alt="Logo">
            </div>
        @else
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <div style="width: 35px; height: 35px; background: linear-gradient(135deg, #6366f1, #4f46e5); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; font-weight: 800; font-size: 1.2rem;">
                    {{ substr($sys_name, 0, 1) }}
                </div>
                <h2 style="margin: 0; font-size: 1.25rem; color: #fff; letter-spacing: 1px; font-weight: 700;">{{ $sys_name }}</h2>
            </div>
        @endif
        <button class="close-sidebar-btn" onclick="document.getElementById('app-sidebar').classList.remove('active'); document.getElementById('mobile-overlay').style.display='none';" style="background: #374151; border: none; color: #fff; width: 32px; height: 32px; border-radius: 6px; font-size: 1rem; cursor: pointer; display: none; align-items: center; justify-content: center;">
            <i class="fas fa-times"></i>
        </button>
    </div>
    
    <nav style="flex-grow: 1; padding: 1.5rem 0; overflow-y: auto;">
        @php $curr = Route::currentRouteName(); @endphp
        
        {{-- Main Dashboard for All Roles --}}
        @php
            $showMain = Auth::check();
            $rolePrefix = strtolower(Auth::user()->getRoleNames()->first() ?? 'user');
            $dashboardRoute = Route::has("$rolePrefix.dashboard") ? "$rolePrefix.dashboard" : 'user.dashboard';
        @endphp

        @if($showMain && Auth::user()->can('view dashboard'))
            <div class="nav-label">MAIN</div>
            <a href="{{ route($dashboardRoute) }}" class="nav-item-link {{ $curr == $dashboardRoute ? 'active' : '' }}">
                <i class="fas fa-th-large"></i> Dashboard
            </a>
        @endif
        
        {{-- Management Section - Permission Based --}}
        @canany(['manage users', 'manage products', 'manage clients', 'manage projects', 'manage services'])
            <div class="nav-label">MANAGEMENT</div>
            
            @can('manage users')
                <a href="{{ route('admin.users') }}" class="nav-item-link {{ $curr == 'admin.users' ? 'active' : '' }}">
                    <i class="fas fa-users-cog"></i> User 
                </a>
            @endcan

            @can('manage products')
                <a href="{{ route('admin.products') }}" class="nav-item-link {{ str_contains($curr, 'admin.products') ? 'active' : '' }}">
                    <i class="fas fa-box-open"></i> Product 
                </a>
            @endcan

            @can('manage clients')
                <a href="{{ route('admin.clients') }}" class="nav-item-link {{ str_contains($curr, 'admin.clients') ? 'active' : '' }}">
                    <i class="fas fa-user-tie"></i> Client 
                </a>
            @endcan

            @can('manage projects')
                <a href="{{ route('admin.projects') }}" class="nav-item-link {{ str_contains($curr, 'admin.projects') ? 'active' : '' }}">
                    <i class="fas fa-project-diagram"></i> Project
                </a>
            @endcan

            @can('manage services')
                <a href="{{ route('admin.services') }}" class="nav-item-link {{ str_contains($curr, 'admin.services') ? 'active' : '' }}">
                    <i class="fas fa-concierge-bell"></i> Service
                </a>
            @endcan
        @endcanany

        @canany(['admin', 'manage roles', 'manage categories', 'manage designations', 'manage positions'])
            <div class="nav-label">SYSTEM</div>
            @if(Auth::user()->hasRole('admin'))
                <a href="{{ route('admin.setup') }}" class="nav-item-link {{ str_contains($curr, 'admin.setup') ? 'active' : '' }}">
                    <i class="fas fa-tools"></i> Setup Hub
                </a>
            @endif
        @endcanany

        {{-- Access Control Section --}}
        @canany(['manage roles', 'manage permissions', 'view profile'])
            <div class="nav-label">ACCESS CONTROL</div>
            @can('manage roles')
                <a href="{{ route('admin.roles') }}" class="nav-item-link {{ str_contains($curr, 'admin.roles') ? 'active' : '' }}">
                    <i class="fas fa-user-shield"></i> Roles
                </a>
            @endcan
            @can('manage permissions')
                <a href="{{ route('admin.permissions') }}" class="nav-item-link {{ str_contains($curr, 'admin.permissions') ? 'active' : '' }}">
                    <i class="fas fa-key"></i> Permissions
                </a>
            @endcan
            @can('view profile')
                <a href="{{ route('user.profile') }}" class="nav-item-link {{ $curr == 'user.profile' ? 'active' : '' }}">
                    <i class="fas fa-user-circle"></i> Profile Settings
                </a>
            @endcan
        @endcanany

        
        {{-- Support & Operations Section - Fully Dynamic --}}
        @php
            $canManageAll = Auth::user()->can('manage tickets');
            $canEditAssigned = Auth::user()->can('edit tickets');
            $canCreate = Auth::user()->can('create tickets');
        @endphp

        @if($canManageAll || $canEditAssigned || $canCreate)
            <div class="nav-label">
                @if($canManageAll || $canEditAssigned)
                    OPERATIONS
                @else
                    SUPPORT
                @endif
            </div>

            @can('create tickets')
                <a href="{{ route('user.tickets.create') }}" class="nav-item-link {{ $curr == 'user.tickets.create' ? 'active' : '' }}">
                    <i class="fas fa-plus-circle"></i> Raise Ticket
                </a>
            @endcan

            {{-- Manager/Admin level: All Tickets --}}
            @can('manage tickets')
                <a href="{{ route('manager.tickets') }}" class="nav-item-link {{ $curr == 'manager.tickets' ? 'active' : '' }}">
                    <i class="fas fa-tasks"></i> All Tickets
                </a>
                <a href="{{ route('manager.conversations') }}" class="nav-item-link {{ $curr == 'manager.conversations' ? 'active' : '' }}">
                    <i class="fas fa-comment-medical"></i> Conversations
                </a>
            @endcan

            {{-- Staff level: Assigned Tickets --}}
            @can('edit tickets')
                <a href="{{ route('staff.assigned_tickets') }}" class="nav-item-link {{ $curr == 'staff.assigned_tickets' ? 'active' : '' }}">
                    <i class="fas fa-clipboard-list"></i> Assigned Tickets
                </a>
            @endcan

            {{-- My Tickets - Permission Based --}}
            @can('view my tickets')
                <a href="{{ route('user.tickets') }}" class="nav-item-link {{ $curr == 'user.tickets' ? 'active' : '' }}">
                    <i class="fas fa-ticket-alt"></i> My Tickets
                </a>
            @endcan

            <a href="{{ route('user.products') }}" class="nav-item-link {{ $curr == 'user.products' ? 'active' : '' }}">
                <i class="fas fa-shopping-bag"></i> Browse Products
            </a>
        @endif
    </nav>
    
    <div style="padding: 1.5rem; border-top: 1px solid #374151;">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i> Logout System
            </button>
        </form>
    </div>
</aside>

<!-- Mobile Overlay -->
<div id="mobile-overlay" class="sidebar-overlay" onclick="document.getElementById('app-sidebar').classList.remove('active'); this.style.display='none';" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); display: none;"></div>

<style>
    .nav-label { font-size: 0.7rem; font-weight: 700; color: #4b5563; padding: 1.25rem 1.5rem 0.5rem; text-transform: uppercase; letter-spacing: 0.1em; }
    .nav-item-link { padding: 0.75rem 1.5rem; display: flex; align-items: center; gap: 0.75rem; color: var(--sidebar-text); text-decoration: none; font-size: 0.9rem; font-weight: 500; transition: 0.2s; border-left: 3px solid transparent; }
    .nav-item-link:hover { background: var(--sidebar-hover); color: white; }
    .nav-item-link.active { background: #1f2937; color: white; border-left-color: var(--primary-color); }
    .nav-item-link i { width: 20px; text-align: center; font-size: 1.1rem; opacity: 0.8; }
    .logout-btn { background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.2); color: #f87171; width: 100%; text-align: left; padding: 0.75rem 1rem; cursor: pointer; border-radius: 0.5rem; font-weight: 600; display: flex; align-items: center; gap: 0.75rem; transition: 0.2s; }
    .logout-btn:hover { background: #ef4444; color: white; }

    .sidebar-main {
        width: 260px;
        z-index: 1000;
        height: 100vh;
    }
    
    .sidebar-overlay {
        z-index: 900;
    }

    @media (max-width: 1024px) {
        .sidebar-main { 
            display: flex !important;
            transform: translateX(-100%); 
            width: 280px !important; 
            z-index: 1010 !important; /* Above header */
            transition: transform 0.3s ease;
        }
        .sidebar-main.active { 
            transform: translateX(0); 
        }
        
        .sidebar-overlay {
            z-index: 1005 !important; /* Between sidebar and header */
        }
        
        .close-sidebar-btn { 
            display: flex !important; 
        }
    }
</style>
    
    <script>
        function toggleSettingsMenu(event) {
            event.preventDefault();
            const submenu = document.getElementById('settings-submenu');
            const arrow = document.getElementById('settings-arrow');
            
            if (submenu.style.display === 'none') {
                submenu.style.display = 'block';
                arrow.style.transform = 'rotate(180deg)';
            } else {
                submenu.style.display = 'none';
                arrow.style.transform = 'rotate(0deg)';
            }
        }
        
        // Initialize arrow state if submenu is open on load
        document.addEventListener('DOMContentLoaded', function() {
            const settingsSubmenu = document.getElementById('settings-submenu');
            const settingsArrow = document.getElementById('settings-arrow');
            if (settingsSubmenu && settingsSubmenu.style.display === 'block' && settingsArrow) {
                settingsArrow.style.transform = 'rotate(180deg)';
            }
        });
    </script>