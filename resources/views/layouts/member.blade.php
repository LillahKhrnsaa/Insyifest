<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50 scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Member Dashboard') - Cikampek Swimming Club</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=nunito:400,500,600,700,800,900&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Nunito', sans-serif; }
        [x-cloak] { display: none !important; }
        
        /* Custom Scrollbar Premium */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f8fafc; }
        ::-webkit-scrollbar-thumb { 
            background: #e2e8f0; 
            border-radius: 10px;
            border: 2px solid #f8fafc;
        }
        ::-webkit-scrollbar-thumb:hover { background: #3b82f6; }

        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { 
            background: #e2e8f0; 
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #3b82f6; }

        .fade-in { animation: fadeIn 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        @keyframes fadeIn { 
            from { opacity: 0; transform: translateY(20px); } 
            to { opacity: 1; transform: translateY(0); } 
        }

        .slide-up { animation: slideUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px) scale(0.98); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        .btn-primary { 
            background: #2563eb; 
            color: white; 
            font-weight: 800; 
            text-transform: uppercase;
            letter-spacing: 0.1em;
            font-size: 0.75rem;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .btn-primary:hover { 
            background: #1d4ed8;
            transform: translateY(-2px); 
            box-shadow: 0 10px 20px -5px rgba(37, 99, 235, 0.3); 
        }

        .input-field { 
            background: #f8fafc; 
            border: 1px solid #f1f5f9; 
            border-radius: 1rem; 
            padding: 0.875rem 1.25rem; 
            font-size: 0.875rem; 
            font-weight: 600;
            transition: all 0.3s ease; 
        }
        .input-field:focus { 
            outline: none; 
            border-color: #3b82f6; 
            background: white; 
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1); 
        }
    </style>
    @stack('styles')
    @livewireStyles
</head>
<body class="h-full text-slate-700 antialiased bg-slate-50">
    @include('components.demo-badge')
    
    <div x-data="{}" class="min-h-screen">
        @include('components.loading-screen')
        @yield('content')
    </div>

    <script src="https://unpkg.com/feather-icons"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof feather !== 'undefined') feather.replace();
        });
        
        // Re-replace feather icons for dynamic content
        window.addEventListener('content-updated', () => {
            if (typeof feather !== 'undefined') feather.replace();
        });
    </script>
    @stack('scripts')
    @livewireScripts
</body>
</html>