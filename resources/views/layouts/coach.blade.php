<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50 scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Coach Dashboard') - Cikampek Swimming Club</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=nunito:400,500,600,700,800,900&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Nunito', sans-serif; }
        [x-cloak] { display: none !important; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #0891b2; }
        .fade-in { animation: fadeIn 0.5s ease-out forwards; }
        .slide-up { animation: slideUp 0.3s ease-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes slideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .card-hover { transition: all 0.3s ease; }
        .card-hover:hover { transform: translateY(-4px); box-shadow: 0 12px 24px -8px rgba(8, 145, 178, 0.15); }
        .status-badge { padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 700; display: inline-flex; align-items: center; gap: 6px; }
        .status-badge::before { content: ''; width: 8px; height: 8px; border-radius: 50%; display: inline-block; }
        .status-active { background-color: #f0fdf4; color: #16a34a; }
        .status-active::before { background-color: #16a34a; }
        .status-inactive { background-color: #f1f5f9; color: #64748b; }
        .status-inactive::before { background-color: #64748b; }
        .btn-primary { background: linear-gradient(135deg, #0891b2 0%, #2563eb 100%); color: white; font-weight: 700; padding: 10px 20px; border-radius: 10px; transition: all 0.3s ease; border: none; cursor: pointer; }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 16px -4px rgba(8, 145, 178, 0.3); }
        .input-field { background: #f8fafc; border: 2px solid #e2e8f0; border-radius: 10px; padding: 12px 16px; font-size: 14px; transition: all 0.3s ease; }
        .input-field:focus { outline: none; border-color: #0891b2; background: white; box-shadow: 0 0 0 3px rgba(8, 145, 178, 0.1); }
        .modal-overlay { background: rgba(15, 23, 42, 0.5); backdrop-filter: blur(4px); }
        .custom-scrollbar { scrollbar-width: thin; scrollbar-color: #cbd5e1 #f1f5f9; }
    </style>
</head>
<body class="h-full text-slate-700 antialiased">
    @auth
        @include('coach.partials.navbar')
    @endauth

    @yield('content')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof feather !== 'undefined') feather.replace();
        });
    </script>
    @stack('scripts')
</body>
</html>