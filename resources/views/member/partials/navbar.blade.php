<nav class="bg-white border-b border-slate-100 shadow-sm sticky top-0 z-50">
    <div class="w-full px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logocsc.png') }}" alt="CSC Logo" class="h-10 w-auto">
                <div class="hidden md:block">
                    <h1 class="text-lg font-bold text-slate-800 leading-tight">Cikampek Swimming Club</h1>
                    <p class="text-xs text-slate-500">Member Dashboard</p>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-3">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-bold text-slate-800">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-cyan-600 font-medium">Member</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-bold rounded-lg transition-colors flex items-center gap-2">
                            <i data-feather="log-out" class="w-4 h-4"></i>
                            <span class="hidden sm:inline">Keluar</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>