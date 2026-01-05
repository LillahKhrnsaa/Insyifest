<div class="bg-white rounded-xl border border-slate-200 overflow-hidden card-hover">
    <div class="px-5 py-4 border-b border-slate-100 bg-slate-50">
        <h3 class="font-bold text-slate-800 flex items-center gap-2">
            <i data-feather="calendar" class="w-5 h-5 text-purple-600"></i> Jadwal Latihan
        </h3>
    </div>
    <div class="divide-y divide-slate-100 max-h-[400px] overflow-y-auto">
        @forelse($trainingSchedules as $schedule)
            <div class="px-5 py-4 hover:bg-slate-50 transition-colors">
                <div class="flex justify-between items-start mb-1">
                    <h4 class="font-bold text-slate-800">{{ ucfirst($schedule->day) }}</h4>
                    <span class="text-xs bg-slate-100 text-slate-600 px-2 py-0.5 rounded">
                        {{ $schedule->time ? \Carbon\Carbon::parse($schedule->time)->format('H:i') : '-' }}
                    </span>
                </div>
                <div class="text-xs text-slate-500 flex items-center gap-1 mb-2">
                    <i data-feather="map-pin" class="w-3 h-3"></i> {{ $schedule->place ?? 'Kolam Utama' }}
                </div>
            </div>
        @empty
            <div class="px-5 py-8 text-center text-slate-400">
                <p>Tidak ada jadwal</p>
            </div>
        @endforelse
    </div>
</div>