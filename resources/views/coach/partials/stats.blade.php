<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 fade-in" style="animation-delay: 0.1s;">
    {{-- Total Atlet --}}
    <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm hover:shadow-md transition-all duration-300 group">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Total Atlet</p>
                <h3 class="text-3xl font-black text-slate-800 group-hover:text-blue-600 transition-colors">{{ str_pad($totalMembers, 2, '0', STR_PAD_LEFT) }}</h3>
            </div>
            <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center group-hover:bg-blue-600 transition-all duration-500 shadow-sm">
                <i data-feather="users" class="w-7 h-7 text-blue-600 group-hover:text-white transition-colors"></i>
            </div>
        </div>
        <div class="mt-4 pt-4 border-t border-slate-50">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest flex items-center gap-2">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                {{ $activeMembers }} Atlet Aktif
            </p>
        </div>
    </div>

    {{-- Atlet Aktif --}}
    <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm hover:shadow-md transition-all duration-300 group">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Sesi Aktif</p>
                <h3 class="text-3xl font-black text-slate-800 group-hover:text-indigo-600 transition-colors">{{ str_pad($activeMembers, 2, '0', STR_PAD_LEFT) }}</h3>
            </div>
            <div class="w-14 h-14 bg-indigo-50 rounded-2xl flex items-center justify-center group-hover:bg-indigo-600 transition-all duration-500 shadow-sm">
                <i data-feather="activity" class="w-7 h-7 text-indigo-600 group-hover:text-white transition-colors"></i>
            </div>
        </div>
        <div class="mt-4 pt-4 border-t border-slate-50">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest flex items-center gap-2">
                <span class="w-1.5 h-1.5 rounded-full bg-indigo-400"></span>
                Monitoring Performa
            </p>
        </div>
    </div>

    {{-- Jadwal --}}
    <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm hover:shadow-md transition-all duration-300 group">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Jadwal</p>
                <h3 class="text-3xl font-black text-slate-800 group-hover:text-amber-600 transition-colors">{{ str_pad($totalSchedules, 2, '0', STR_PAD_LEFT) }}</h3>
            </div>
            <div class="w-14 h-14 bg-amber-50 rounded-2xl flex items-center justify-center group-hover:bg-amber-600 transition-all duration-500 shadow-sm">
                <i data-feather="calendar" class="w-7 h-7 text-amber-600 group-hover:text-white transition-colors"></i>
            </div>
        </div>
        <div class="mt-4 pt-4 border-t border-slate-50">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest flex items-center gap-2">
                <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                Agenda Minggu Ini
            </p>
        </div>
    </div>

    {{-- Total Sesi --}}
    <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm hover:shadow-md transition-all duration-300 group">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Total Sesi</p>
                <h3 class="text-3xl font-black text-slate-800 group-hover:text-rose-600 transition-colors">{{ str_pad(count($attendances), 2, '0', STR_PAD_LEFT) }}</h3>
            </div>
            <div class="w-14 h-14 bg-rose-50 rounded-2xl flex items-center justify-center group-hover:bg-rose-600 transition-all duration-500 shadow-sm">
                <i data-feather="clipboard" class="w-7 h-7 text-rose-600 group-hover:text-white transition-colors"></i>
            </div>
        </div>
        <div class="mt-4 pt-4 border-t border-slate-50">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">
                <span class="w-1.5 h-1.5 rounded-full bg-rose-400"></span>
                Arsip Terakumulasi
            </p>
        </div>
    </div>
</div>