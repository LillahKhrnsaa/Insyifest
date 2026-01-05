<div class="bg-white rounded-xl border border-slate-200 overflow-hidden card-hover h-fit">
    <div class="px-5 py-4 border-b border-slate-100 bg-slate-50">
        <div class="flex flex-col gap-3">
            <div class="flex items-center justify-between">
                <h3 class="font-bold text-slate-800 flex items-center gap-2">
                    <i data-feather="calendar" class="w-5 h-5 text-blue-600"></i>
                    Jadwal Minggu Ini
                </h3>
                <span class="text-xs text-slate-500 bg-slate-100 px-2 py-1 rounded">{{ $coach->trainingSchedules->count() }} sesi</span>
            </div>

            {{-- TOMBOL ABSEN UTAMA --}}
            <button @click="toggleModal()" 
                    class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-sm flex items-center justify-center gap-2 shadow-lg shadow-blue-100 transition-all active:scale-95">
                <i data-feather="plus-circle" class="w-4 h-4"></i>
                Absen Sekarang
            </button>
        </div>
    </div>
                
    <div class="overflow-hidden">
        <div class="divide-y divide-slate-100 max-h-[350px] overflow-y-auto custom-scrollbar">
            @forelse($coach->trainingSchedules->take(5) as $schedule)
            <div class="px-5 py-4 hover:bg-slate-50 transition-colors">
                <div class="flex items-center justify-between group">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center group-hover:bg-blue-100 transition-colors">
                            <i data-feather="calendar" class="w-5 h-5 text-blue-600"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-800">{{ ucfirst($schedule->day) }}</h4>
                            <div class="flex flex-col gap-0.5 mt-0.5">
                                <p class="text-xs font-medium text-slate-600 flex items-center gap-1">
                                    <i data-feather="clock" class="w-3 h-3 text-slate-400"></i>
                                    {{ $schedule->time ? \Carbon\Carbon::parse($schedule->time)->format('H:i') : '--:--' }} WIB
                                </p>
                                <p class="text-xs text-slate-500 flex items-center gap-1">
                                    <i data-feather="map-pin" class="w-3 h-3 text-slate-400"></i>
                                    {{ $schedule->place ?? 'Kolam Utama' }}
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <i data-feather="chevron-right" class="w-4 h-4 text-slate-300 group-hover:text-blue-400 transition-colors"></i>
                </div>
            </div>
            @empty
            <div class="px-5 py-8 text-center text-slate-400">
                <i data-feather="calendar" class="w-8 h-8 mx-auto mb-2 opacity-50"></i>
                <p>Tidak ada jadwal latihan</p>
            </div>
            @endforelse
        </div>
    </div>
    
    <div class="px-5 py-3 border-t border-slate-100 bg-slate-50">
        <button @click="showAllSchedules = true" class="w-full text-sm font-bold text-blue-600 hover:text-blue-700 flex items-center justify-center gap-1 transition-colors">
            <span>Lihat Semua Jadwal</span>
            <i data-feather="chevron-right" class="w-4 h-4"></i>
        </button>
    </div>
</div>