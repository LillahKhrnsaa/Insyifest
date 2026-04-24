<div class="bg-white rounded-3xl border border-slate-100 overflow-hidden shadow-sm hover:shadow-md transition-all duration-300 flex flex-col h-full">
    {{-- Header Card --}}
    <div class="px-6 py-5 border-b border-slate-50 bg-white shrink-0">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center">
                    <i data-feather="clock" class="w-5 h-5 text-blue-600"></i>
                </div>
                <div>
                    <h3 class="font-extrabold text-slate-800 text-sm uppercase tracking-tight">Riwayat Absensi</h3>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Arsip Sesi Latihan</p>
                </div>
            </div>
            <div class="flex items-center gap-3 bg-slate-50 px-3 py-1.5 rounded-2xl border border-slate-100">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Filter:</label>
                <input type="month" x-model="filterMonth" 
                    class="text-[10px] font-black border-none rounded-lg p-0 focus:ring-0 bg-transparent text-blue-600 uppercase">
            </div>
        </div>
    </div>
                
    <div class="overflow-x-auto flex-1 w-full custom-scrollbar">
        <table class="w-full min-w-[600px] text-sm">
            <thead>
                <tr class="text-left text-[10px] text-slate-400 font-black uppercase tracking-[0.2em] border-b border-slate-50 bg-slate-50/30">
                    <th class="px-6 py-5">Hari & Tanggal</th>
                    <th class="px-6 py-5 text-center">Kehadiran</th>
                    <th class="px-6 py-5">Catatan</th>
                    <th class="px-6 py-5 text-right">Manajemen</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($attendances as $attendance)
                <tr x-show="filterMonth === '' || '{{ \Carbon\Carbon::parse($attendance->date)->format('Y-m') }}' === filterMonth"
                    x-transition.opacity
                    class="hover:bg-blue-50/30 transition-all group">
                    
                    {{-- 1. Waktu --}}
                    <td class="px-6 py-5">
                        <div class="font-black text-slate-700 text-sm group-hover:text-blue-600 transition-colors leading-tight">
                            {{ \Carbon\Carbon::parse($attendance->date)->isoFormat('dddd') }}
                        </div>
                        <div class="text-[11px] font-bold text-slate-400 mt-0.5">
                            {{ \Carbon\Carbon::parse($attendance->date)->isoFormat('D MMMM YYYY') }}
                        </div>
                        <div class="flex items-center gap-3 mt-2">
                            <span class="text-[9px] font-black text-blue-500 bg-blue-50 px-2 py-0.5 rounded-md uppercase tracking-widest">
                                {{ $attendance->time ? \Carbon\Carbon::parse($attendance->time)->format('H:i') : '--:--' }} WIB
                            </span>
                        </div>
                    </td>

                    {{-- 2. Kehadiran --}}
                    <td class="px-6 py-5">
                        @php
                            $binaanIds = $coach->members->pluck('id')->toArray();
                            $countBinaan = $attendance->members->whereIn('id', $binaanIds)->count();
                            $countLain = $attendance->members->whereNotIn('id', $binaanIds)->count();
                            
                            $detailData = $attendance->members->map(function($m) use ($binaanIds) {
                                return [
                                    'name' => addslashes($m->user->name),
                                    'is_binaan' => in_array($m->id, $binaanIds),
                                    'photo' => $m->user->photo_url ?? null,
                                    'category' => $m->category ?? 'Umum'
                                ];
                            });
                        @endphp
                        
                        <button type="button" 
                                @click="openDetail({{ json_encode($detailData) }}, '{{ \Carbon\Carbon::parse($attendance->date)->isoFormat('D MMM YYYY') }}')"
                                class="flex items-center justify-center gap-1.5 hover:scale-105 transition-all cursor-pointer group/btn mx-auto">
                            <div class="text-center px-2 py-1.5 bg-blue-50/50 rounded-xl border border-blue-100 group-hover/btn:bg-blue-600 group-hover/btn:border-blue-600 transition-all">
                                <p class="text-[8px] text-blue-500 font-black uppercase tracking-tighter group-hover/btn:text-blue-100">Binaan</p>
                                <p class="text-xs font-black text-blue-700 group-hover/btn:text-white">{{ $countBinaan }}</p>
                            </div>
                            <div class="text-center px-2 py-1.5 bg-slate-50 rounded-xl border border-slate-100 group-hover/btn:bg-slate-700 group-hover/btn:border-slate-700 transition-all">
                                <p class="text-[8px] text-slate-400 font-black uppercase tracking-tighter group-hover/btn:text-slate-300">Lain</p>
                                <p class="text-xs font-black text-slate-600 group-hover/btn:text-white">{{ $countLain }}</p>
                            </div>
                        </button>
                    </td>

                    {{-- 3. Catatan & Foto --}}
                    <td class="px-6 py-5">
                        <div class="flex flex-col gap-2">
                            <p class="text-xs font-medium text-slate-500 italic line-clamp-1 max-w-[150px]">
                                {{ $attendance->notes ? '"'.$attendance->notes.'"' : '-' }}
                            </p>
                            @if($attendance->photo_path)
                                <a href="{{ asset('storage/' . $attendance->photo_path) }}" target="_blank" 
                                class="inline-flex items-center gap-1.5 text-[9px] font-black text-blue-600 bg-blue-50 px-2 py-1 rounded-lg border border-blue-100 hover:bg-blue-600 hover:text-white transition-all uppercase tracking-widest w-fit">
                                    <i data-feather="image" class="w-3 h-3"></i> Foto
                                </a>
                            @endif
                        </div>
                    </td>

                    {{-- 4. Aksi --}}
                    <td class="px-6 py-5">
                        <div class="flex items-center justify-end gap-1.5">
                            <button onclick="openEditAttendanceModal({{ $attendance->id }}, '{{ $attendance->date }}', '{{ $attendance->time }}', '{{ $attendance->place }}', '{{ $attendance->notes ?? '' }}')" 
                                    class="w-9 h-9 bg-white border border-slate-100 text-slate-400 hover:text-blue-600 hover:border-blue-100 rounded-xl transition-all flex items-center justify-center shadow-sm" title="Edit Data">
                                <i data-feather="edit-3" class="w-3.5 h-3.5"></i>
                            </button>
                            <button onclick="deleteAttendance({{ $attendance->id }})" 
                                    class="w-9 h-9 bg-white border border-slate-100 text-slate-400 hover:text-rose-600 hover:border-rose-100 rounded-xl transition-all flex items-center justify-center shadow-sm" title="Hapus Riwayat">
                                <i data-feather="trash-2" class="w-3.5 h-3.5"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-24 text-center">
                        <div class="w-16 h-16 bg-slate-50 rounded-3xl flex items-center justify-center mx-auto mb-4">
                            <i data-feather="clock" class="w-8 h-8 text-slate-200"></i>
                        </div>
                        <p class="text-xs font-black text-slate-300 uppercase tracking-widest">Belum ada riwayat</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="px-6 py-4 border-t border-slate-50 bg-white">
        <button @click="showAllHistory = true" class="w-full py-3 rounded-2xl bg-slate-50 text-slate-400 hover:bg-blue-50 hover:text-blue-600 font-black text-[10px] uppercase tracking-[0.2em] transition-all flex items-center justify-center gap-2">
            <span>Seluruh Riwayat</span>
            <i data-feather="arrow-right" class="w-4 h-4"></i>
        </button>
    </div>
</div>