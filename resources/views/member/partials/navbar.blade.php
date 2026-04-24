<nav class="bg-white border-b border-slate-50 sticky top-0 z-[999]">
    <div class="mx-auto px-6 lg:px-10">
        <div class="flex justify-between items-center h-20">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center p-2 shadow-sm border border-blue-100">
                    <img src="{{ asset('images/logocsc.png') }}" alt="CSC Logo" class="h-full w-auto object-contain">
                </div>
                <div class="hidden md:block">
                    <h1 class="text-lg font-black text-slate-800 leading-none uppercase tracking-tighter">Cikampek Swimming Club</h1>
                    <p class="text-[10px] font-black text-blue-600 uppercase tracking-widest mt-1">Portal Atlet Professional</p>
                </div>
            </div>
            <div class="flex items-center gap-6">
                <div class="flex items-center gap-6">
                    <div class="text-right hidden sm:block border-r border-slate-100 pr-6">
                        <p class="text-sm font-black text-slate-800 leading-tight uppercase tracking-tight">{{ Auth::user()->name }}</p>
                        <div class="flex items-center justify-end gap-1.5 mt-0.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                            <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest">Active Member</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-12 h-12 bg-slate-50 hover:bg-blue-50 text-slate-400 hover:text-blue-600 rounded-2xl transition-all flex items-center justify-center border border-slate-100 group shadow-sm">
                            <i data-feather="log-out" class="w-5 h-5 group-hover:scale-110 transition-transform"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>