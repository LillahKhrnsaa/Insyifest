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
            background-color: #f8fafc;
            background-image: radial-gradient(#e2e8f0 0.5px, transparent 0.5px);
            background-size: 24px 24px;
        }
    </style>
</head>
<body class="h-full bg-pattern text-slate-700 antialiased">

<div class="min-h-screen flex items-center justify-center p-6 relative overflow-hidden">
    {{-- Decorative Subtle Glows --}}
    <div class="absolute -top-24 -right-24 w-[500px] h-[500px] bg-blue-100/30 rounded-full blur-[100px]"></div>
    <div class="absolute -bottom-24 -left-24 w-[500px] h-[500px] bg-indigo-100/30 rounded-full blur-[100px]"></div>

    <div class="max-w-md w-full fade-in relative z-10">
        {{-- Brand Logo Section --}}
        <div class="text-center mb-10">
            <div class="inline-flex items-center justify-center w-24 h-24 bg-white rounded-[2rem] shadow-xl shadow-blue-100/50 border border-slate-50 p-4 mb-6 group hover:scale-105 transition-transform duration-500">
                <img src="{{ asset('images/logocsc.png') }}" alt="CSC Logo" class="w-full h-auto object-contain">
            </div>
            <h1 class="text-3xl font-black text-slate-800 uppercase tracking-tighter leading-none">Cikampek</h1>
            <h2 class="text-3xl font-black text-blue-600 uppercase tracking-tighter leading-none mt-1">Swimming Club</h2>
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
                        class="w-full py-4 bg-blue-600 text-white rounded-2xl font-black text-xs uppercase tracking-[0.2em] shadow-xl shadow-blue-100 hover:bg-blue-700 hover:-translate-y-1 active:translate-y-0 transition-all duration-300 flex items-center justify-center gap-3">
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
