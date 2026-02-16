<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Dashboard | TMS PRO</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
    </style>
    @yield('styles')
</head>
