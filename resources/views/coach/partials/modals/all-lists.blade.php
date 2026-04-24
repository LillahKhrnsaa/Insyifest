{{-- MODAL 1: SEMUA ATLET --}}
<div x-show="showAllMembers" x-cloak class="relative z-[9999]">
    <div x-show="showAllMembers" x-transition.opacity class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showAllMembers = false"></div>
    <div class="fixed inset-0 z-[9999] flex items-center justify-center p-4">
        <div x-show="showAllMembers" @click.away="showAllMembers = false" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             class="relative w-full max-w-5xl bg-white rounded-[2.5rem] shadow-2xl flex flex-col max-h-[90vh] overflow-hidden border border-slate-100">
            
            {{-- Header --}}
            <div class="px-8 py-6 flex justify-between items-center bg-white border-b border-slate-50 shrink-0">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center">
                        <i data-feather="users" class="w-6 h-6 text-blue-600"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-extrabold text-slate-800 uppercase tracking-tight">Daftar Seluruh Atlet</h3>
                        <p class="text-xs font-medium text-slate-400 uppercase tracking-widest mt-0.5">Total: <span class="text-blue-600 font-black">{{ $coach->members->count() }}</span> Atlet Aktif</p>
                    </div>
                </div>
                <button @click="showAllMembers = false" class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-all">
                    <i data-feather="x" class="w-5 h-5"></i>
                </button>
            </div>

            <div class="overflow-y-auto custom-scrollbar flex-1">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50/50 text-[10px] text-slate-400 font-black uppercase tracking-widest sticky top-0 z-10 backdrop-blur-md border-b border-slate-100">
                        <tr>
                            <th class="px-8 py-5 text-center w-20">No</th>
                            <th class="px-8 py-5 text-left">Informasi Atlet</th>
                            <th class="px-8 py-5 text-left">Kontak & Email</th>
                            <th class="px-8 py-5 text-center">Status</th>
                            <th class="px-8 py-5 text-center">Aksi Manajemen</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @php
                            $allSortedMembers = $coach->members->sortBy(function($member) { return $member->user->name; });
                        @endphp
                        @foreach($allSortedMembers as $member) 
                        <tr class="hover:bg-blue-50/30 transition-colors group">
                            <td class="px-8 py-5 text-center font-black text-slate-300 group-hover:text-blue-400 transition-colors">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</td>
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-2xl bg-slate-100 overflow-hidden border border-slate-100 shadow-sm shrink-0">
                                        @if ($member->user->photo_url)
                                            <img src="{{ $member->user->photo_url }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-slate-300 bg-slate-50">
                                                <i data-feather="user" class="w-5 h-5"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-black text-slate-700 group-hover:text-blue-600 transition-colors leading-tight text-base">{{ $member->user->name }}</div>
                                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter mt-0.5">ID: #{{ $member->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-5">
                                <div class="text-sm font-semibold text-slate-600 italic">{{ $member->user->email }}</div>
                                <div class="text-xs font-bold text-slate-400 mt-0.5">{{ $member->user->phone ?? '-' }}</div>
                            </td>
                            <td class="px-8 py-5 text-center">
                                <span class="px-3 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest border shadow-sm
                                    {{ $member->status == 'AKTIF' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-slate-100 text-slate-500 border-slate-200' }}">
                                    {{ $member->status }}
                                </span>
                            </td>
                            <td class="px-8 py-5">
                                <div class="flex items-center justify-center gap-2">
                                    <button onclick="openRaportModal({{ $member->id }}, '{{ $member->user->name }}'); showAllMembers = false;" 
                                            class="w-10 h-10 bg-white border border-slate-100 text-slate-400 hover:text-blue-600 hover:border-blue-100 hover:shadow-lg hover:shadow-blue-100 rounded-xl transition-all flex items-center justify-center"
                                            title="Lihat Raport">
                                        <i data-feather="bar-chart-2" class="w-4 h-4"></i>
                                    </button>
                                    <button @click="showAllMembers = false; editMember({{ json_encode([
                                        'id' => $member->id,
                                        'name' => $member->user->name,
                                        'email' => $member->user->email,
                                        'phone' => $member->user->phone,
                                        'gender' => $member->user->gender,
                                        'training_package_id' => $member->training_package_id,
                                        'status' => $member->status
                                    ]) }})" 
                                            class="w-10 h-10 bg-white border border-slate-100 text-slate-400 hover:text-indigo-600 hover:border-indigo-100 hover:shadow-lg hover:shadow-indigo-100 rounded-xl transition-all flex items-center justify-center"
                                            title="Edit Data">
                                        <i data-feather="edit-3" class="w-4 h-4"></i>
                                    </button>
                                    <button onclick="deleteMember({{ $member->id }}, '{{ $member->user->name }}')" 
                                            class="w-10 h-10 bg-white border border-slate-100 text-slate-400 hover:text-rose-600 hover:border-rose-100 hover:shadow-lg hover:shadow-rose-100 rounded-xl transition-all flex items-center justify-center"
                                            title="Hapus Atlet">
                                        <i data-feather="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="p-8 bg-slate-50/50 border-t border-slate-100 shrink-0 text-center">
                <button @click="showAllMembers = false" class="px-10 py-4 bg-white border border-slate-200 text-slate-600 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-slate-100 transition-all">Tutup Jendela</button>
            </div>
        </div>
    </div>
</div>

{{-- MODAL 2: SEMUA JADWAL --}}
<div x-show="showAllSchedules" x-cloak class="relative z-[9999]">
    <div x-show="showAllSchedules" x-transition.opacity class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showAllSchedules = false"></div>
    <div class="fixed inset-0 z-[9999] flex items-center justify-center p-4">
        <div x-show="showAllSchedules" @click.away="showAllSchedules = false" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             class="relative w-full max-w-5xl bg-white rounded-[2.5rem] shadow-2xl flex flex-col max-h-[90vh] overflow-hidden border border-slate-100">
            
            <div class="px-8 py-6 flex justify-between items-center bg-white border-b border-slate-50 shrink-0">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center">
                        <i data-feather="calendar" class="w-6 h-6 text-blue-600"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-extrabold text-slate-800 uppercase tracking-tight">Jadwal Latihan Lengkap</h3>
                        <p class="text-xs font-medium text-slate-400 uppercase tracking-widest mt-0.5">Total: <span class="text-blue-600 font-black">{{ $coach->trainingSchedules->count() }}</span> Sesi Rutin</p>
                    </div>
                </div>
                <button @click="showAllSchedules = false" class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-all">
                    <i data-feather="x" class="w-5 h-5"></i>
                </button>
            </div>

            <div class="p-8 overflow-y-auto custom-scrollbar flex-1 bg-slate-50/30">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($coach->trainingSchedules as $schedule)
                    <div class="bg-white p-6 rounded-3xl border border-slate-100 hover:shadow-xl hover:shadow-blue-100 transition-all group flex flex-col justify-between">
                        <div>
                            <div class="flex items-start justify-between mb-6">
                                <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center group-hover:scale-110 group-hover:bg-blue-600 transition-all duration-500">
                                    <i data-feather="clock" class="w-7 h-7 text-blue-600 group-hover:text-white transition-colors"></i>
                                </div>
                                <span class="bg-slate-100 text-slate-500 text-[10px] font-black px-4 py-1.5 rounded-full uppercase tracking-widest border border-slate-200">
                                    {{ ucfirst($schedule->day) }}
                                </span>
                            </div>
                            <h4 class="font-black text-slate-800 mb-2 text-xl leading-tight group-hover:text-blue-600 transition-colors">{{ $schedule->place ?? 'Kolam Utama' }}</h4>
                            <div class="flex items-center text-sm font-bold text-slate-400 italic">
                                <i data-feather="watch" class="w-4 h-4 mr-2 opacity-50"></i>
                                <span>{{ $schedule->time ? \Carbon\Carbon::parse($schedule->time)->format('H:i') : '--:--' }} WIB</span>
                            </div>
                        </div>
                        <div class="pt-8 mt-6 border-t border-slate-50">
                            <button @click="toggleModal({{ $schedule->id }}, '{{ $schedule->place ?? '' }}'); showAllSchedules = false;"
                                    class="w-full py-4 bg-blue-600 text-white rounded-2xl flex items-center justify-center gap-3 text-xs font-black uppercase tracking-widest shadow-lg shadow-blue-100 hover:bg-blue-700 transition-all">
                                <i data-feather="check-square" class="w-4 h-4"></i> Pilih Jadwal
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="p-8 bg-white border-t border-slate-50 shrink-0 text-center">
                <button @click="showAllSchedules = false" class="px-10 py-4 bg-slate-100 text-slate-600 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-slate-200 transition-all">Kembali</button>
            </div>
        </div>
    </div>
</div>

{{-- MODAL 3: SEMUA RIWAYAT --}}
<div x-show="showAllHistory" x-cloak class="relative z-[9999]">
    <div x-show="showAllHistory" x-transition.opacity class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showAllHistory = false"></div>
    <div class="fixed inset-0 z-[9999] flex items-center justify-center p-4">
        <div x-show="showAllHistory" @click.away="showAllHistory = false" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             class="relative w-full max-w-5xl bg-white rounded-[2.5rem] shadow-2xl flex flex-col max-h-[90vh] overflow-hidden border border-slate-100">
            
            <div class="px-8 py-6 flex justify-between items-center bg-white border-b border-slate-50 shrink-0">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center">
                        <i data-feather="clock" class="w-6 h-6 text-blue-600"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-extrabold text-slate-800 uppercase tracking-tight">Riwayat Kehadiran</h3>
                        <p class="text-xs font-medium text-slate-400 uppercase tracking-widest mt-0.5">Arsip Seluruh Sesi Latihan</p>
                    </div>
                </div>
                <button @click="showAllHistory = false" class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-all">
                    <i data-feather="x" class="w-5 h-5"></i>
                </button>
            </div>

            <div class="overflow-y-auto custom-scrollbar flex-1 bg-slate-50/30">
                <table class="w-full text-sm">
                    <thead class="bg-white text-[10px] uppercase text-slate-400 font-black tracking-widest sticky top-0 z-10 border-b border-slate-100 backdrop-blur-md">
                        <tr>
                            <th class="px-8 py-5 text-left">Hari, Tanggal & Jam</th>
                            <th class="px-8 py-5 text-left">Lokasi Kolam</th>
                            <th class="px-8 py-5 text-center">Partisipasi</th>
                            <th class="px-8 py-5 text-center">Dokumentasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($attendances as $attendance)
                        <tr class="hover:bg-white transition-colors group">
                            <td class="px-8 py-6">
                                <div class="font-black text-slate-700 group-hover:text-blue-600 transition-colors text-base">
                                    {{ \Carbon\Carbon::parse($attendance->date)->isoFormat('dddd, D MMMM Y') }}
                                </div>
                                <div class="text-xs font-bold text-slate-400 mt-1 flex items-center gap-2 uppercase tracking-widest italic">
                                    <i data-feather="clock" class="w-3.5 h-3.5 text-blue-400"></i>
                                    {{ $attendance->schedule ? \Carbon\Carbon::parse($attendance->schedule->time)->format('H:i') : '-' }} WIB
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-3 text-slate-600 font-semibold">
                                    <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-blue-400">
                                        <i data-feather="map-pin" class="w-4 h-4"></i>
                                    </div>
                                    {{ $attendance->place ?? '-' }}
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex items-center justify-center gap-3">
                                    <div class="flex -space-x-3">
                                        @foreach($attendance->members->take(3) as $m)
                                            <div class="w-8 h-8 rounded-full border-2 border-white bg-blue-100 overflow-hidden shadow-sm">
                                                @if($m->user->photo_url)
                                                    <img src="{{ $m->user->photo_url }}" class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center text-blue-300 text-[10px] font-black uppercase">
                                                        {{ substr($m->user->name, 0, 1) }}
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                        @if($attendance->members_count > 3)
                                            <div class="w-8 h-8 rounded-full border-2 border-white bg-slate-800 flex items-center justify-center text-[10px] font-black text-white shadow-sm">
                                                +{{ $attendance->members_count - 3 }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="text-xs font-black text-slate-400 uppercase tracking-widest">
                                        <span class="text-blue-600">{{ $attendance->members_count }}</span> Atlet
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-center">
                                @if($attendance->photo_path)
                                    <a href="{{ asset('storage/'.$attendance->photo_path) }}" target="_blank" 
                                       class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white rounded-xl font-black text-[10px] uppercase tracking-widest transition-all shadow-sm">
                                        <i data-feather="image" class="w-4 h-4"></i> View Foto
                                    </a>
                                @else
                                    <span class="text-slate-300 text-[10px] font-black uppercase tracking-widest italic">No Preview</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-8 bg-white border-t border-slate-100 shrink-0 text-center">
                <button @click="showAllHistory = false" class="px-10 py-4 bg-slate-100 text-slate-600 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-slate-200 transition-all">Tutup Riwayat</button>
            </div>
        </div>
    </div>
</div>