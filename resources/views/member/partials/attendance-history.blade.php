<div class="bg-white rounded-xl border border-slate-200 overflow-hidden card-hover">
    <div class="px-5 py-4 border-b border-slate-100 bg-slate-50">
        <h3 class="font-bold text-slate-800 flex items-center gap-2">
            <i data-feather="clock" class="w-5 h-5 text-blue-600"></i> Riwayat Kehadiran
        </h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="table-header text-left text-xs text-slate-500 font-bold uppercase tracking-wider">
                <tr>
                    <th class="px-5 py-3">Tanggal</th>
                    <th class="px-5 py-3">Lokasi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($attendances->take(5) as $attendance)
                    <tr class="table-row hover:bg-slate-50 transition-colors">
                        <td class="px-5 py-4 font-bold text-slate-800 text-sm">
                            {{ \Carbon\Carbon::parse($attendance->date)->isoFormat('D MMM Y') }}
                        </td>
                        <td class="px-5 py-4 text-slate-600 text-sm">
                            {{ $attendance->place ?? '-' }}
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="2" class="px-5 py-8 text-center text-slate-400">Belum ada riwayat</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>