<div class="bg-white rounded-xl border border-slate-200 overflow-hidden card-hover flex flex-col h-full">
    {{-- Header Card dengan Filter Bulan --}}
    <div class="px-5 py-4 border-b border-slate-100 bg-slate-50 shrink-0">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <h3 class="font-bold text-slate-800 flex items-center gap-2">
                <i data-feather="clock" class="w-5 h-5 text-purple-600"></i>
                Riwayat Absensi
            </h3>
            <div class="flex items-center gap-2">
                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">Bulan:</label>
                <input type="month" x-model="filterMonth" 
                    class="text-xs border-slate-200 rounded-lg px-2 py-1 focus:ring-purple-500 focus:border-purple-500 bg-white shadow-sm">
            </div>
        </div>
    </div>
                
    <div class="overflow-x-auto flex-1 w-full custom-scrollbar">
        <table class="w-full min-w-[600px]">
            <thead>
                <tr class="text-left text-[10px] text-slate-400 font-bold uppercase tracking-widest border-b border-slate-50">
                    <th class="px-4 py-4">Waktu & Lokasi</th>
                    <th class="px-4 py-4 text-center">Kehadiran</th>
                    <th class="px-4 py-4">Catatan & Foto</th>
                    <th class="px-4 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($attendances as $attendance)
                <tr x-show="filterMonth === '' || '{{ \Carbon\Carbon::parse($attendance->date)->format('Y-m') }}' === filterMonth"
                    x-transition.opacity
                    class="hover:bg-slate-50/50 transition-colors">
                    
                    {{-- 1. Waktu --}}
                    <td class="px-4 py-4">
                        <div class="font-bold text-slate-800 text-sm">
                            {{ \Carbon\Carbon::parse($attendance->date)->isoFormat('D MMM YYYY') }}
                        </div>
                        <div class="flex flex-col gap-1 mt-1">
                            <span class="text-[11px] text-blue-600 font-medium flex items-center gap-1">
                                <i data-feather="clock" class="w-3 h-3 text-slate-400"></i>
                                {{ $attendance->time ? \Carbon\Carbon::parse($attendance->time)->format('H:i') : '--:--' }} WIB
                            </span>
                            <span class="text-[11px] text-slate-500 flex items-center gap-1">
                                <i data-feather="map-pin" class="w-3 h-3 text-slate-400"></i>
                                {{ Str::limit($attendance->place ?? '-', 20) }}
                            </span>
                        </div>
                    </td>

                    {{-- 2. Kehadiran --}}
                    <td class="px-4 py-4 text-center">
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
                                class="flex items-center justify-center gap-2 hover:scale-105 transition-transform cursor-pointer group mx-auto">
                            <div class="text-center px-2 py-1 bg-blue-50 rounded-lg border border-blue-100 group-hover:bg-blue-100 transition-colors">
                                <p class="text-[9px] text-blue-500 font-bold uppercase tracking-tighter">Binaan</p>
                                <p class="text-sm font-black text-blue-700">{{ $countBinaan }}</p>
                            </div>
                            <div class="text-center px-2 py-1 bg-slate-50 rounded-lg border border-slate-100 group-hover:bg-slate-200 transition-colors">
                                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-tighter">Lainnya</p>
                                <p class="text-sm font-black text-slate-600">{{ $countLain }}</p>
                            </div>
                        </button>
                    </td>

                    {{-- 3. Catatan & Foto --}}
                    <td class="px-4 py-4">
                        <div class="max-w-[150px]">
                            <p class="text-xs text-slate-600 italic line-clamp-2 mb-2" title="{{ $attendance->notes }}">
                                {{ $attendance->notes ? '"'.$attendance->notes.'"' : '-' }}
                            </p>
                            @if($attendance->photo_path)
                                <a href="{{ asset('storage/' . $attendance->photo_path) }}" target="_blank" 
                                class="inline-flex items-center gap-1.5 text-[10px] font-bold text-cyan-600 hover:text-cyan-700 bg-cyan-50 px-2 py-1 rounded-md transition-colors">
                                    <i data-feather="image" class="w-3 h-3"></i> Lihat Foto
                                </a>
                            @else
                                <span class="text-[10px] text-slate-300 italic">Tanpa Foto</span>
                            @endif
                        </div>
                    </td>

                    {{-- 4. Aksi --}}
                    <td class="px-4 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <button onclick="openEditAttendanceModal({{ $attendance->id }}, '{{ $attendance->date }}', '{{ $attendance->time }}', '{{ $attendance->place }}', '{{ $attendance->notes ?? '' }}')" 
                                    class="p-2 bg-yellow-50 text-yellow-600 hover:bg-yellow-100 rounded-lg transition-colors" title="Edit Data">
                                <i data-feather="edit-2" class="w-4 h-4"></i>
                            </button>
                            <button onclick="deleteAttendance({{ $attendance->id }})" 
                                    class="p-2 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg transition-colors" title="Hapus Riwayat">
                                <i data-feather="trash-2" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-5 py-20 text-center text-slate-400 font-medium">
                        Belum ada riwayat absensi.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="px-5 py-3 border-t border-slate-100 bg-slate-50">
        <button @click="showAllHistory = true" class="w-full text-sm font-bold text-purple-600 hover:text-purple-700 flex items-center justify-center gap-1 transition-colors">
            <span>Lihat Semua Riwayat</span>
            <i data-feather="chevron-right" class="w-4 h-4"></i>
        </button>
    </div>
</div>