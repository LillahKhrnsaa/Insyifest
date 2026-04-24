<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 fade-in" style="animation-delay: 0.1s;">
    {{-- Total Kehadiran --}}
    <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm hover:shadow-md transition-all duration-300 group">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Kehadiran</p>
                <h3 class="text-3xl font-black text-slate-800 group-hover:text-blue-600 transition-colors">{{ str_pad($totalAttendances ?? 0, 2, '0', STR_PAD_LEFT) }}</h3>
            </div>
            <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center group-hover:bg-blue-600 transition-all duration-500 shadow-sm">
                <i data-feather="calendar" class="w-7 h-7 text-blue-600 group-hover:text-white transition-colors"></i>
            </div>
        </div>
        <div class="mt-4 pt-4 border-t border-slate-50">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest flex items-center gap-2">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                Total Sesi Latihan
            </p>
        </div>
    </div>

    {{-- Data Raport --}}
    <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm hover:shadow-md transition-all duration-300 group">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Evaluasi</p>
                <h3 class="text-3xl font-black text-slate-800 group-hover:text-indigo-600 transition-colors">{{ str_pad($totalRaports ?? 0, 2, '0', STR_PAD_LEFT) }}</h3>
            </div>
            <div class="w-14 h-14 bg-indigo-50 rounded-2xl flex items-center justify-center group-hover:bg-indigo-600 transition-all duration-500 shadow-sm">
                <i data-feather="file-text" class="w-7 h-7 text-indigo-600 group-hover:text-white transition-colors"></i>
            </div>
        </div>
        <div class="mt-4 pt-4 border-t border-slate-50">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest flex items-center gap-2">
                <span class="w-1.5 h-1.5 rounded-full bg-indigo-400"></span>
                Laporan Performa
            </p>
        </div>
    </div>

    {{-- Coach --}}
    <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm hover:shadow-md transition-all duration-300 group">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Coach</p>
                <h3 class="text-3xl font-black text-slate-800 group-hover:text-amber-600 transition-colors">{{ str_pad($assignedCoaches->count(), 2, '0', STR_PAD_LEFT) }}</h3>
            </div>
            <div class="w-14 h-14 bg-amber-50 rounded-2xl flex items-center justify-center group-hover:bg-amber-600 transition-all duration-500 shadow-sm">
                <i data-feather="users" class="w-7 h-7 text-amber-600 group-hover:text-white transition-colors"></i>
            </div>
        </div>
        <div class="mt-4 pt-4 border-t border-slate-50">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest flex items-center gap-2">
                <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                Pelatih Pembimbing
            </p>
        </div>
    </div>

    {{-- Jadwal --}}
    <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm hover:shadow-md transition-all duration-300 group">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Jadwal</p>
                <h3 class="text-3xl font-black text-slate-800 group-hover:text-rose-600 transition-colors">{{ str_pad($trainingSchedules->count(), 2, '0', STR_PAD_LEFT) }}</h3>
            </div>
            <div class="w-14 h-14 bg-rose-50 rounded-2xl flex items-center justify-center group-hover:bg-rose-600 transition-all duration-500 shadow-sm">
                <i data-feather="clock" class="w-7 h-7 text-rose-600 group-hover:text-white transition-colors"></i>
            </div>
        </div>
        <div class="mt-4 pt-4 border-t border-slate-50">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">
                <span class="w-1.5 h-1.5 rounded-full bg-rose-400"></span>
                Sesi Rutin Mingguan
            </p>
        </div>
    </div>
</div>