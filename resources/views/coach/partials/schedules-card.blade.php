<div class="bg-white rounded-3xl border border-slate-100 overflow-hidden shadow-sm hover:shadow-md transition-all duration-300 h-fit">
    <div class="px-6 py-5 border-b border-slate-50 bg-white">
        <div class="flex flex-col gap-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center">
                        <i data-feather="calendar" class="w-5 h-5 text-blue-600"></i>
                    </div>
                    <div>
                        <h3 class="font-extrabold text-slate-800 text-sm uppercase tracking-tight">Jadwal Minggu Ini</h3>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">{{ $coach->trainingSchedules->count() }} Sesi Latihan</p>
                    </div>
                </div>
            </div>

            {{-- TOMBOL ABSEN UTAMA --}}
            <button @click="toggleModal()" 
                    class="w-full py-3.5 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest flex items-center justify-center gap-3 shadow-xl shadow-blue-100 transition-all active:scale-95">
                <i data-feather="check-square" class="w-4 h-4"></i>
                Absen Sekarang
            </button>
        </div>
    </div>
                
    <div class="overflow-hidden">
        <div class="divide-y divide-slate-50 max-h-[400px] overflow-y-auto custom-scrollbar">
            @forelse($coach->trainingSchedules->take(5) as $schedule)
            <div class="px-6 py-4 hover:bg-blue-50/30 transition-all group cursor-pointer" @click="toggleModal({{ $schedule->id }}, '{{ $schedule->place ?? '' }}')">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-slate-50 rounded-xl flex items-center justify-center group-hover:bg-blue-600 transition-all duration-500 shadow-sm">
                            <i data-feather="clock" class="w-5 h-5 text-slate-400 group-hover:text-white transition-colors"></i>
                        </div>
                        <div>
                            <h4 class="font-black text-slate-700 group-hover:text-blue-600 transition-colors text-sm uppercase tracking-tight">{{ ucfirst($schedule->day) }}</h4>
                            <div class="flex flex-col gap-0.5 mt-0.5">
                                <p class="text-[11px] font-bold text-slate-400 flex items-center gap-2">
                                    {{ $schedule->time ? \Carbon\Carbon::parse($schedule->time)->format('H:i') : '--:--' }} WIB
                                    <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                                    {{ $schedule->place ?? 'Kolam Utama' }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <i data-feather="chevron-right" class="w-4 h-4 text-slate-200 group-hover:text-blue-400 group-hover:translate-x-1 transition-all"></i>
                </div>
            </div>
            @empty
            <div class="px-6 py-12 text-center">
                <div class="w-16 h-16 bg-slate-50 rounded-3xl flex items-center justify-center mx-auto mb-4">
                    <i data-feather="calendar" class="w-8 h-8 text-slate-200"></i>
                </div>
                <p class="text-xs font-black text-slate-300 uppercase tracking-widest">Tidak ada jadwal</p>
            </div>
            @endforelse
        </div>
    </div>
    
    <div class="px-6 py-4 border-t border-slate-50 bg-white">
        <button @click="showAllSchedules = true" class="w-full py-3 rounded-2xl bg-slate-50 text-slate-400 hover:bg-blue-50 hover:text-blue-600 font-black text-[10px] uppercase tracking-[0.2em] transition-all flex items-center justify-center gap-2">
            <span>Lihat Semua Jadwal</span>
            <i data-feather="arrow-right" class="w-4 h-4"></i>
        </button>
    </div>
</div>