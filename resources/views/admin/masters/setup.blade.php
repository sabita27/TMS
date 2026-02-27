@extends('layouts.backend.master')

@section('content')
<div class="setup-container" style="margin-bottom: 2rem;">
    <div style="margin-bottom: 2rem;">
        @php
            $displayType = $type;
            if($type == 'global') {
                if($section == 'logo') $displayType = 'System Logo';
                elseif($section == 'smtp') $displayType = 'SMTP Setting';
                else $displayType = 'Home Page Setting';
            }
            elseif($type == 'subcategory') $displayType = 'Sub-Category';
            elseif($type == 'priority') $displayType = 'Priority';
            elseif($type == 'status') $displayType = 'Ticket Status';
            else $displayType = ucfirst($type);
        @endphp
        <h1 style="font-size: 1.75rem; font-weight: 800; color: #0f172a; margin: 0; letter-spacing: -0.01em;">Manage {{ $displayType }}</h1>
        <nav style="display: flex; align-items: center; gap: 0.5rem; color: #64748b; font-size: 0.9rem; margin-top: 0.3rem;">
            <a href="{{ route('admin.dashboard') }}" style="color: #6366f1; text-decoration: none; font-weight: 500;">Home</a>
            <i class="fas fa-chevron-right" style="font-size: 0.7rem; opacity: 0.5;"></i>
            <span>{{ $displayType }}</span>
        </nav>
    </div>

    <div style="display: grid; grid-template-columns: 280px 1fr; gap: 2rem; align-items: stretch; min-height: 600px;">
        <!-- Internal Sidebar -->
        <div style="background: white; border-radius: 1.25rem; padding: 1.5rem; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.04); border: 1px solid #f1f5f9; height: 100%;">
            <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                <!-- 1. Global Setting Dropdown -->
                <div style="margin-bottom: 0.25rem;">
                    <a href="javascript:void(0)" onclick="toggleGlobalMenu()" class="setup-tab {{ $type == 'global' ? 'active' : '' }}" style="display: flex; justify-content: space-between; align-items: center;">
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <i class="fas fa-globe"></i>
                            <span>Global Setting</span>
                        </div>
                        <i class="fas fa-chevron-down" style="font-size: 0.7rem; transition: transform 0.3s;" id="global-chevron"></i>
                    </a>
                    <div id="global-submenu" style="display: {{ $type == 'global' ? 'flex' : 'none' }}; flex-direction: column; gap: 0.25rem; padding: 0.5rem 0 0.5rem 2.25rem;">
                        <a href="{{ route('admin.setup', ['type' => 'global', 'section' => 'identity']) }}" class="submenu-link {{ ($type == 'global' && $section == 'identity') ? 'active' : '' }}">Home Page Setting</a>
                        <a href="{{ route('admin.setup', ['type' => 'global', 'section' => 'logo']) }}" class="submenu-link {{ ($type == 'global' && $section == 'logo') ? 'active' : '' }}">Logo & Favicon</a>
                        <a href="{{ route('admin.setup', ['type' => 'global', 'section' => 'smtp']) }}" class="submenu-link {{ ($type == 'global' && $section == 'smtp') ? 'active' : '' }}">SMTP Setting</a>
                    </div>
                </div>

                <!-- 3. Designation -->
                <a href="{{ route('admin.setup', ['type' => 'designation']) }}" class="setup-tab {{ $type == 'designation' ? 'active' : '' }}">
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <i class="fas fa-id-badge"></i>
                        <span>Designation</span>
                    </div>
                </a>
                <!-- 4. Position -->
                <a href="{{ route('admin.setup', ['type' => 'position']) }}" class="setup-tab {{ $type == 'position' ? 'active' : '' }}">
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <i class="fas fa-chair"></i>
                        <span>Position</span>
                    </div>
                </a>
                <!-- 5. Category -->
                <a href="{{ route('admin.setup', ['type' => 'category']) }}" class="setup-tab {{ $type == 'category' ? 'active' : '' }}">
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <i class="fas fa-tags"></i>
                        <span>Category</span>
                    </div>
                </a>
                <!-- 6. Sub-Category -->
                <a href="{{ route('admin.setup', ['type' => 'subcategory']) }}" class="setup-tab {{ $type == 'subcategory' ? 'active' : '' }}">
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <i class="fas fa-layer-group"></i>
                        <span>Sub-Category</span>
                    </div>
                </a>
                <!-- 7. Priority -->
                <a href="{{ route('admin.setup', ['type' => 'priority']) }}" class="setup-tab {{ $type == 'priority' ? 'active' : '' }}">
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <i class="fas fa-flag"></i>
                        <span>Priority</span>
                    </div>
                </a>
                <!-- 8. Ticket Status -->
                <a href="{{ route('admin.setup', ['type' => 'status']) }}" class="setup-tab {{ $type == 'status' ? 'active' : '' }}">
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <i class="fas fa-info-circle"></i>
                        <span>Ticket Status</span>
                    </div>
                </a>
            </div>
        </div>

        <!-- Content Area (Table Design) -->
        <div style="background: white; border-radius: 1.25rem; padding: 2rem; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.04); border: 1px solid #f1f5f9; min-height: 500px; height: 100%;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; padding: 0 1.5rem;">
                <h2 style="margin: 0; font-size: 1.5rem; font-weight: 700; color: #1e293b;">
                    @if($type == 'global') Global Settings @elseif($type == 'role') Roles @elseif($type == 'designation') Designations @elseif($type == 'position') Positions @elseif($type == 'category') Product Categories @elseif($type == 'subcategory') Sub-Categories @elseif($type == 'priority') Ticket Priorities @else Ticket Statuses @endif
                </h2>
                
                @if($type == 'global')
                    <div style="display: flex; align-items: center; gap: 0.5rem; background: #f1f5f9; padding: 0.3rem 0.8rem; border-radius: 2rem; font-size: 0.8rem; color: #64748b; font-weight: 600;">
                        <i class="fas fa-lock"></i> System Secure
                    </div>
                @elseif($type == 'role')
                    <button onclick="openModal('roleModal')" class="btn btn-primary" style="background: #474affff; border: none; padding: 0.6rem 1.2rem; border-radius: 0.4rem; font-weight: 600; display: flex; align-items: center; gap: 0.5rem; font-size: 0.9rem;">
                        <i class="fas fa-plus"></i> Add Role
                    </button>
                @elseif($type == 'designation')
                    <button onclick="openModal('designationModal')" class="btn btn-primary" style="background: #474affff; border: none; padding: 0.6rem 1.2rem; border-radius: 0.4rem; font-weight: 600; display: flex; align-items: center; gap: 0.5rem; font-size: 0.9rem;">
                        <i class="fas fa-plus"></i> Add Designation
                    </button>
                @elseif($type == 'position')
                    <button onclick="openModal('positionModal')" class="btn btn-primary" style="background: #474affff; border: none; padding: 0.6rem 1.2rem; border-radius: 0.4rem; font-weight: 600; display: flex; align-items: center; gap: 0.5rem; font-size: 0.9rem;">
                        <i class="fas fa-plus"></i> Add Position
                    </button>
                @elseif($type == 'category')
                    <button onclick="openModal('categoryModal')" class="btn btn-primary" style="background: #474affff; border: none; padding: 0.6rem 1.2rem; border-radius: 0.4rem; font-weight: 600; display: flex; align-items: center; gap: 0.5rem; font-size: 0.9rem;">
                        <i class="fas fa-plus"></i> Add Category
                    </button>
                @elseif($type == 'subcategory')
                    <button onclick="openModal('subcategoryModal')" class="btn btn-primary" style="background: #474affff; border: none; padding: 0.6rem 1.2rem; border-radius: 0.4rem; font-weight: 600; display: flex; align-items: center; gap: 0.5rem; font-size: 0.9rem;">
                        <i class="fas fa-plus"></i> Add Sub-Category
                    </button>
                @elseif($type == 'priority')
                    <button onclick="openModal('priorityModal')" class="btn btn-primary" style="background: #6366f1; border: none; padding: 0.6rem 1.2rem; border-radius: 0.4rem; font-weight: 600; display: flex; align-items: center; gap: 0.5rem; font-size: 0.9rem;">
                        <i class="fas fa-plus"></i> Add Priority
                    </button>
                @elseif($type == 'status')
                    <button onclick="openModal('statusModal')" class="btn btn-primary" style="background: #6366f1; border: none; padding: 0.6rem 1.2rem; border-radius: 0.4rem; font-weight: 600; display: flex; align-items: center; gap: 0.5rem; font-size: 0.9rem;">
                        <i class="fas fa-plus"></i> Add Status
                    </button>
                @endif
            </div>

            @if($type != 'global')
            <div style="overflow-x: auto;">
                <table id="setupTable" style="width: 100%; border-collapse: separate; border-spacing: 0;">
                    <thead>
                        <tr style="background: #f8fafc;">
                            <th style="padding: 1rem 1.5rem; text-align: left; font-size: 0.85rem; font-weight: 700; color: #64748b; border-bottom: 2px solid #f1f5f9;">@if($type == 'category' || $type == 'subcategory') Category Name @else Name @endif</th>
                            @if($type == 'subcategory')
                                <th style="padding: 1rem 1.5rem; text-align: left; font-size: 0.85rem; font-weight: 700; color: #64748b; border-bottom: 2px solid #f1f5f9;">Parent</th>
                            @endif
                            @if($type == 'priority' || $type == 'status')
                                <th style="padding: 1rem 1.5rem; text-align: left; font-size: 0.85rem; font-weight: 700; color: #64748b; border-bottom: 2px solid #f1f5f9;">Color</th>
                            @endif
                            <th style="padding: 1rem 1.5rem; text-align: left; font-size: 0.85rem; font-weight: 700; color: #64748b; border-bottom: 2px solid #f1f5f9;">Status</th>
                            <th style="padding: 1rem 1.5rem; text-align: center; font-size: 0.85rem; font-weight: 700; color: #64748b; border-bottom: 2px solid #f1f5f9;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
            @endif

            @if($type == 'global')
                <form action="{{ route('admin.setup.settings') }}" method="POST" enctype="multipart/form-data" style="padding: 0;">
                    @csrf
                                        
                                        @if($section == 'identity')
                                            <!-- Home Page Identity Settings -->
                                            <div style="padding: 0;">
                                                <h4 style="margin: 0 0 2rem; padding-bottom: 1rem; border-bottom: 1px solid #f1f5f9; font-size: 1.1rem; color: #1e293b; display: flex; align-items: center; gap: 0.75rem;">
                                                    <i class="fas fa-fingerprint" style="color: #6366f1; background: #eef2ff; padding: 0.5rem; border-radius: 0.5rem;"></i> Identity Profile
                                                </h4>
                                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                                                    <div class="form-group">
                                                        <label class="form-label">System Title</label>
                                                        <input type="text" name="system_name" class="form-control" value="{{ $data['settings']['system_name'] ?? 'TMS PRO' }}" placeholder="e.g. Enterprise TMS">
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="form-label">Copyright Footer</label>
                                                        <input type="text" name="system_footer" class="form-control" value="{{ $data['settings']['system_footer'] ?? '© 2026 Professional TMS' }}" placeholder="e.g. © 2026 Admin Panel">
                                                    </div>
                                                    <div class="form-group" style="grid-column: span 2;">
                                                        <label class="form-label">Home Page Tagline</label>
                                                        <input type="text" name="home_tagline" class="form-control" value="{{ $data['settings']['home_tagline'] ?? 'Smart Ticket Management System' }}">
                                                    </div>
                                                </div>
                                            </div>
                                        @elseif($section == 'logo')
                                            <!-- Logo & Favicon Branding Settings -->
                                            <div style="padding: 0;">
                                                <h4 style="margin: 0 0 2rem; padding-bottom: 1rem; border-bottom: 1px solid #f1f5f9; font-size: 1.1rem; color: #1e293b; display: flex; align-items: center; gap: 0.75rem;">
                                                    <i class="fas fa-palette" style="color: #6366f1; background: #eef2ff; padding: 0.5rem; border-radius: 0.5rem;"></i> Visual Branding
                                                </h4>
                                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                                                    <div style="text-align: center; border: 2px dashed #e2e8f0; padding: 2.5rem; border-radius: 1rem; background: #f8fafc;">
                                                        <label style="cursor: pointer;">
                                                            @if(isset($data['settings']['system_logo']))
                                                                <img src="{{ asset('storage/' . $data['settings']['system_logo']) }}" style="max-height: 80px; margin-bottom: 1rem; display: block; margin-left: auto; margin-right: auto;">
                                                            @else
                                                                <i class="fas fa-cloud-upload-alt" style="font-size: 2.5rem; color: #cbd5e1; margin-bottom: 1rem; display: block;"></i>
                                                            @endif
                                                            <span style="font-weight: 700; color: #64748b; font-size: 0.9rem;">Upload Main Logo</span>
                                                            <input type="file" name="logo" style="display: none;">
                                                        </label>
                                                    </div>
                                                    <div style="text-align: center; border: 2px dashed #e2e8f0; padding: 2rem; border-radius: 1rem; background: white;">
                                                        <label style="cursor: pointer;">
                                                            @if(isset($data['settings']['system_favicon']))
                                                                <img src="{{ asset('storage/' . $data['settings']['system_favicon']) }}" style="height: 48px; width: 48px; margin-bottom: 1rem; display: block; margin-left: auto; margin-right: auto;">
                                                            @else
                                                                <i class="fas fa-image" style="font-size: 2.5rem; color: #cbd5e1; margin-bottom: 1rem; display: block;"></i>
                                                            @endif
                                                            <span style="font-weight: 700; color: #64748b; font-size: 0.9rem;">Upload Browser Favicon</span>
                                                            <input type="file" name="favicon" style="display: none;">
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        @elseif($section == 'smtp')
                                            <!-- Professional SMTP Settings -->
                                            <div style="padding: 0;">
                                                <h4 style="margin: 0 0 2rem; padding-bottom: 1rem; border-bottom: 1px solid #f1f5f9; font-size: 1.1rem; color: #1e293b; display: flex; align-items: center; gap: 0.75rem;">
                                                    <i class="fas fa-envelope-open-text" style="color: #6366f1; background: #eef2ff; padding: 0.5rem; border-radius: 0.5rem;"></i> Mail Configuration (SMTP)
                                                </h4>

                                                {{-- Gmail Help Notice --}}
                                                <div style="background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 0.75rem; padding: 1rem 1.25rem; margin-bottom: 1.5rem; display: flex; gap: 0.75rem; align-items: flex-start;">
                                                    <i class="fas fa-info-circle" style="color: #3b82f6; margin-top: 2px; flex-shrink: 0;"></i>
                                                    <div style="font-size: 0.85rem; color: #1d4ed8; line-height: 1.6;">
                                                        <strong>Gmail Users:</strong> Use <code style="background:#dbeafe; padding:1px 5px; border-radius:3px;">smtp.gmail.com</code> as host, port <code style="background:#dbeafe; padding:1px 5px; border-radius:3px;">587</code>, encryption <code style="background:#dbeafe; padding:1px 5px; border-radius:3px;">TLS</code>.
                                                        The <strong>Username</strong> must be your <strong>Gmail address</strong> and the <strong>Password</strong> must be a <strong>16-digit App Password</strong>
                                                        (not your regular password) — generate one at <a href="https://myaccount.google.com/apppasswords" target="_blank" style="color:#2563eb;">myaccount.google.com/apppasswords</a>.
                                                    </div>
                                                </div>

                                                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem;">
                                                    <div class="form-group">
                                                        <label class="form-label">Mail Host</label>
                                                        <input type="text" name="mail_host" class="form-control" value="{{ $data['settings']['mail_host'] ?? '' }}" placeholder="smtp.gmail.com">
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="form-label">Mail Port</label>
                                                        <input type="text" name="mail_port" class="form-control" value="{{ $data['settings']['mail_port'] ?? '' }}" placeholder="587">
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="form-label">Mail Encryption</label>
                                                        <select name="mail_encryption" class="form-control">
                                                            <option value="tls" {{ ($data['settings']['mail_encryption'] ?? 'tls') == 'tls' ? 'selected' : '' }}>TLS (Recommended)</option>
                                                            <option value="ssl" {{ ($data['settings']['mail_encryption'] ?? '') == 'ssl' ? 'selected' : '' }}>SSL</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="form-label">Username <small style="color:#94a3b8;">(Gmail address)</small></label>
                                                        <input type="email" name="mail_username" class="form-control" value="{{ $data['settings']['mail_username'] ?? '' }}" placeholder="yourname@gmail.com">
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="form-label">App Password <small style="color:#94a3b8;">(16 chars)</small></label>
                                                        <input type="password" name="mail_password" class="form-control" value="{{ $data['settings']['mail_password'] ?? '' }}" placeholder="xxxx xxxx xxxx xxxx" autocomplete="new-password">
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="form-label">From Address <small style="color:#94a3b8;">(sender email)</small></label>
                                                        <input type="email" name="mail_from_address" class="form-control" value="{{ $data['settings']['mail_from_address'] ?? '' }}" placeholder="noreply@yourcompany.com">
                                                    </div>
                                                    <div class="form-group" style="grid-column: span 3;">
                                                        <label class="form-label">From Name <small style="color:#94a3b8;">(shown to recipients)</small></label>
                                                        <input type="text" name="mail_from_name" class="form-control" value="{{ $data['settings']['mail_from_name'] ?? config('app.name') }}" placeholder="TMS PROD Support">
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        
                                        <div style="margin-top: 1.5rem; display: flex; justify-content: flex-end;">
                                            <button type="submit" class="btn btn-primary" style="background: #10b981; border: none; padding: 0.75rem 2rem; border-radius: 0.5rem; font-weight: 700; box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.2); transition: transform 0.2s;">
                                                <i class="fas fa-save" style="margin-right: 0.5rem;"></i> Update {{ ucfirst($section) }}
                                            </button>
                                        </div>
                                    </form>
                        @elseif($type == 'role')
                            @foreach($data['roles'] as $role)
                            <tr style="transition: background 0.2s;">
                                <td style="padding: 1.25rem 1.5rem; font-weight: 500; color: #334155; border-bottom: 1px solid #f1f5f9;">{{ $role->name }}</td>
                                <td style="padding: 1.25rem 1.5rem; border-bottom: 1px solid #f1f5f9;"><span style="background: #dcfce7; color: #166534; padding: 0.35rem 0.75rem; border-radius: 0.4rem; font-size: 0.75rem; font-weight: 700; letter-spacing: 0.05em;">ACTIVE</span></td>
                                <td style="padding: 1.25rem 1.5rem; border-bottom: 1px solid #f1f5f9; text-align: center;">
                                    <div style="display: flex; gap: 0.5rem; justify-content: center;">
                                        <button onclick="editRole({{ $role->id }}, '{{ $role->name }}')" style="background: #6366f1; color: white; border: none; width: 34px; height: 34px; border-radius: 0.4rem; cursor: pointer; display: flex; align-items: center; justify-content: center;"><i class="fas fa-edit"></i></button>
                                        <form action="{{ route('admin.roles.delete', $role->id) }}" method="POST" onsubmit="return confirm('Delete role?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" style="background: #ef4444; color: white; border: none; width: 34px; height: 34px; border-radius: 0.4rem; cursor: pointer; display: flex; align-items: center; justify-content: center;"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        @elseif($type == 'designation')
                            @foreach($data['designations'] as $des)
                            <tr style="transition: background 0.2s;">
                                <td style="padding: 1.25rem 1.5rem; font-weight: 500; color: #334155; border-bottom: 1px solid #f1f5f9;">{{ $des->name }}</td>
                                <td style="padding: 1.25rem 1.5rem; border-bottom: 1px solid #f1f5f9;"><span style="background: #dcfce7; color: #166534; padding: 0.35rem 0.75rem; border-radius: 0.4rem; font-size: 0.75rem; font-weight: 700; letter-spacing: 0.05em;">ACTIVE</span></td>
                                <td style="padding: 1.25rem 1.5rem; border-bottom: 1px solid #f1f5f9; text-align: center;">
                                    <div style="display: flex; gap: 0.5rem; justify-content: center;">
                                        <button onclick="editDesignation({{ $des->id }}, '{{ $des->name }}')" style="background: #6366f1; color: white; border: none; width: 34px; height: 34px; border-radius: 0.4rem; cursor: pointer; display: flex; align-items: center; justify-content: center;"><i class="fas fa-edit"></i></button>
                                        <form action="{{ route('admin.designations.delete', $des->id) }}" method="POST" onsubmit="return confirm('Delete designation?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" style="background: #ef4444; color: white; border: none; width: 34px; height: 34px; border-radius: 0.4rem; cursor: pointer; display: flex; align-items: center; justify-content: center;"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        @elseif($type == 'position')
                            @foreach($data['positions'] as $pos)
                            <tr style="transition: background 0.2s;">
                                <td style="padding: 1.25rem 1.5rem; font-weight: 500; color: #334155; border-bottom: 1px solid #f1f5f9;">{{ $pos->name }}</td>
                                <td style="padding: 1.25rem 1.5rem; border-bottom: 1px solid #f1f5f9;"><span style="background: #dcfce7; color: #166534; padding: 0.35rem 0.75rem; border-radius: 0.4rem; font-size: 0.75rem; font-weight: 700; letter-spacing: 0.05em;">ACTIVE</span></td>
                                <td style="padding: 1.25rem 1.5rem; border-bottom: 1px solid #f1f5f9; text-align: center;">
                                    <div style="display: flex; gap: 0.5rem; justify-content: center;">
                                        <button onclick="editPosition({{ $pos->id }}, '{{ $pos->name }}')" style="background: #6366f1; color: white; border: none; width: 34px; height: 34px; border-radius: 0.4rem; cursor: pointer; display: flex; align-items: center; justify-content: center;"><i class="fas fa-edit"></i></button>
                                        <form action="{{ route('admin.positions.delete', $pos->id) }}" method="POST" onsubmit="return confirm('Delete position?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" style="background: #ef4444; color: white; border: none; width: 34px; height: 34px; border-radius: 0.4rem; cursor: pointer; display: flex; align-items: center; justify-content: center;"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        @elseif($type == 'category')
                            @foreach($data['categories'] as $cat)
                            <tr style="transition: background 0.2s;">
                                <td style="padding: 1.25rem 1.5rem; font-weight: 500; color: #334155; border-bottom: 1px solid #f1f5f9;">{{ $cat->name }}</td>
                                <td style="padding: 1.25rem 1.5rem; border-bottom: 1px solid #f1f5f9;"><span style="background: #dcfce7; color: #166534; padding: 0.35rem 0.75rem; border-radius: 0.4rem; font-size: 0.75rem; font-weight: 700; letter-spacing: 0.05em;">ACTIVE</span></td>
                                <td style="padding: 1.25rem 1.5rem; border-bottom: 1px solid #f1f5f9; text-align: center;">
                                    <div style="display: flex; gap: 0.5rem; justify-content: center;">
                                        <button onclick="editCategory({{ $cat->id }}, '{{ $cat->name }}')" style="background: #6366f1; color: white; border: none; width: 34px; height: 34px; border-radius: 0.4rem; cursor: pointer; display: flex; align-items: center; justify-content: center;"><i class="fas fa-edit"></i></button>
                                        <form action="{{ route('admin.categories.delete', $cat->id) }}" method="POST" onsubmit="return confirm('Delete category?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" style="background: #ef4444; color: white; border: none; width: 34px; height: 34px; border-radius: 0.4rem; cursor: pointer; display: flex; align-items: center; justify-content: center;"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        @elseif($type == 'subcategory')
                            @foreach($data['subcategories'] as $sub)
                            <tr>
                                <td style="padding: 1.25rem 1.5rem; font-weight: 500; color: #334155; border-bottom: 1px solid #f1f5f9;">{{ $sub->name }}</td>
                                <td style="padding: 1.25rem 1.5rem; border-bottom: 1px solid #f1f5f9;"><span style="color: #64748b;">{{ $sub->category->name }}</span></td>
                                <td style="padding: 1.25rem 1.5rem; border-bottom: 1px solid #f1f5f9;"><span style="background: #dcfce7; color: #166534; padding: 0.35rem 0.75rem; border-radius: 0.4rem; font-size: 0.75rem; font-weight: 700;">ACTIVE</span></td>
                                <td style="padding: 1.25rem 1.5rem; border-bottom: 1px solid #f1f5f9; text-align: center;">
                                    <div style="display: flex; gap: 0.5rem; justify-content: center;">
                                        <button onclick="editSubCategory({{ $sub->id }}, '{{ $sub->name }}', {{ $sub->category_id }})" style="background: #6366f1; color: white; border: none; width: 34px; height: 34px; border-radius: 0.4rem; cursor: pointer; display: flex; align-items: center; justify-content: center;"><i class="fas fa-edit"></i></button>
                                        <form action="{{ route('admin.subcategories.delete', $sub->id) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button type="submit" style="background: #ef4444; color: white; border: none; width: 34px; height: 34px; border-radius: 0.4rem; cursor: pointer; display: flex; align-items: center; justify-content: center;"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        @elseif($type == 'priority')
                            @foreach($data['priorities'] as $pri)
                            <tr>
                                <td style="padding: 1.25rem 1.5rem; font-weight: 500; color: #334155; border-bottom: 1px solid #f1f5f9;">{{ $pri->name }}</td>
                                <td style="padding: 1.25rem 1.5rem; border-bottom: 1px solid #f1f5f9;"><div style="width: 24px; height: 24px; border-radius: 6px; background: {{ $pri->color }}; border: 1px solid #e2e8f0; box-shadow: 0 0 8px {{ $pri->color }}33;"></div></td>
                                <td style="padding: 1.25rem 1.5rem; border-bottom: 1px solid #f1f5f9;"><span style="background: #dcfce7; color: #166534; padding: 0.35rem 0.75rem; border-radius: 0.4rem; font-size: 0.75rem; font-weight: 700;">ACTIVE</span></td>
                                <td style="padding: 1.25rem 1.5rem; border-bottom: 1px solid #f1f5f9; text-align: center;">
                                    <div style="display: flex; gap: 0.5rem; justify-content: center;">
                                        <button onclick="editPriority({{ $pri->id }}, '{{ $pri->name }}', '{{ $pri->color }}')" style="background: #6366f1; color: white; border: none; width: 34px; height: 34px; border-radius: 0.4rem; cursor: pointer; display: flex; align-items: center; justify-content: center;"><i class="fas fa-edit"></i></button>
                                        <form action="{{ route('admin.ticket_priorities.delete', $pri->id) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button type="submit" style="background: #ef4444; color: white; border: none; width: 34px; height: 34px; border-radius: 0.4rem; cursor: pointer; display: flex; align-items: center; justify-content: center;"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        @elseif($type == 'status')
                            @foreach($data['statuses'] as $status)
                            <tr>
                                <td style="padding: 1.25rem 1.5rem; font-weight: 500; color: #334155; border-bottom: 1px solid #f1f5f9;">{{ $status->name }}</td>
                                <td style="padding: 1.25rem 1.5rem; border-bottom: 1px solid #f1f5f9;"><div style="width: 24px; height: 24px; border-radius: 50%; background: {{ $status->color }}; border: 1px solid #e2e8f0; box-shadow: 0 0 8px {{ $status->color }}33;"></div></td>
                                <td style="padding: 1.25rem 1.5rem; border-bottom: 1px solid #f1f5f9;"><span style="background: #dcfce7; color: #166534; padding: 0.35rem 0.75rem; border-radius: 0.4rem; font-size: 0.75rem; font-weight: 700;">ACTIVE</span></td>
                                <td style="padding: 1.25rem 1.5rem; border-bottom: 1px solid #f1f5f9; text-align: center;">
                                    <div style="display: flex; gap: 0.5rem; justify-content: center;">
                                        <button onclick="editStatus({{ $status->id }}, '{{ $status->name }}', '{{ $status->color }}')" style="background: #6366f1; color: white; border: none; width: 34px; height: 34px; border-radius: 0.4rem; cursor: pointer; display: flex; align-items: center; justify-content: center;"><i class="fas fa-edit"></i></button>
                                        <form action="{{ route('admin.ticket_statuses.delete', $status->id) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button type="submit" style="background: #ef4444; color: white; border: none; width: 34px; height: 34px; border-radius: 0.4rem; cursor: pointer; display: flex; align-items: center; justify-content: center;"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        @endif
                @if($type != 'global')
                    </tbody>
                </table>
            </div>
            @else
                </form>
            @endif
        </div>
    </div>
</div>
    </div>
</div>

    </div>
</div>

<!-- All Modals -->
<div id="roleModal" class="modal-overlay">
    <div class="modal-content">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h3 id="roleModalTitle" class="modal-title" style="margin: 0;">Add Role</h3>
            <button type="button" onclick="closeModal('roleModal')" style="background: none; border: none; font-size: 1.25rem; color: #94a3b8; cursor: pointer;">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="roleForm" action="{{ route('admin.roles.store') }}" method="POST">
            @csrf
            <input type="hidden" name="_method" id="roleMethod" value="POST">
            <div class="form-group">
                <label class="form-label">Role Name</label>
                <input type="text" name="name" id="roleName" class="form-control" required placeholder="Manager, Staff, etc...">
            </div>
            <div class="modal-actions">
                <button type="submit" class="btn-save">Save changes</button>
            </div>
        </form>
    </div>
</div>

<div id="designationModal" class="modal-overlay">
    <div class="modal-content">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h3 id="designationModalTitle" class="modal-title" style="margin: 0;">Add Designation</h3>
            <button type="button" onclick="closeModal('designationModal')" style="background: none; border: none; font-size: 1.25rem; color: #94a3b8; cursor: pointer;">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="designationForm" action="{{ route('admin.designations.store') }}" method="POST">
            @csrf
            <input type="hidden" name="_method" id="designationMethod" value="POST">
            <div class="form-group">
                <label class="form-label">Designation Name</label>
                <input type="text" name="name" id="designationName" class="form-control" required placeholder="Senior Developer, HR Manager, etc...">
            </div>
            <div class="modal-actions">
                <button type="submit" class="btn-save">Save changes</button>
            </div>
        </form>
    </div>
</div>

<div id="positionModal" class="modal-overlay">
    <div class="modal-content">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h3 id="positionModalTitle" class="modal-title" style="margin: 0;">Add Position</h3>
            <button type="button" onclick="closeModal('positionModal')" style="background: none; border: none; font-size: 1.25rem; color: #94a3b8; cursor: pointer;">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="positionForm" action="{{ route('admin.positions.store') }}" method="POST">
            @csrf
            <input type="hidden" name="_method" id="positionMethod" value="POST">
            <div class="form-group">
                <label class="form-label">Position Name</label>
                <input type="text" name="name" id="positionName" class="form-control" required placeholder="Floor 1 - Desk 4, etc...">
            </div>
            <div class="modal-actions">
                <button type="submit" class="btn-save">Save changes</button>
            </div>
        </form>
    </div>
</div>

<div id="categoryModal" class="modal-overlay">
    <div class="modal-content">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h3 id="categoryModalTitle" class="modal-title" style="margin: 0;">Add Category</h3>
            <button type="button" onclick="closeModal('categoryModal')" style="background: none; border: none; font-size: 1.25rem; color: #94a3b8; cursor: pointer;">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="categoryForm" action="{{ route('admin.categories.store') }}" method="POST">
            @csrf
            <input type="hidden" name="_method" id="categoryMethod" value="POST">
            <div class="form-group">
                <label class="form-label">Name</label>
                <input type="text" name="name" id="categoryName" class="form-control" required placeholder="Name...">
            </div>
            <div class="modal-actions">
                <button type="submit" class="btn-save">Save changes</button>
            </div>
        </form>
    </div>
</div>

<div id="subcategoryModal" class="modal-overlay">
    <div class="modal-content">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h3 id="subcategoryModalTitle" class="modal-title" style="margin: 0;">Add Sub-Category</h3>
            <button type="button" onclick="closeModal('subcategoryModal')" style="background: none; border: none; font-size: 1.25rem; color: #94a3b8; cursor: pointer;">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="subcategoryForm" action="{{ route('admin.subcategories.store') }}" method="POST">
            @csrf
            <input type="hidden" name="_method" id="subcategoryMethod" value="POST">
            <div class="form-group">
                <label class="form-label">Parent Category</label>
                <select name="category_id" id="subcategoryParent" class="form-control" required>
                    <option value="">Select Category</option>
                    @if(isset($data['categories']))
                        @foreach($data['categories'] as $pcat)
                            <option value="{{ $pcat->id }}">{{ $pcat->name }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="form-group" style="margin-top: 1rem;">
                <label class="form-label">Sub-Category Name</label>
                <input type="text" name="name" id="subcategoryName" class="form-control" required placeholder="Name...">
            </div>
            <div class="modal-actions">
                <button type="submit" class="btn-save">Save changes</button>
            </div>
        </form>
    </div>
</div>

<div id="priorityModal" class="modal-overlay">
    <div class="modal-content">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h3 id="priorityModalTitle" class="modal-title" style="margin: 0;">Add Priority</h3>
            <button type="button" onclick="closeModal('priorityModal')" style="background: none; border: none; font-size: 1.25rem; color: #94a3b8; cursor: pointer;">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="priorityForm" method="POST">
            @csrf
            <input type="hidden" name="_method" id="priorityMethod" value="POST">
            <div class="form-group">
                <label class="form-label">Name</label>
                <input type="text" name="name" id="priorityName" class="form-control" required placeholder="Name...">
            </div>
            <div class="form-group" style="margin-top: 1rem;">
                <label class="form-label">Color</label>
                <input type="color" name="color" id="priorityColor" class="form-control" style="height: 45px;">
            </div>
            <div class="modal-actions">
                <button type="submit" class="btn-save">Save changes</button>
            </div>
        </form>
    </div>
</div>

<div id="statusModal" class="modal-overlay">
    <div class="modal-content">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h3 id="statusModalTitle" class="modal-title" style="margin: 0;">Add Status</h3>
            <button type="button" onclick="closeModal('statusModal')" style="background: none; border: none; font-size: 1.25rem; color: #94a3b8; cursor: pointer;">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="statusForm" method="POST">
            @csrf
            <input type="hidden" name="_method" id="statusMethod" value="POST">
            <div class="form-group">
                <label class="form-label">Name</label>
                <input type="text" name="name" id="statusName" class="form-control" required placeholder="Name...">
            </div>
            <div class="form-group" style="margin-top: 1rem;">
                <label class="form-label">Color</label>
                <input type="color" name="color" id="statusColor" class="form-control" style="height: 45px;">
            </div>
            <div class="modal-actions">
                <button type="submit" class="btn-save">Save changes</button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
    function openModal(id) { document.getElementById(id).style.display = 'flex'; }
    function closeModal(id) { document.getElementById(id).style.display = 'none'; }

    function editRole(id, name) {
        document.getElementById('roleModalTitle').innerText = 'Edit Role';
        document.getElementById('roleName').value = name;
        document.getElementById('roleForm').action = `{{ url('admin/roles') }}/${id}`;
        document.getElementById('roleMethod').value = 'PUT';
        openModal('roleModal');
    }

    function editDesignation(id, name) {
        document.getElementById('designationModalTitle').innerText = 'Edit Designation';
        document.getElementById('designationName').value = name;
        document.getElementById('designationForm').action = `{{ url('admin/designations') }}/${id}`;
        document.getElementById('designationMethod').value = 'PUT';
        openModal('designationModal');
    }

    function editPosition(id, name) {
        document.getElementById('positionModalTitle').innerText = 'Edit Position';
        document.getElementById('positionName').value = name;
        document.getElementById('positionForm').action = `{{ url('admin/positions') }}/${id}`;
        document.getElementById('positionMethod').value = 'PUT';
        openModal('positionModal');
    }

    function editCategory(id, name) {
        document.getElementById('categoryModalTitle').innerText = 'Edit Category';
        document.getElementById('categoryName').value = name;
        document.getElementById('categoryForm').action = `{{ url('admin/categories') }}/${id}`;
        document.getElementById('categoryMethod').value = 'PUT';
        openModal('categoryModal');
    }

    function editSubCategory(id, name, parentId) {
        document.getElementById('subcategoryModalTitle').innerText = 'Edit Sub-Category';
        document.getElementById('subcategoryName').value = name;
        document.getElementById('subcategoryParent').value = parentId;
        document.getElementById('subcategoryForm').action = `{{ url('admin/subcategories') }}/${id}`;
        document.getElementById('subcategoryMethod').value = 'PUT';
        openModal('subcategoryModal');
    }

    function editPriority(id, name, color) {
        document.getElementById('priorityModalTitle').innerText = 'Edit Priority';
        document.getElementById('priorityName').value = name;
        document.getElementById('priorityColor').value = color;
        document.getElementById('priorityForm').action = `{{ url('admin/ticket-priorities') }}/${id}`;
        document.getElementById('priorityMethod').value = 'PUT';
        openModal('priorityModal');
    }

    function editStatus(id, name, color) {
        document.getElementById('statusModalTitle').innerText = 'Edit Status';
        document.getElementById('statusName').value = name;
        document.getElementById('statusColor').value = color;
        document.getElementById('statusForm').action = `{{ url('admin/ticket-statuses') }}/${id}`;
        document.getElementById('statusMethod').value = 'PUT';
        openModal('statusModal');
    }
</script>

<style>
    .setup-tab {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 1.25rem;
        border-radius: 0.85rem;
        text-decoration: none;
        color: #64748b;
        font-weight: 600;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid transparent;
    }
    .setup-tab:hover {
        background: #f8fafc;
        color: #0f172a;
    }
    .setup-tab.active {
        background: #6366f1;
        color: white;
        box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.25);
    }
    .submenu-link {
        display: block;
        padding: 0.6rem 1rem;
        border-radius: 0.5rem;
        text-decoration: none;
        color: #64748b;
        font-size: 0.85rem;
        font-weight: 600;
        transition: all 0.2s;
    }
    .submenu-link:hover {
        background: #f1f5f9;
        color: #0f172a;
    }
    .submenu-link.active {
        color: #6366f1;
        background: #eef2ff;
    }
    .setup-tab i { font-size: 1.1rem; }
    
    /* Premium Card Styles */
    .premium-card {
        background: #f8fafc;
        border-radius: 1rem;
        padding: 1.25rem 1.5rem;
        display: flex;
        flex-direction: column;
        border: 1px solid #eef2f6;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    .premium-card:hover {
        transform: translateY(-4px);
        background: #ffffff;
        box-shadow: 0 12px 20px -5px rgba(0, 0, 0, 0.05);
        border-color: #e2e8f0;
    }
    .card-label {
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        color: #94a3b8;
        letter-spacing: 0.05em;
    }
    .card-value {
        font-size: 1.1rem;
        font-weight: 800;
        color: #1e293b;
    }
    .card-actions {
        display: flex;
        gap: 0.5rem;
    }
    .action-btn-pne {
        width: 34px;
        height: 34px;
        border-radius: 0.75rem;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
        font-size: 0.9rem;
    }
    .action-btn-pne.edit { background: #eff6ff; color: #3b82f6; }
    .action-btn-pne.edit:hover { background: #3b82f6; color: white; }
    .action-btn-pne.delete { background: #fff1f2; color: #f43f5e; }
    .action-btn-pne.delete:hover { background: #f43f5e; color: white; }
    
    .modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(4px);
        z-index: 2000;
        align-items: center;
        justify-content: center;
    }
    .modal-content {
        background: white;
        width: 100%;
        max-width: 450px;
        padding: 2rem;
        border-radius: 1.5rem;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        animation: modalFadeIn 0.3s ease-out;
    }
    @keyframes modalFadeIn {
        from { opacity: 0; transform: scale(0.95) translateY(10px); }
        to { opacity: 1; transform: scale(1) translateY(0); }
    }
    .modal-title { font-size: 1.25rem; font-weight: 800; color: #0f172a; margin: 0; }
    .form-label { display: block; font-size: 0.875rem; font-weight: 700; color: #475569; margin-bottom: 0.5rem; }
    .form-control {
        width: 100%;
        padding: 0.75rem 1rem;
        border-radius: 0.75rem;
        border: 1px solid #e2e8f0;
        font-size: 0.95rem;
        color: #1e293b;
        transition: all 0.2s;
        box-sizing: border-box;
    }
    .form-control:focus { outline: none; border-color: #10b981; box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1); }
    .modal-actions { display: flex; gap: 1rem; margin-top: 2rem; }
    .btn-cancel { flex: 1; padding: 0.75rem; border-radius: 0.75rem; border: 1px solid #e2e8f0; background: #f8fafc; color: #64748b; font-weight: 700; cursor: pointer; }
    .btn-save { flex: 1; padding: 0.75rem; border-radius: 0.75rem; border: none; background: #10b981; color: white; font-weight: 700; cursor: pointer; box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.2); }
</style>

<script>
    function openModal(id) {
        document.getElementById(id).style.display = 'flex';
    }
    function closeModal(id) {
        document.getElementById(id).style.display = 'none';
    }
    function toggleGlobalMenu() {
        const menu = document.getElementById('global-submenu');
        const chevron = document.getElementById('global-chevron');
        if (menu.style.display === 'none') {
            menu.style.display = 'flex';
            chevron.style.transform = 'rotate(180deg)';
        } else {
            menu.style.display = 'none';
            chevron.style.transform = 'rotate(0deg)';
        }
    }

    $(document).ready(function() {
        if ($('#setupTable').length > 0) {
            $('#setupTable').DataTable({
                "pageLength": 10,
                "order": [],
                "dom": '<"top"Bf>rt<"bottom"ip><"clear">',
                "buttons": [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                "language": {
                    "search": "_INPUT_",
                    "searchPlaceholder": "Search..."
                }
            });
        }
    });
</script>
@endsection
