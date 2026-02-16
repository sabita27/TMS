<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - TMS PRO</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #4f46e5;
            --primary-gradient: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            --bg-light: #f3f4f6;
        }
        body { font-family: 'Inter', sans-serif; background: var(--bg-light); margin: 0; }
    </style>
    @yield('styles')
</head>
