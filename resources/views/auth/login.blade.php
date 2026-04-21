<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Cikampek Swimming Club</title>
    @vite('resources/css/app.css')
</head>
<body>

<div class="min-h-screen bg-gradient-to-br from-blue-50 via-cyan-50 to-sky-100 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute -top-40 -right-32 w-80 h-80 bg-cyan-400/20 rounded-full mix-blend-multiply filter blur-3xl animate-float-slow"></div>
        <div class="absolute -bottom-40 -left-32 w-80 h-80 bg-blue-400/20 rounded-full mix-blend-multiply filter blur-3xl animate-float-slower"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-sky-400/10 rounded-full mix-blend-multiply filter blur-3xl"></div>
    </div>

    <div class="absolute bottom-0 left-0 w-full overflow-hidden">
        <svg class="relative block w-full h-20" viewBox="0 0 1200 120" preserveAspectRatio="none">
            <path d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z" opacity=".1" class="fill-cyan-500/30"></path>
            <path d="M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.07,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,25.39,62.32,62,103.63,73,40.44,10.79,81.35-6.69,119.13-24.28s75.16-39,116.92-43.05c59.73-5.85,113.28,22.88,168.9,38.84,30.2,8.66,59,6.17,87.09-7.5,22.43-10.89,48-26.93,60.65-49.24V0Z" opacity=".2" class="fill-cyan-500/30"></path>
        </svg>
    </div>

    <div class="max-w-md w-full space-y-8 fade-in relative z-10">
        <div class="bg-white/90 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/50 overflow-hidden">
            <div class="bg-gradient-to-r from-cyan-500 to-blue-600 px-8 py-8 text-center">
                <div class="flex justify-center mb-4">
                    <div class="p-4 rounded-2xl">
                        <img src="{{ asset('images/logocsc.png') }}" alt="Logo Cikampek Swimming Club" class="h-24 w-auto">
                    </div>
                </div>
                <h1 class="text-2xl font-bold text-white mb-2">Cikampek Swimming Club</h1>
                <p class="text-cyan-100 text-sm">Masuk ke akun Anda untuk melanjutkan</p>
            </div>

            <div class="px-8 py-8">
                @if($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl shadow-sm" role="alert">
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"/>
                            </svg>
                            <p class="ml-3 text-sm font-medium text-red-800">{{ $errors->first() }}</p>
                        </div>
                    </div>
                @endif

                @if(session('status'))
                    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl shadow-sm" role="alert">
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                            </svg>
                            <p class="ml-3 text-sm font-medium text-green-800">{{ session('status') }}</p>
                        </div>
                    </div>
                @endif

                <form class="space-y-6" action="{{ route('login') }}" method="POST">
                    @csrf

                    <div>
                        <label for="phone" class="block text-sm font-semibold text-slate-700 mb-2 flex items-center">
                            Nomor Telepon
                        </label>
                        <div class="relative">
                            <input id="phone" name="phone" type="text" required 
                                   class="w-full px-4 py-3 pl-11 border rounded-xl shadow-sm focus:ring-2 focus:ring-cyan-500 bg-white/80 backdrop-blur-sm"
                                   value="{{ old('phone') }}"
                                   placeholder="081234567890"
                                   autocomplete="tel"
                                   autofocus>
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-semibold text-slate-700 mb-2 flex items-center">
                            Password
                        </label>
                        <div class="relative">
                            <input id="password" name="password" type="password" required 
                                   class="w-full px-4 py-3 pl-11 border rounded-xl shadow-sm focus:ring-2 focus:ring-cyan-500 bg-white/80 backdrop-blur-sm"
                                   placeholder="Masukkan password Anda"
                                   autocomplete="current-password">
                                <div>
                                    <svg xmlns="http://www.w3.org/2000/svg" 
                                        class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-500"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path d="M12 17a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" />
                                        <path d="M6 10V8a6 6 0 1 1 12 0v2" />
                                        <rect width="14" height="12" x="5" y="10" rx="2" />
                                    </svg>
                                </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="flex items-center text-sm text-slate-600 font-medium">
                            <input id="remember" name="remember" type="checkbox" 
                                   class="h-4 w-4 text-cyan-600 border-slate-300 rounded"
                                   {{ old('remember') ? 'checked' : '' }}>
                            <span class="ml-2">Ingat saya</span>
                        </label>

                        @if(Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-sm font-medium text-cyan-600 hover:text-cyan-500">
                                Lupa password?
                            </a>
                        @endif
                    </div>

                    <button type="submit"
                        class="group w-full py-3.5 px-4 text-lg font-bold rounded-xl text-white bg-gradient-to-r from-cyan-500 to-blue-600 hover:from-cyan-600 hover:to-blue-700 shadow-lg hover:shadow-xl transition-all duration-300 flex items-center justify-center">
                        Masuk ke Akun
                    </button>
                </form>

                <div class="relative my-6 flex items-center">
                    <div class="w-full border-t border-slate-200"></div>
                    <span class="px-2 bg-white text-slate-500 text-sm">Atau</span>
                    <div class="w-full border-t border-slate-200"></div>
                </div>

                <p class="text-sm text-slate-600 text-center">
                    Belum memiliki akun?
                    <a href="{{ route('member.register.create') }}" class="font-semibold text-cyan-600 hover:text-cyan-700">
                        Daftar member baru
                    </a>
                </p>
                <p class="text-sm text-center mt-3 pt-10">
                    <a href="{{ url('/') }}" class="font-semibold text-cyan-600 hover:text-cyan-700">
                        Kembali ke Beranda
                    </a>
                </p>
            </div>
        </div>

        <p class="text-xs text-slate-500 text-center">
            © {{ date('Y') }} Cikampek Swimming Club. All rights reserved.
        </p>
    </div>
</div>

<style>
@keyframes float-slow {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    50% { transform: translateY(-20px) rotate(180deg); }
}

@keyframes float-slower {
    0%, 100% { transform: translateX(0px) rotate(0deg); }
    50% { transform: translateX(20px) rotate(-180deg); }
}

.animate-float-slow { animation: float-slow 15s ease-in-out infinite; }
.animate-float-slower { animation: float-slower 20s ease-in-out infinite; }
</style>

</body>
</html>
