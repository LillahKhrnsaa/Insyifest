<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Cikampek Swimming Club</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=nunito:400,500,600,700,800,900&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Nunito', sans-serif; }
        .fade-in { animation: fadeIn 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        @keyframes fadeIn { 
            from { opacity: 0; transform: translateY(20px); } 
            to { opacity: 1; transform: translateY(0); } 
        }
        .bg-pattern {
            background: linear-gradient(135deg, #eff6ff 0%, #f0f9ff 50%, #f5f3ff 100%);
        }
    </style>
</head>
<body class="h-full bg-pattern text-slate-700 antialiased relative overflow-hidden">

{{-- Floating background orbs matching registration theme --}}
<div class="absolute inset-0 pointer-events-none overflow-hidden z-0">
    <div class="absolute top-[-10%] left-[-5%] w-96 h-96 rounded-full opacity-40"
        style="background: radial-gradient(circle, #bfdbfe, transparent); filter: blur(60px);"></div>
    <div class="absolute top-[40%] right-[-8%] w-[30rem] h-[30rem] rounded-full opacity-30"
        style="background: radial-gradient(circle, #a5f3fc, transparent); filter: blur(80px);"></div>
    <div class="absolute bottom-[-5%] left-[25%] w-80 h-80 rounded-full opacity-30"
        style="background: radial-gradient(circle, #ddd6fe, transparent); filter: blur(60px);"></div>
</div>

<div class="min-h-screen flex items-center justify-center p-6 relative z-10">

    <div class="max-w-xl w-full fade-in relative z-10">
        {{-- ── Header Card ── --}}
        <div class="relative mb-6 rounded-3xl overflow-hidden"
            style="background: linear-gradient(135deg, #1d4ed8 0%, #0369a1 100%); box-shadow: 0 20px 60px -10px rgba(29,78,216,0.35);">
            {{-- Pattern overlay --}}
            <div class="absolute inset-0 opacity-10"
                style="background: repeating-linear-gradient(45deg, transparent, transparent 25px, rgba(255,255,255,0.07) 25px, rgba(255,255,255,0.07) 50px);"></div>
            {{-- Glow circle --}}
            <div class="absolute -right-10 -top-10 w-48 h-48 rounded-full opacity-20"
                style="background: radial-gradient(circle, #93c5fd, transparent);"></div>

            <div class="relative px-8 py-7 flex items-center justify-between">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <div class="w-1.5 h-1.5 rounded-full bg-sky-300 animate-pulse"></div>
                        <span class="text-sky-200 text-xs font-semibold tracking-widest uppercase">Portal Akses</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Cikampek Swimming Club</h1>
                    <p class="mt-1 text-blue-200 text-sm font-medium">Manajemen Atlet & Pelatih CSC</p>
                </div>
                <div class="flex-shrink-0">
                    <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl flex items-center justify-center"
                        style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.25); backdrop-filter: blur(10px);">
                        <img src="{{ asset('images/logocsc.png') }}" class="h-12 sm:h-16 w-auto drop-shadow-xl" alt="CSC Logo">
                    </div>
                </div>
            </div>

            {{-- Security Bar --}}
            <div class="px-8 pb-6 flex items-center gap-3">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-full bg-white flex items-center justify-center text-xs font-bold text-blue-700 shadow"><i data-feather="lock" class="w-3.5 h-3.5"></i></div>
                    <span class="text-white text-xs font-semibold hidden sm:block">Akses Aman Terenkripsi</span>
                </div>
                <div class="flex-1 h-px" style="background: rgba(255,255,255,0.35);"></div>
            </div>
        </div>

        {{-- Login Card --}}
        <div class="bg-white rounded-[2.5rem] shadow-2xl shadow-blue-900/5 border border-slate-100 overflow-hidden">
            <div class="p-10">
                @if($errors->any())
                    <div class="mb-8 p-4 bg-rose-50 border border-rose-100 rounded-2xl flex items-center gap-3">
                        <div class="w-8 h-8 bg-rose-500 rounded-lg flex items-center justify-center shrink-0 shadow-lg shadow-rose-200">
                            <i data-feather="alert-circle" class="w-4 h-4 text-white"></i>
                        </div>
                        <p class="text-[10px] font-black text-rose-600 uppercase tracking-tight leading-tight">{{ $errors->first() }}</p>
                    </div>
                @endif

                @if(session('status'))
                    <div class="mb-8 p-4 bg-emerald-50 border border-emerald-100 rounded-2xl flex items-center gap-3">
                        <div class="w-8 h-8 bg-emerald-500 rounded-lg flex items-center justify-center shrink-0 shadow-lg shadow-emerald-200">
                            <i data-feather="check" class="w-4 h-4 text-white"></i>
                        </div>
                        <p class="text-[10px] font-black text-emerald-600 uppercase tracking-tight leading-tight">{{ session('status') }}</p>
                    </div>
                @endif

                <form class="space-y-8" action="{{ route('login') }}" method="POST" x-data="{ showPassword: false }">
                    @csrf

                    <div class="space-y-2">
                        <label for="phone" class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Nomor Telepon</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                                <i data-feather="phone" class="w-4 h-4 text-slate-400 group-focus-within:text-blue-600 transition-colors"></i>
                            </div>
                            <input id="phone" name="phone" type="text" required 
                                   class="w-full pl-12 pr-5 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold text-slate-700 placeholder:text-slate-300 focus:bg-white focus:ring-4 focus:ring-blue-600/5 focus:border-blue-600 transition-all outline-none"
                                   value="{{ old('phone') }}"
                                   placeholder="Contoh: 08123456789"
                                   autocomplete="tel"
                                   autofocus>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <div class="flex items-center justify-between px-1">
                            <label for="password" class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Kata Sandi</label>
                            @if(Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-[10px] font-black text-blue-600 uppercase tracking-widest hover:text-blue-700 transition-colors">Lupa Password?</a>
                            @endif
                        </div>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                                <i data-feather="lock" class="w-4 h-4 text-slate-400 group-focus-within:text-blue-600 transition-colors"></i>
                            </div>
                            <input :type="showPassword ? 'text' : 'password'" id="password" name="password" required 
                                   class="w-full pl-12 pr-14 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold text-slate-700 placeholder:text-slate-300 focus:bg-white focus:ring-4 focus:ring-blue-600/5 focus:border-blue-600 transition-all outline-none"
                                   placeholder="••••••••"
                                   autocomplete="current-password">
                            
                            <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 pr-5 flex items-center text-slate-400 hover:text-blue-600 transition-colors focus:outline-none">
                                <template x-if="!showPassword">
                                    <i data-feather="eye" class="w-4 h-4"></i>
                                </template>
                                <template x-if="showPassword">
                                    <i data-feather="eye-off" class="w-4 h-4"></i>
                                </template>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 px-1">
                        <input id="remember" name="remember" type="checkbox" 
                               class="w-5 h-5 text-blue-600 border-slate-200 rounded-lg focus:ring-blue-600 transition-all cursor-pointer"
                               {{ old('remember') ? 'checked' : '' }}>
                        <label for="remember" class="text-[11px] font-black text-slate-500 uppercase tracking-widest cursor-pointer select-none">Ingat saya di perangkat ini</label>
                    </div>

                    <button type="submit"
                        class="w-full py-4 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl font-extrabold text-xs uppercase tracking-[0.2em] shadow-xl shadow-blue-500/30 hover:shadow-blue-600/40 transform hover:-translate-y-1 active:translate-y-0 transition-all duration-300 flex items-center justify-center gap-3">
                        Masuk Sekarang
                        <i data-feather="arrow-right" class="w-4 h-4"></i>
                    </button>
                </form>

                {{-- Footer Action --}}
                <div class="mt-12 pt-8 border-t border-slate-50 text-center">
                    <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-4">Belum memiliki akun?</p>
                    <a href="{{ route('member.register.create') }}" class="inline-flex items-center justify-center px-8 py-3 bg-slate-50 hover:bg-blue-50 text-blue-600 rounded-xl font-black text-[10px] uppercase tracking-widest border border-slate-100 transition-all">
                        Daftar Member Baru
                    </a>
                </div>
            </div>
        </div>

        <div class="mt-10 text-center flex items-center justify-center gap-6">
            <a href="{{ url('/') }}" class="text-[10px] font-black text-slate-400 uppercase tracking-widest hover:text-blue-600 transition-all flex items-center gap-2">
                <i data-feather="home" class="w-3.5 h-3.5"></i> Beranda
            </a>
            <span class="w-1.5 h-1.5 rounded-full bg-slate-200"></span>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
                © {{ date('Y') }} CSC Management
            </p>
        </div>
    </div>
</div>

<script src="https://unpkg.com/feather-icons"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof feather !== 'undefined') feather.replace();
    });

    document.addEventListener('alpine:initialized', () => {
        Alpine.effect(() => {
            if (typeof feather !== 'undefined') feather.replace();
        });
    });
</script>

</body>
</html>
