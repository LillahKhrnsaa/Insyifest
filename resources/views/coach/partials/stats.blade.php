<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 fade-in" style="animation-delay: 0.1s;">
    {{-- Total Atlet --}}
    <div class="bg-white rounded-3xl p-6 border-t-4 border-t-blue-500 border-x border-b border-slate-100 shadow-md hover:shadow-xl transition-all duration-300 group">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-semibold text-slate-500 mb-1">Total Atlet</p>
                <h3 class="text-4xl font-black text-slate-800 group-hover:text-blue-600 transition-colors">{{ str_pad($totalMembers, 2, '0', STR_PAD_LEFT) }}</h3>
            </div>
            <div class="w-16 h-16 bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl flex items-center justify-center group-hover:from-blue-500 group-hover:to-blue-600 transition-all duration-500 shadow-sm">
                <i data-feather="users" class="w-8 h-8 text-blue-600 group-hover:text-white transition-colors"></i>
            </div>
        </div>
        <div class="mt-5 pt-4 border-t border-slate-100">
            <p class="text-sm font-medium text-slate-500 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
                {{ $activeMembers }} Atlet Aktif
            </p>
        </div>
    </div>

    {{-- Atlet Aktif --}}
    <div class="bg-white rounded-3xl p-6 border-t-4 border-t-indigo-500 border-x border-b border-slate-100 shadow-md hover:shadow-xl transition-all duration-300 group">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-semibold text-slate-500 mb-1">Sesi Aktif</p>
                <h3 class="text-4xl font-black text-slate-800 group-hover:text-indigo-600 transition-colors">{{ str_pad($activeMembers, 2, '0', STR_PAD_LEFT) }}</h3>
            </div>
            <div class="w-16 h-16 bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-2xl flex items-center justify-center group-hover:from-indigo-500 group-hover:to-indigo-600 transition-all duration-500 shadow-sm">
                <i data-feather="activity" class="w-8 h-8 text-indigo-600 group-hover:text-white transition-colors"></i>
            </div>
        </div>
        <div class="mt-5 pt-4 border-t border-slate-100">
            <p class="text-sm font-medium text-slate-500 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                Monitoring Performa
            </p>
        </div>
    </div>

    {{-- Jadwal --}}
    <div class="bg-white rounded-3xl p-6 border-t-4 border-t-amber-500 border-x border-b border-slate-100 shadow-md hover:shadow-xl transition-all duration-300 group">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-semibold text-slate-500 mb-1">Jadwal Sesi</p>
                <h3 class="text-4xl font-black text-slate-800 group-hover:text-amber-600 transition-colors">{{ str_pad($totalSchedules, 2, '0', STR_PAD_LEFT) }}</h3>
            </div>
            <div class="w-16 h-16 bg-gradient-to-br from-amber-50 to-amber-100 rounded-2xl flex items-center justify-center group-hover:from-amber-500 group-hover:to-amber-600 transition-all duration-500 shadow-sm">
                <i data-feather="calendar" class="w-8 h-8 text-amber-600 group-hover:text-white transition-colors"></i>
            </div>
        </div>
        <div class="mt-5 pt-4 border-t border-slate-100">
            <p class="text-sm font-medium text-slate-500 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                Agenda Minggu Ini
            </p>
        </div>
    </div>

    {{-- Total Sesi --}}
    <div class="bg-white rounded-3xl p-6 border-t-4 border-t-rose-500 border-x border-b border-slate-100 shadow-md hover:shadow-xl transition-all duration-300 group">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-semibold text-slate-500 mb-1">Riwayat Sesi</p>
                <h3 class="text-4xl font-black text-slate-800 group-hover:text-rose-600 transition-colors">{{ str_pad(count($attendances), 2, '0', STR_PAD_LEFT) }}</h3>
            </div>
            <div class="w-16 h-16 bg-gradient-to-br from-rose-50 to-rose-100 rounded-2xl flex items-center justify-center group-hover:from-rose-500 group-hover:to-rose-600 transition-all duration-500 shadow-sm">
                <i data-feather="clipboard" class="w-8 h-8 text-rose-600 group-hover:text-white transition-colors"></i>
            </div>
        </div>
        <div class="mt-5 pt-4 border-t border-slate-100">
            <p class="text-sm font-medium text-slate-500 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                Arsip Terakumulasi
            </p>
        </div>
    </div>
</div>