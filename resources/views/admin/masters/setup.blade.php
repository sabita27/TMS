@extends('layouts.backend.master')

@section('content')
<div class="setup-container">
    <div style="margin-bottom: 2rem;">
        @php
            $displayType = $type;
            if($type == 'global') $displayType = 'Global Setting';
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

    <div style="display: grid; grid-template-columns: 280px 1fr; gap: 2rem; align-items: start;">
        <!-- Internal Sidebar -->
        <div style="background: white; border-radius: 1.25rem; padding: 1.5rem; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.04); border: 1px solid #f1f5f9;">
            <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                <!-- 1. Global Setting -->
                <a href="{{ route('admin.setup', ['type' => 'global']) }}" class="setup-tab {{ $type == 'global' ? 'active' : '' }}">
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <i class="fas fa-globe"></i>
                        <span>Global Setting</span>
                    </div>
                </a>
                <!-- 2. Role -->
                <a href="{{ route('admin.setup', ['type' => 'role']) }}" class="setup-tab {{ $type == 'role' ? 'active' : '' }}">
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <i class="fas fa-user-shield"></i>
                        <span>Role</span>
                    </div>
                </a>
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
        <div style="background: white; border-radius: 0.75rem; padding: 2rem; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.04); border: 1px solid #f1f5f9; min-height: 500px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                <h2 style="margin: 0; font-size: 1.5rem; font-weight: 700; color: #1e293b;">
                    @if($type == 'global') Global Settings @elseif($type == 'role') Roles @elseif($type == 'designation') Designations @elseif($type == 'position') Positions @elseif($type == 'category') Product Categories @elseif($type == 'subcategory') Sub-Categories @elseif($type == 'priority') Ticket Priorities @else Ticket Statuses @endif
                </h2>
                
                @if($type == 'role')
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

            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: separate; border-spacing: 0;">
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
                        @if($type == 'global')
                            <tr>
                                <td colspan="4" style="padding: 3rem; text-align: center; color: #64748b;">
                                    <i class="fas fa-cogs" style="font-size: 3rem; opacity: 0.2; margin-bottom: 1rem; display: block;"></i>
                                    Global Settings Implementation Coming Soon
                                </td>
                            </tr>
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
                    </tbody>
                </table>
            </div>
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
        <h3 id="roleModalTitle" class="modal-title">Add Role</h3>
        <form id="roleForm" action="{{ route('admin.roles.store') }}" method="POST">
            @csrf
            <input type="hidden" name="_method" id="roleMethod" value="POST">
            <div class="form-group">
                <label class="form-label">Role Name</label>
                <input type="text" name="name" id="roleName" class="form-control" required placeholder="Manager, Staff, etc...">
            </div>
            <div class="modal-actions">
                <button type="button" onclick="closeModal('roleModal')" class="btn-cancel">Cancel</button>
                <button type="submit" class="btn-save">Save changes</button>
            </div>
        </form>
    </div>
</div>

<div id="designationModal" class="modal-overlay">
    <div class="modal-content">
        <h3 id="designationModalTitle" class="modal-title">Add Designation</h3>
        <form id="designationForm" action="{{ route('admin.designations.store') }}" method="POST">
            @csrf
            <input type="hidden" name="_method" id="designationMethod" value="POST">
            <div class="form-group">
                <label class="form-label">Designation Name</label>
                <input type="text" name="name" id="designationName" class="form-control" required placeholder="Senior Developer, HR Manager, etc...">
            </div>
            <div class="modal-actions">
                <button type="button" onclick="closeModal('designationModal')" class="btn-cancel">Cancel</button>
                <button type="submit" class="btn-save">Save changes</button>
            </div>
        </form>
    </div>
</div>

<div id="positionModal" class="modal-overlay">
    <div class="modal-content">
        <h3 id="positionModalTitle" class="modal-title">Add Position</h3>
        <form id="positionForm" action="{{ route('admin.positions.store') }}" method="POST">
            @csrf
            <input type="hidden" name="_method" id="positionMethod" value="POST">
            <div class="form-group">
                <label class="form-label">Position Name</label>
                <input type="text" name="name" id="positionName" class="form-control" required placeholder="Floor 1 - Desk 4, etc...">
            </div>
            <div class="modal-actions">
                <button type="button" onclick="closeModal('positionModal')" class="btn-cancel">Cancel</button>
                <button type="submit" class="btn-save">Save changes</button>
            </div>
        </form>
    </div>
</div>

<div id="categoryModal" class="modal-overlay">
    <div class="modal-content">
        <h3 id="categoryModalTitle" class="modal-title">Add Category</h3>
        <form id="categoryForm" action="{{ route('admin.categories.store') }}" method="POST">
            @csrf
            <input type="hidden" name="_method" id="categoryMethod" value="POST">
            <div class="form-group">
                <label class="form-label">Name</label>
                <input type="text" name="name" id="categoryName" class="form-control" required placeholder="Name...">
            </div>
            <div class="modal-actions">
                <button type="button" onclick="closeModal('categoryModal')" class="btn-cancel">Cancel</button>
                <button type="submit" class="btn-save">Save changes</button>
            </div>
        </form>
    </div>
</div>

<div id="subcategoryModal" class="modal-overlay">
    <div class="modal-content">
        <h3 id="subcategoryModalTitle" class="modal-title">Add Sub-Category</h3>
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
                <button type="button" onclick="closeModal('subcategoryModal')" class="btn-cancel">Cancel</button>
                <button type="submit" class="btn-save">Save changes</button>
            </div>
        </form>
    </div>
</div>

<div id="priorityModal" class="modal-overlay">
    <div class="modal-content">
        <h3 id="priorityModalTitle" class="modal-title">Add Priority</h3>
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
                <button type="button" onclick="closeModal('priorityModal')" class="btn-cancel">Cancel</button>
                <button type="submit" class="btn-save">Save changes</button>
            </div>
        </form>
    </div>
</div>

<div id="statusModal" class="modal-overlay">
    <div class="modal-content">
        <h3 id="statusModalTitle" class="modal-title">Add Status</h3>
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
                <button type="button" onclick="closeModal('statusModal')" class="btn-cancel">Cancel</button>
                <button type="submit" class="btn-save">Save changes</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal(id) { document.getElementById(id).style.display = 'flex'; }
    function closeModal(id) { document.getElementById(id).style.display = 'none'; }

    function editRole(id, name) {
        document.getElementById('roleModalTitle').innerText = 'Edit Role';
        document.getElementById('roleName').value = name;
        document.getElementById('roleForm').action = `/admin/roles/${id}`;
        document.getElementById('roleMethod').value = 'PUT';
        openModal('roleModal');
    }

    function editDesignation(id, name) {
        document.getElementById('designationModalTitle').innerText = 'Edit Designation';
        document.getElementById('designationName').value = name;
        document.getElementById('designationForm').action = `/admin/designations/${id}`;
        document.getElementById('designationMethod').value = 'PUT';
        openModal('designationModal');
    }

    function editPosition(id, name) {
        document.getElementById('positionModalTitle').innerText = 'Edit Position';
        document.getElementById('positionName').value = name;
        document.getElementById('positionForm').action = `/admin/positions/${id}`;
        document.getElementById('positionMethod').value = 'PUT';
        openModal('positionModal');
    }

    function editCategory(id, name) {
        document.getElementById('categoryModalTitle').innerText = 'Edit Category';
        document.getElementById('categoryName').value = name;
        document.getElementById('categoryForm').action = `/admin/categories/${id}`;
        document.getElementById('categoryMethod').value = 'PUT';
        openModal('categoryModal');
    }

    function editSubCategory(id, name, parentId) {
        document.getElementById('subcategoryModalTitle').innerText = 'Edit Sub-Category';
        document.getElementById('subcategoryName').value = name;
        document.getElementById('subcategoryParent').value = parentId;
        document.getElementById('subcategoryForm').action = `/admin/subcategories/${id}`;
        document.getElementById('subcategoryMethod').value = 'PUT';
        openModal('subcategoryModal');
    }

    function editPriority(id, name, color) {
        document.getElementById('priorityModalTitle').innerText = 'Edit Priority';
        document.getElementById('priorityName').value = name;
        document.getElementById('priorityColor').value = color;
        document.getElementById('priorityForm').action = `/admin/ticket-priorities/${id}`;
        document.getElementById('priorityMethod').value = 'PUT';
        openModal('priorityModal');
    }

    function editStatus(id, name, color) {
        document.getElementById('statusModalTitle').innerText = 'Edit Status';
        document.getElementById('statusName').value = name;
        document.getElementById('statusColor').value = color;
        document.getElementById('statusForm').action = `/admin/ticket-statuses/${id}`;
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
    .modal-title { font-size: 1.25rem; font-weight: 800; color: #0f172a; margin: 0 0 1.5rem; }
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
</script>
@endsection
