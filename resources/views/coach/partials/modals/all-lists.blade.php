{{-- MODAL 1: SEMUA ATLET --}}
<div x-show="showAllMembers" x-cloak class="relative z-50">
    <div x-show="showAllMembers" x-transition.opacity class="fixed inset-0 modal-overlay"></div>
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6">
        <div x-show="showAllMembers" @click.away="showAllMembers = false" x-transition.scale 
             class="relative w-full max-w-4xl bg-white rounded-3xl shadow-2xl slide-up max-h-[85vh] flex flex-col overflow-hidden">
            
            <div class="bg-gradient-to-r from-cyan-500 to-blue-600 px-6 py-5 flex justify-between items-center shrink-0">
                <div>
                    <h3 class="text-xl font-bold text-white">Daftar Seluruh Atlet</h3>
                    <p class="text-sm text-cyan-100 mt-0.5">Total: {{ $coach->members->count() }} Atlet</p>
                </div>
                <button @click="showAllMembers = false" class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center text-white hover:bg-white/20 transition-colors">
                    <i data-feather="x" class="w-5 h-5"></i>
                </button>
            </div>

            <div class="overflow-y-auto">
                <table class="w-full">
                    <thead class="bg-slate-50 text-xs text-slate-500 font-bold uppercase sticky top-0 z-10 shadow-sm">
                        <tr>
                            <th class="px-6 py-4 text-center w-16">No</th>
                            <th class="px-6 py-4 text-left">Atlet</th>
                            <th class="px-6 py-4 text-left">Email</th>
                            <th class="px-6 py-4 text-left">Status</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @php
                            $allSortedMembers = $coach->members->sortBy(function($member) { return $member->user->name; });
                        @endphp
                        @foreach($allSortedMembers as $member) 
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 text-center font-bold text-slate-500">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-slate-100 overflow-hidden border border-slate-200">
                                        @if ($member->user->photo_url)
                                            <img src="{{ $member->user->photo_url }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-slate-400">
                                                <i data-feather="user" class="w-4 h-4"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-800">{{ $member->user->name }}</div>
                                        <div class="text-xs text-slate-500">ID: {{ $member->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-slate-600">{{ $member->user->email }}</td>
                            <td class="px-6 py-4">
                                <span class="status-badge {{ $member->status == 'AKTIF' ? 'status-active' : 'status-inactive' }}">
                                    {{ $member->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button onclick="openRaportModal({{ $member->id }}, '{{ $member->user->name }}'); showAllMembers = false;" 
                                        class="px-4 py-2 bg-cyan-50 text-cyan-600 hover:bg-cyan-100 rounded-lg font-bold text-xs transition-colors flex items-center gap-2 mx-auto">
                                    <i data-feather="file-text" class="w-3 h-3"></i> Lihat Raport
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- MODAL 2: SEMUA JADWAL --}}
<div x-show="showAllSchedules" x-cloak class="relative z-50">
    <div x-show="showAllSchedules" x-transition.opacity class="fixed inset-0 modal-overlay"></div>
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6">
        <div x-show="showAllSchedules" @click.away="showAllSchedules = false" x-transition.scale 
             class="relative w-full max-w-4xl bg-white rounded-3xl shadow-2xl slide-up max-h-[85vh] flex flex-col overflow-hidden">
            
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-5 flex justify-between items-center shrink-0">
                <div>
                    <h3 class="text-xl font-bold text-white">Jadwal Latihan Lengkap</h3>
                    <p class="text-sm text-blue-100 mt-0.5">{{ $coach->trainingSchedules->count() }} sesi latihan</p>
                </div>
                <button @click="showAllSchedules = false" class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center text-white hover:bg-white/20 transition-colors">
                    <i data-feather="x" class="w-5 h-5"></i>
                </button>
            </div>

            <div class="p-6 overflow-y-auto">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($coach->trainingSchedules as $schedule)
                    <div class="bg-white p-5 rounded-xl border border-slate-200 hover:shadow-lg transition-all card-hover">
                        <div class="flex items-start justify-between mb-4">
                            <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center">
                                <i data-feather="calendar" class="w-6 h-6 text-blue-600"></i>
                            </div>
                            <span class="bg-blue-100 text-blue-700 text-xs font-bold px-3 py-1 rounded-full uppercase">
                                {{ ucfirst($schedule->day) }}
                            </span>
                        </div>
                        <h4 class="font-bold text-slate-800 mb-2 text-lg">{{ $schedule->place ?? 'Kolam Utama' }}</h4>
                        <div class="space-y-3">
                            <div class="flex items-center text-sm text-slate-600">
                                <i data-feather="clock" class="w-4 h-4 mr-2 text-slate-400"></i>
                                <span>{{ $schedule->time ? \Carbon\Carbon::parse($schedule->time)->format('H:i') : '--:--' }} WIB</span>
                            </div>
                            <div class="pt-4 border-t border-slate-100">
                                <button @click="toggleModal({{ $schedule->id }}, '{{ $schedule->place ?? '' }}'); showAllSchedules = false;"
                                        class="w-full py-3 btn-primary flex items-center justify-center gap-2 text-sm font-bold">
                                    <i data-feather="check-square" class="w-4 h-4"></i> Absen Jadwal Ini
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL 3: SEMUA RIWAYAT --}}
<div x-show="showAllHistory" x-cloak class="relative z-50">
    <div x-show="showAllHistory" x-transition.opacity class="fixed inset-0 modal-overlay"></div>
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6">
        <div x-show="showAllHistory" @click.away="showAllHistory = false" x-transition.scale 
             class="relative w-full max-w-4xl bg-white rounded-3xl shadow-2xl slide-up max-h-[85vh] flex flex-col overflow-hidden">
            
            <div class="bg-gradient-to-r from-cyan-500 to-blue-600 px-6 py-5 flex justify-between items-center shrink-0">
                <div>
                    <h3 class="text-xl font-bold text-white">Riwayat Kehadiran Lengkap</h3>
                    <p class="text-sm text-cyan-100 mt-0.5">Arsip seluruh sesi latihan</p>
                </div>
                <button @click="showAllHistory = false" class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center text-white hover:bg-white/20 transition-colors">
                    <i data-feather="x" class="w-5 h-5"></i>
                </button>
            </div>

            <div class="overflow-y-auto">
                <table class="w-full">
                    <thead class="bg-slate-50 text-xs uppercase text-slate-500 font-bold sticky top-0 z-10 shadow-sm">
                        <tr>
                            <th class="px-6 py-4 text-left">Tanggal & Waktu</th>
                            <th class="px-6 py-4 text-left">Lokasi</th>
                            <th class="px-6 py-4 text-left">Total Hadir</th>
                            <th class="px-6 py-4 text-left">Dokumentasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($attendances as $attendance)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-800">
                                    {{ \Carbon\Carbon::parse($attendance->date)->isoFormat('dddd, D MMMM Y') }}
                                </div>
                                <div class="text-sm text-slate-500 mt-1 flex items-center gap-1">
                                    <i data-feather="clock" class="w-3 h-3"></i>
                                    {{ $attendance->schedule ? \Carbon\Carbon::parse($attendance->schedule->time)->format('H:i') : '-' }} WIB
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2 text-slate-600">
                                    <i data-feather="map-pin" class="w-4 h-4 text-slate-400"></i>
                                    {{ $attendance->place ?? '-' }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-cyan-100 text-cyan-700 font-bold">
                                        {{ $attendance->members_count }}
                                    </span>
                                    <span class="text-sm text-slate-600">atlet</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($attendance->photo_path)
                                    <a href="{{ asset('storage/'.$attendance->photo_path) }}" target="_blank" 
                                       class="inline-flex items-center gap-2 px-4 py-2 bg-cyan-50 text-cyan-600 hover:bg-cyan-100 rounded-lg font-bold text-xs transition-colors">
                                        <i data-feather="image" class="w-4 h-4"></i> Lihat Foto
                                    </a>
                                @else
                                    <span class="text-slate-400 text-sm italic">Tidak ada foto</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>