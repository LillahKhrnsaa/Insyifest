<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8 fade-in" style="animation-delay: 0.1s;">
    {{-- Total Kehadiran --}}
    <div class="bg-white rounded-xl p-5 border border-slate-200 card-hover">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-slate-500 mb-1">Total Kehadiran</p>
                <h3 class="text-2xl font-bold text-slate-800">{{ $totalAttendances ?? 0 }}</h3>
            </div>
            <div class="w-12 h-12 bg-cyan-50 rounded-lg flex items-center justify-center">
                <i data-feather="calendar" class="w-6 h-6 text-cyan-600"></i>
            </div>
        </div>
    </div>

    {{-- Data Raport --}}
    <div class="bg-white rounded-xl p-5 border border-slate-200 card-hover">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-slate-500 mb-1">Data Raport</p>
                <h3 class="text-2xl font-bold text-slate-800">{{ $totalRaports ?? 0 }}</h3>
            </div>
            <div class="w-12 h-12 bg-green-50 rounded-lg flex items-center justify-center">
                <i data-feather="file-text" class="w-6 h-6 text-green-600"></i>
            </div>
        </div>
    </div>

    {{-- Coach --}}
    <div class="bg-white rounded-xl p-5 border border-slate-200 card-hover">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-slate-500 mb-1">Coach</p>
                <h3 class="text-2xl font-bold text-slate-800">{{ $assignedCoaches->count() }}</h3>
            </div>
            <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center">
                <i data-feather="users" class="w-6 h-6 text-blue-600"></i>
            </div>
        </div>
    </div>

    {{-- Jadwal --}}
    <div class="bg-white rounded-xl p-5 border border-slate-200 card-hover">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-slate-500 mb-1">Jadwal</p>
                <h3 class="text-2xl font-bold text-slate-800">{{ $trainingSchedules->count() }}</h3>
            </div>
            <div class="w-12 h-12 bg-purple-50 rounded-lg flex items-center justify-center">
                <i data-feather="clock" class="w-6 h-6 text-purple-600"></i>
            </div>
        </div>
    </div>
</div>