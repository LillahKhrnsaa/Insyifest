<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance - Cikampek Swimming Club</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=nunito:400,500,600,700,800,900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Nunito', sans-serif; }
        .fade-in { animation: fadeIn 1s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        
        .bg-subtle {
            background-color: #f8fafc;
            background-image: radial-gradient(#e2e8f0 0.5px, transparent 0.5px);
            background-size: 32px 32px;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(12deg); }
            50% { transform: translateY(-10px) rotate(15deg); }
        }
        .animate-float { animation: float 3s ease-in-out infinite; }
    </style>
</head>
<body class="bg-subtle text-slate-800 antialiased h-screen flex items-center justify-center p-6">
    <div class="max-w-2xl w-full text-center fade-in">
        
        {{-- Animated Image Section (Inspired by Loading Screen) --}}
        <div class="relative w-64 h-64 mx-auto mb-12 flex items-center justify-center">
            {{-- Decorative Blobs --}}
            <div class="absolute inset-0 bg-blue-50 rounded-[3.5rem] rotate-12 animate-float opacity-80"></div>
            <div class="absolute inset-0 bg-indigo-50 rounded-[3.5rem] -rotate-12 animate-pulse opacity-60" style="animation-delay: 0.5s"></div>
            
            {{-- Main Logo --}}
            <div class="relative z-10 group">
                <img src="{{ asset('images/logocsc.png') }}" alt="CSC Logo" class="w-32 h-32 object-contain filter drop-shadow-2xl transition-transform duration-700 group-hover:scale-110">
            </div>
            
            {{-- Rotating Ring --}}
            <div class="absolute inset-2 border-[8px] border-white rounded-[3rem] shadow-inner"></div>
            <div class="absolute inset-2 border-[8px] border-blue-600 rounded-[3rem] border-t-transparent border-l-transparent animate-spin duration-1000 shadow-xl"></div>
        </div>

        {{-- Text Content --}}
        <div class="space-y-4">
            <h1 class="text-4xl md:text-6xl font-black text-slate-800 uppercase tracking-tighter leading-none">
                Sistem Sedang <br> <span class="text-blue-600">Ditingkatkan</span>
            </h1>
            
            <p class="text-lg md:text-xl text-slate-400 font-bold italic max-w-lg mx-auto leading-relaxed">
                "Kami sedang memoles kolam digital kami untuk performa yang lebih tajam. Mohon tunggu sebentar!"
            </p>
        </div>

        {{-- Status Cards --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-12 mb-12">
            <div class="bg-white/80 backdrop-blur-sm p-4 rounded-3xl border border-slate-100 shadow-sm group hover:shadow-md transition-all">
                <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center mx-auto mb-2 group-hover:bg-blue-600 transition-colors">
                    <i data-feather="settings" class="w-5 h-5 text-blue-600 group-hover:text-white"></i>
                </div>
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Maintenance</p>
            </div>
            <div class="bg-white/80 backdrop-blur-sm p-4 rounded-3xl border border-slate-100 shadow-sm group hover:shadow-md transition-all">
                <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center mx-auto mb-2 group-hover:bg-indigo-600 transition-colors">
                    <i data-feather="refresh-cw" class="w-5 h-5 text-indigo-600 group-hover:text-white"></i>
                </div>
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Updating</p>
            </div>
            <div class="bg-white/80 backdrop-blur-sm p-4 rounded-3xl border border-slate-100 shadow-sm group hover:shadow-md transition-all">
                <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center mx-auto mb-2 group-hover:bg-emerald-600 transition-colors">
                    <i data-feather="cpu" class="w-5 h-5 text-emerald-600 group-hover:text-white"></i>
                </div>
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Optimization</p>
            </div>
            <div class="bg-white/80 backdrop-blur-sm p-4 rounded-3xl border border-slate-100 shadow-sm group hover:shadow-md transition-all">
                <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center mx-auto mb-2 group-hover:bg-amber-600 transition-colors">
                    <i data-feather="lock" class="w-5 h-5 text-amber-600 group-hover:text-white"></i>
                </div>
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Security</p>
            </div>
        </div>

        <div class="flex items-center justify-center gap-4">
            <span class="w-12 h-[1px] bg-slate-200"></span>
            <div class="text-[10px] font-black text-slate-400 uppercase tracking-[0.4em]">
                Cikampek Swimming Club
            </div>
            <span class="w-12 h-[1px] bg-slate-200"></span>
        </div>
    </div>

    <script src="https://unpkg.com/feather-icons"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof feather !== 'undefined') feather.replace();
        });
    </script>
</body>
</html>
