<aside id="app-sidebar" style="width: 260px; background: var(--sidebar-bg); color: var(--sidebar-text); display: flex; flex-direction: column; height: 100vh; position: fixed; left: 0; top: 0; bottom: 0; z-index: 1000; transition: transform 0.3s ease-in-out;">
    <div style="padding: 2rem 1.5rem; text-align: center; border-bottom: 1px solid #374151; display: flex; justify-content: space-between; align-items: center;">
        <h2 style="margin: 0; font-size: 1.5rem; color: #fff; letter-spacing: 1px;">TMS PRO</h2>
        <button class="close-sidebar-btn" onclick="document.getElementById('app-sidebar').classList.remove('active'); document.getElementById('mobile-overlay').style.display='none';" style="background: none; border: none; color: #9ca3af; font-size: 1.25rem; cursor: pointer; display: none;">
            <i class="fas fa-times"></i>
        </button>
    </div>
    
    <nav style="flex-grow: 1; padding: 1.5rem 0; overflow-y: auto;">
        @php $curr = Route::currentRouteName(); @endphp
        
        @if(optional(Auth::user()->role)->name == 'admin')
            <div class="nav-label">MAIN</div>
            <a href="{{ route('admin.dashboard') }}" class="nav-item-link {{ $curr == 'admin.dashboard' ? 'active' : '' }}">
                <i class="fas fa-th-large"></i> Dashboard
            </a>
            
            <!-- <div class="nav-label">MANAGEMENT</div> -->
            <div class="nav-label">MANAGEMENT</div>
            <a href="{{ route('admin.users') }}" class="nav-item-link {{ $curr == 'admin.users' ? 'active' : '' }}">
                <i class="fas fa-users-cog"></i> User Master
            </a>
            <a href="{{ route('admin.products') }}" class="nav-item-link {{ str_contains($curr, 'admin.products') ? 'active' : '' }}">
                <i class="fas fa-box-open"></i> Product Master
            </a>
            <a href="{{ route('admin.clients') }}" class="nav-item-link {{ str_contains($curr, 'admin.clients') ? 'active' : '' }}">
                <i class="fas fa-user-tie"></i> Client Master
            </a>
            <a href="{{ route('admin.projects') }}" class="nav-item-link {{ str_contains($curr, 'admin.projects') ? 'active' : '' }}">
                <i class="fas fa-project-diagram"></i> Project Master
            </a>
            <a href="{{ route('admin.services') }}" class="nav-item-link {{ str_contains($curr, 'admin.services') ? 'active' : '' }}">
                <i class="fas fa-concierge-bell"></i> Service Master
            </a>

            <div class="nav-label">SYSTEM</div>
            <a href="{{ route('admin.setup') }}" class="nav-item-link {{ str_contains($curr, 'admin.setup') ? 'active' : '' }}">
                <i class="fas fa-tools"></i> Setup Hub
            </a>

        @elseif(optional(Auth::user()->role)->name == 'user')
            <div class="nav-label">MAIN</div>
            <a href="{{ route('user.dashboard') }}" class="nav-item-link {{ $curr == 'user.dashboard' ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt"></i> My Dashboard
            </a>
            <a href="{{ route('user.profile') }}" class="nav-item-link {{ $curr == 'user.profile' ? 'active' : '' }}">
                <i class="fas fa-user-circle"></i> Profile Settings
            </a>
            
            <div class="nav-label">SUPPORT</div>
            <a href="{{ route('user.tickets.create') }}" class="nav-item-link {{ $curr == 'user.tickets.create' ? 'active' : '' }}">
                <i class="fas fa-plus-circle"></i> Raise Ticket
            </a>
            <a href="{{ route('user.tickets') }}" class="nav-item-link {{ $curr == 'user.tickets' ? 'active' : '' }}">
                <i class="fas fa-ticket-alt"></i> My Tickets
            </a>
            <a href="{{ route('user.products') }}" class="nav-item-link {{ $curr == 'user.products' ? 'active' : '' }}">
                <i class="fas fa-shopping-bag"></i> Browse Products
            </a>

        @elseif(optional(Auth::user()->role)->name == 'staff')
            <div class="nav-label">WORK DESK</div>
            <a href="{{ route('staff.dashboard') }}" class="nav-item-link {{ $curr == 'staff.dashboard' ? 'active' : '' }}">
                <i class="fas fa-clipboard-list"></i> Assigned Tickets
            </a>
            <a href="{{ route('staff.designation') }}" class="nav-item-link {{ $curr == 'staff.designation' ? 'active' : '' }}">
                <i class="fas fa-user-id"></i> My Designation
            </a>

        @elseif(optional(Auth::user()->role)->name == 'manager')
            <div class="nav-label">OPERATIONS</div>
            <a href="{{ route('manager.dashboard') }}" class="nav-item-link {{ $curr == 'manager.dashboard' ? 'active' : '' }}">
                <i class="fas fa-chart-line"></i> Dashboard
            </a>
            <a href="{{ route('manager.tickets') }}" class="nav-item-link {{ $curr == 'manager.tickets' ? 'active' : '' }}">
                <i class="fas fa-tasks"></i> All Tickets
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
<div id="mobile-overlay" onclick="document.getElementById('app-sidebar').classList.remove('active'); this.style.display='none';" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 900; display: none;"></div>

<style>
    .nav-label { font-size: 0.7rem; font-weight: 700; color: #4b5563; padding: 1.25rem 1.5rem 0.5rem; text-transform: uppercase; letter-spacing: 0.1em; }
    .nav-item-link { padding: 0.75rem 1.5rem; display: flex; align-items: center; gap: 0.75rem; color: var(--sidebar-text); text-decoration: none; font-size: 0.9rem; font-weight: 500; transition: 0.2s; border-left: 3px solid transparent; }
    .nav-item-link:hover { background: var(--sidebar-hover); color: white; }
    .nav-item-link.active { background: #1f2937; color: white; border-left-color: var(--primary-color); }
    .nav-item-link i { width: 20px; text-align: center; font-size: 1.1rem; opacity: 0.8; }
    .logout-btn { background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.2); color: #f87171; width: 100%; text-align: left; padding: 0.75rem 1rem; cursor: pointer; border-radius: 0.5rem; font-weight: 600; display: flex; align-items: center; gap: 0.75rem; transition: 0.2s; }
    .logout-btn:hover { background: #ef4444; color: white; }

    @media (max-width: 1024px) {
        #app-sidebar { 
            transform: translateX(-100%); 
            width: 280px !important; 
            box-shadow: 4px 0 10px rgba(0,0,0,0.1); 
            
            /* Position sidebar below the sticky header */
            top: 70px; 
            height: calc(100vh - 70px);
            z-index: 99; /* Below header (z-index 100) */
            box-shadow: 4px 0 10px rgba(0,0,0,0.1);
        }
        #app-sidebar.active { transform: translateX(0); }
        #app-sidebar.active + #mobile-overlay { display: block !important; }
        
        #mobile-overlay {
            top: 70px; /* Overlay starts below header */
            height: calc(100vh - 70px);
            /* z-index: 98; Below sidebar */
        }
        
        .close-sidebar-btn { display: none !important; }
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
