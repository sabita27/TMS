<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php
        $sys_name = \App\Models\Setting::get('system_name', 'TMS PRO');
        $sys_favicon = \App\Models\Setting::get('system_favicon');
    @endphp
    <title>@yield('title') - Dashboard | {{ $sys_name }}</title>
    @if($sys_favicon)
        <link rel="icon" type="image/x-icon" href="{{ asset('storage/' . $sys_favicon) }}">
    @endif
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
    <style>
        :root {
            --primary-color: #4f46e5;
            --primary-hover: #4338ca;
            --secondary-color: #10b981;
            --bg-light: #f9fafb;
            --sidebar-bg: #111827;
            --sidebar-text: #d1d5db;
            --sidebar-hover: #1f2937;
            --text-main: #111827;
            --text-muted: #6b7280;
            --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        body { font-family: 'Inter', sans-serif; background-color: var(--bg-light); color: var(--text-main); margin: 0; display: flex; height: 100vh; overflow: hidden; }
        
        /* Shared Styles */
        .card { background: #fff; border-radius: 0.75rem; padding: 1.5rem; box-shadow: var(--card-shadow); margin-bottom: 1.5rem; }
        .card-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; border-bottom: 1px solid #f3f4f6; padding-bottom: 1rem; }
        .card-title { font-size: 1.25rem; font-weight: 600; }
        .btn { padding: 0.625rem 1.25rem; border-radius: 0.375rem; font-weight: 500; cursor: pointer; transition: all 0.2s; border: none; display: inline-flex; align-items: center; gap: 0.5rem; text-decoration: none; }
        .btn-primary { background-color: var(--primary-color); color: #fff; }
        .btn-danger { background-color: #ef4444; color: #fff; }
        .btn-danger:hover { background-color: #dc2626; }
        .form-group { margin-bottom: 1rem; }
        .form-label { display: block; margin-bottom: 0.5rem; font-weight: 500; color: var(--text-muted); }
        .form-control { width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; box-sizing: border-box; }
        .table-container { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; padding: 0.75rem 1rem; background-color: #f9fafb; color: var(--text-muted); font-size: 0.875rem; border-bottom: 1px solid #e5e7eb; }
        td { padding: 1rem; border-bottom: 1px solid #f3f4f6; font-size: 0.875rem; }
        .badge { padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; }
        .badge-success { background-color: #d1fae5; color: #065f46; }
        .badge-danger { background-color: #fee2e2; color: #991b1b; }
        .badge-warning { background-color: #fef3c7; color: #92400e; }
        .badge-info { background-color: #dbeafe; color: #1e40af; }

        /* Responsive Breakpoints */
        @media (max-width: 1024px) {
            body { flex-direction: column; height: auto; overflow: auto; }
            aside { width: 100% !important; height: auto !important; position: static !important; }
            main { padding: 1rem !important; }
            .sidebar-toggle { display: block !important; }
        }

        @media (max-width: 768px) {
            .card-header { flex-direction: column; align-items: flex-start; gap: 1rem; }
            .btn { width: 100%; justify-content: center; }
        }

        /* Custom DataTables Styling (Refined Professional Theme) */
        .dataTables_wrapper {
            padding: 0;
            margin-top: 1rem;
        }
        
        .dataTables_wrapper .top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.25rem;
            padding: 0 0.5rem;
        }

        .dataTables_wrapper .dt-buttons {
            display: flex;
            gap: 4px;
        }

        .dataTables_wrapper .dt-buttons .btn {
            background: #334155 !important;
            color: #ffffff !important;
            border: none !important;
            padding: 0.5rem 1.25rem !important;
            font-size: 0.75rem !important;
            font-weight: 600 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.5px !important;
            border-radius: 4px !important;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05) !important;
            transition: all 0.2s ease !important;
        }

        .dataTables_wrapper .dt-buttons .btn:hover {
            background: #1e293b !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 6px rgba(0,0,0,0.1) !important;
        }

        .dataTables_filter {
            margin: 0 !important;
        }

        .dataTables_filter label {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 600;
            color: #64748b;
            font-size: 0.85rem;
        }

        .dataTables_filter input {
            background: #ffffff !important;
            border: 1px solid #e2e8f0 !important;
            border-radius: 6px !important;
            padding: 0.5rem 1rem !important;
            width: 280px !important;
            outline: none !important;
            font-size: 0.85rem !important;
            color: #1e293b !important;
            box-shadow: inset 0 1px 2px rgba(0,0,0,0.02) !important;
            margin: 0 !important;
        }

        .dataTables_filter input:focus {
            border-color: #4f46e5 !important;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1) !important;
        }

        #clientTable, #setupTable {
            border: 1px solid #e2e8f0 !important;
            border-radius: 12px !important;
            overflow: hidden !important;
            margin-top: 0 !important;
            width: 100% !important;
            border-collapse: separate !important;
            border-spacing: 0 !important;
        }

        #clientTable thead, #setupTable thead {
            background-color: #cbd5e1 !important;
        }

        #clientTable thead tr th, #setupTable thead tr th {
            color: #000000 !important;
            background-color: #cbd5e1 !important;
            font-weight: 800 !important;
            text-transform: uppercase !important;
            font-size: 0.75rem !important;
            padding: 1.25rem 2rem 1.25rem 1rem !important; /* Increased padding-right to 2rem */
            border: none !important;
            letter-spacing: 0.05em !important;
            border-bottom: 2px solid #94a3b8 !important;
            position: relative !important;
        }

        /* Adjust Sorting Icons Position to prevent overlapping */
        table.dataTable thead .sorting:before, table.dataTable thead .sorting:after,
        table.dataTable thead .sorting_asc:before, table.dataTable thead .sorting_asc:after,
        table.dataTable thead .sorting_desc:before, table.dataTable thead .sorting_desc:after {
            right: 0.5rem !important; /* Fixed distance from the right edge */
        }

        #clientTable tbody td, #setupTable tbody td {
            background: #ffffff !important;
            padding: 1rem !important;
            border-bottom: 1px solid #f1f5f9 !important;
            color: #1e293b !important;
            font-size: 0.875rem !important;
        }

        #clientTable tbody tr:last-child td, #setupTable tbody tr:last-child td {
            border-bottom: none !important;
        }

        #clientTable tbody tr:nth-child(even) td, #setupTable tbody tr:nth-child(even) td {
            background-color: #f8fafc !important;
        }

        #clientTable tbody tr:hover td, #setupTable tbody tr:hover td {
            background-color: #f1f5f9 !important;
        }

        /* Sorting Icons Visibility (Dark for Grey Header) */
        table.dataTable thead .sorting:before, table.dataTable thead .sorting:after,
        table.dataTable thead .sorting_asc:before, table.dataTable thead .sorting_asc:after,
        table.dataTable thead .sorting_desc:before, table.dataTable thead .sorting_desc:after {
            color: #000000 !important;
            opacity: 0.6 !important;
        }

        /* DataTables Premium Pagination Styling (Global) */
        .dataTables_paginate, 
        .dataTables_wrapper .dataTables_paginate {
            padding-top: 1.5rem !important;
            display: flex !important;
            flex-direction: row !important;
            flex-wrap: nowrap !important;
            justify-content: flex-end !important;
            align-items: center !important;
            gap: 0.25rem !important;
        }
        .dataTables_paginate .pagination {
            display: flex !important;
            flex-direction: row !important;
            flex-wrap: nowrap !important;
            gap: 0.25rem !important;
            margin: 0 !important;
            padding: 0 !important;
            list-style: none !important;
        }
        .dataTables_paginate span {
            display: flex !important;
            flex-direction: row !important;
        }

        /* Target core button elements */
        .dataTables_wrapper .dataTables_paginate .paginate_button,
        .dataTables_paginate .pagination .page-item .page-link {
            padding: 0.45rem 0.6rem !important;
            border-radius: 8px !important;
            border: 1px solid #94a3b8 !important;
            background: #cbd5e1 !important; 
            color: #000000 !important;
            font-size: 0.85rem !important;
            font-weight: 800 !important;
            cursor: pointer !important;
            transition: all 0.2s !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            text-decoration: none !important;
            box-shadow: none !important;
            min-width: 30px !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover:not(.disabled),
        .dataTables_paginate .pagination .page-item:hover:not(.active):not(.disabled) .page-link {
            background: #94a3b8 !important;
            border-color: #64748b !important;
            color: #000000 !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current,
        .dataTables_paginate .pagination .page-item.active .page-link {
            background: #cbd5e1 !important;
            color: #000000 !important;
            border-color: #6366f1 !important;
            border-width: 2px !important;
            font-weight: 800 !important;
        }

        /* Specialized small Previous/Next buttons */
        .dataTables_wrapper .dataTables_paginate .paginate_button.previous,
        .dataTables_wrapper .dataTables_paginate .paginate_button.next,
        .dataTables_paginate .pagination .page-item:first-child .page-link,
        .dataTables_paginate .pagination .page-item:last-child .page-link {
            font-size: 0.75rem !important;
            padding: 0.35rem 0.5rem !important;
            text-transform: uppercase !important;
            letter-spacing: 0.05em !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled,
        .dataTables_paginate .pagination .page-item.disabled .page-link {
            opacity: 0.7 !important;
            cursor: not-allowed !important;
            background: #cbd5e1 !important;
            color: #000000 !important;
            border-color: #94a3b8 !important;
            font-weight: 800 !important;
        }
    </style>
    @yield('styles')
</head>
