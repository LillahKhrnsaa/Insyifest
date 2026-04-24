<div class="bg-white rounded-[2rem] border border-slate-100 overflow-hidden shadow-sm hover:shadow-md transition-all duration-300">
    <div class="px-8 py-6 border-b border-slate-50 bg-white">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center">
                <i data-feather="clock" class="w-5 h-5 text-blue-600"></i>
            </div>
            <div>
                <h3 class="font-black text-slate-800 text-sm uppercase tracking-tight">Riwayat Kehadiran</h3>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Sesi Terakhir</p>
            </div>
        </div>
    </div>
    <div class="overflow-x-auto custom-scrollbar">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-[10px] text-slate-400 font-black uppercase tracking-[0.2em] border-b border-slate-50 bg-slate-50/30">
                    <th class="px-8 py-5">Tanggal</th>
                    <th class="px-8 py-5">Lokasi Latihan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($attendances->take(5) as $attendance)
                    <tr class="hover:bg-blue-50/30 transition-all group">
                        <td class="px-8 py-6 font-black text-slate-700 group-hover:text-blue-600 transition-colors leading-tight">
                            {{ \Carbon\Carbon::parse($attendance->date)->isoFormat('dddd, D MMM Y') }}
                        </td>
                        <td class="px-8 py-6 text-slate-400 font-bold italic">
                            <div class="flex items-center gap-2">
                                <i data-feather="map-pin" class="w-3.5 h-3.5 opacity-50"></i>
                                {{ $attendance->place ?? '-' }}
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="px-8 py-16 text-center">
                            <div class="w-16 h-16 bg-slate-50 rounded-3xl flex items-center justify-center mx-auto mb-4">
                                <i data-feather="user-x" class="w-8 h-8 text-slate-200"></i>
                            </div>
                            <p class="text-[10px] font-black text-slate-300 uppercase tracking-widest">Belum ada riwayat hadir</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>