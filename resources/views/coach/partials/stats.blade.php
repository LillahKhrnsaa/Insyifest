<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8 fade-in" style="animation-delay: 0.1s;">
    {{-- Total Atlet --}}
    <div class="bg-white rounded-xl p-5 border border-slate-200 card-hover">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-slate-500 mb-1">Total Atlet</p>
                <h3 class="text-2xl font-bold text-slate-800">{{ $totalMembers }}</h3>
            </div>
            <div class="w-12 h-12 bg-cyan-50 rounded-lg flex items-center justify-center">
                <i data-feather="users" class="w-6 h-6 text-cyan-600"></i>
            </div>
        </div>
        <div class="mt-3 pt-3 border-t border-slate-100">
            <p class="text-xs text-slate-500">{{ $activeMembers }} atlet aktif</p>
        </div>
    </div>

    {{-- Atlet Aktif --}}
    <div class="bg-white rounded-xl p-5 border border-slate-200 card-hover">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-slate-500 mb-1">Atlet Aktif</p>
                <h3 class="text-2xl font-bold text-slate-800">{{ $activeMembers }}</h3>
            </div>
            <div class="w-12 h-12 bg-green-50 rounded-lg flex items-center justify-center">
                <i data-feather="activity" class="w-6 h-6 text-green-600"></i>
            </div>
        </div>
        <div class="mt-3 pt-3 border-t border-slate-100">
            <p class="text-xs text-slate-500">Sedang berlatih</p>
        </div>
    </div>

    {{-- Jadwal --}}
    <div class="bg-white rounded-xl p-5 border border-slate-200 card-hover">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-slate-500 mb-1">Jadwal</p>
                <h3 class="text-2xl font-bold text-slate-800">{{ $totalSchedules }}</h3>
            </div>
            <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center">
                <i data-feather="calendar" class="w-6 h-6 text-blue-600"></i>
            </div>
        </div>
        <div class="mt-3 pt-3 border-t border-slate-100">
            <p class="text-xs text-slate-500">Minggu ini</p>
        </div>
    </div>

    {{-- Total Sesi --}}
    <div class="bg-white rounded-xl p-5 border border-slate-200 card-hover">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-slate-500 mb-1">Total Sesi</p>
                <h3 class="text-2xl font-bold text-slate-800">{{ count($attendances) }}</h3>
            </div>
            <div class="w-12 h-12 bg-purple-50 rounded-lg flex items-center justify-center">
                <i data-feather="clipboard" class="w-6 h-6 text-purple-600"></i>
            </div>
        </div>
        <div class="mt-3 pt-3 border-t border-slate-100">
            <p class="text-xs text-slate-500">Riwayat latihan</p>
        </div>
    </div>
</div>