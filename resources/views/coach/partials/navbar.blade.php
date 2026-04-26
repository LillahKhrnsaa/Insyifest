<nav class="border-b border-white/10 sticky top-0 z-[999] shadow-lg backdrop-blur-md" style="background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 100%);">
    <div class="mx-auto px-6 lg:px-10">
        <div class="flex justify-between items-center h-20">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center p-2 shadow-sm border border-white/20 backdrop-blur-sm">
                    <img src="{{ asset('images/logocsc.png') }}" alt="CSC Logo" class="h-full w-auto object-contain brightness-0 invert">
                </div>
                <div class="hidden md:block">
                    <h1 class="text-lg font-black text-white leading-none uppercase tracking-tighter drop-shadow-sm">Cikampek Swimming Club</h1>
                    <p class="text-[10px] font-bold text-blue-200 uppercase tracking-widest mt-1">Platform Pelatih Professional</p>
                </div>
            </div>
            <div class="flex items-center gap-6">
                @auth
                <div class="flex items-center gap-6">
                    <div class="text-right hidden sm:block border-r border-white/20 pr-6">
                        <p class="text-sm font-black text-white leading-tight uppercase tracking-tight drop-shadow-sm">{{ Auth::user()->name }}</p>
                        <div class="flex items-center justify-end gap-1.5 mt-0.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 shadow-[0_0_8px_rgba(52,211,153,0.8)]"></span>
                            <p class="text-[10px] text-blue-200 font-bold uppercase tracking-widest">Active Coach</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-12 h-12 bg-white/10 hover:bg-white/20 text-white rounded-2xl transition-all flex items-center justify-center border border-white/20 group shadow-sm backdrop-blur-sm">
                            <i data-feather="log-out" class="w-5 h-5 group-hover:scale-110 group-hover:text-rose-300 transition-all"></i>
                        </button>
                    </form>
                </div>
                @endauth
            </div>
        </div>
    </div>
</nav>