<div class="bg-white rounded-[2rem] border border-slate-100 overflow-hidden shadow-md hover:shadow-xl transition-all duration-300">
    <div class="px-8 py-6 border-b border-blue-100 relative overflow-hidden" style="background: linear-gradient(to right, #eff6ff, #ffffff);">
        <div class="absolute top-0 right-0 w-32 h-32 bg-blue-100 rounded-full blur-2xl -mr-10 -mt-10 opacity-50"></div>
        <div class="flex items-center gap-3 relative z-10">
            <div class="w-12 h-12 rounded-xl bg-blue-500 flex items-center justify-center shadow-inner">
                <i data-feather="calendar" class="w-6 h-6 text-white"></i>
            </div>
            <div>
                <h3 class="font-bold text-slate-800 text-lg">Jadwal Latihan</h3>
                <p class="text-sm font-medium text-slate-500 mt-0.5">Sesi Rutin Mingguan</p>
            </div>
        </div>
    </div>
    <div class="divide-y divide-slate-50 max-h-[500px] overflow-y-auto custom-scrollbar">
        @forelse($trainingSchedules as $schedule)
            <div class="px-8 py-5 hover:bg-blue-50/30 transition-all group">
                <div class="flex justify-between items-center mb-1">
                    <h4 class="font-black text-slate-700 group-hover:text-blue-600 transition-colors text-sm uppercase tracking-tight">{{ ucfirst($schedule->day) }}</h4>
                    <span class="text-[10px] font-black bg-blue-50 text-blue-600 px-3 py-1 rounded-lg uppercase tracking-widest border border-blue-100 shadow-sm">
                        {{ $schedule->time ? \Carbon\Carbon::parse($schedule->time)->format('H:i') : '--:--' }} WIB
                    </span>
                </div>
                <div class="text-[11px] font-bold text-slate-400 flex items-center gap-2 mt-1">
                    <i data-feather="map-pin" class="w-3.5 h-3.5 opacity-50"></i> {{ $schedule->place ?? 'Kolam Utama' }}
                </div>
            </div>
        @empty
            <div class="px-8 py-16 text-center">
                <div class="w-16 h-16 bg-slate-50 rounded-3xl flex items-center justify-center mx-auto mb-4">
                    <i data-feather="calendar" class="w-8 h-8 text-slate-200"></i>
                </div>
                <p class="text-[10px] font-black text-slate-300 uppercase tracking-widest">Tidak ada jadwal terdaftar</p>
            </div>
        @endforelse
    </div>
</div>