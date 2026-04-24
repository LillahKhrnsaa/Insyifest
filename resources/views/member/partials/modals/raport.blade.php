<div id="raportModal" class="hidden fixed inset-0 z-[9999] overflow-y-auto">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeRaportModal()"></div>
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="relative transform overflow-hidden rounded-[2.5rem] bg-white text-left shadow-2xl transition-all w-full max-w-6xl flex flex-col max-h-[90vh] border border-slate-100">
            
            {{-- Header --}}
            <div class="px-8 py-6 flex justify-between items-center bg-white border-b border-slate-50 shrink-0">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center">
                        <i data-feather="bar-chart-2" class="w-6 h-6 text-blue-600"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-extrabold text-slate-800 uppercase tracking-tight">Raport Performa Saya</h3>
                        <p class="text-xs font-medium text-slate-400 uppercase tracking-widest mt-0.5 italic">Pantau perkembangan hasil latihan Anda secara berkala</p>
                    </div>
                </div>
                <button onclick="closeRaportModal()" class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-all">
                    <i data-feather="x" class="w-5 h-5"></i>
                </button>
            </div>

            <div class="p-8 space-y-8 overflow-y-auto custom-scrollbar bg-slate-50/30">
                
                {{-- Filter Section --}}
                <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div class="md:col-span-2 space-y-2">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Kategori Gaya & Jarak</label>
                            <select id="gaya" class="w-full px-4 py-3.5 rounded-2xl bg-slate-50 border border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all text-sm font-semibold text-slate-700">
                                <option value="" selected disabled>-- Pilih Gaya Renang --</option>
                                @forelse($existingStyles as $style)
                                    <option value="{{ $style }}">{{ ucwords(str_replace('_', ' ', $style)) }}</option>
                                @empty
                                    <option value="" disabled>Belum ada data gaya</option>
                                @endforelse
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Tahun</label>
                            <input type="number" id="year" value="{{ now()->year }}" class="w-full px-4 py-3.5 rounded-2xl bg-slate-50 border border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all text-sm font-semibold text-slate-700">
                        </div>
                        <div class="flex items-end">
                            <button onclick="loadRaportData()" class="w-full py-3.5 bg-blue-600 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-blue-200 hover:bg-blue-700 transition-all flex items-center justify-center gap-2">
                                <i data-feather="refresh-cw" class="w-4 h-4"></i> Muat Data
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Charts Section --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div class="bg-white rounded-[2rem] border border-slate-100 p-8 shadow-sm group hover:shadow-md transition-all">
                        <div class="flex items-center justify-between mb-8">
                            <h4 class="font-black text-slate-800 text-[11px] uppercase tracking-[0.2em] flex items-center gap-3">
                                <span class="w-3 h-3 rounded-full bg-blue-500 shadow-sm shadow-blue-200"></span> Progres Waktu
                            </h4>
                            <span class="text-[10px] font-bold text-slate-400 bg-slate-50 px-3 py-1 rounded-full uppercase tracking-widest">Detik</span>
                        </div>
                        <div class="h-64 relative w-full"><canvas id="chartValue"></canvas></div>
                    </div>
                    <div class="bg-white rounded-[2rem] border border-slate-100 p-8 shadow-sm group hover:shadow-md transition-all">
                        <div class="flex items-center justify-between mb-8">
                            <h4 class="font-black text-slate-800 text-[11px] uppercase tracking-[0.2em] flex items-center gap-3">
                                <span class="w-3 h-3 rounded-full bg-indigo-500 shadow-sm shadow-indigo-200"></span> Volume & Intensitas
                            </h4>
                            <div class="flex gap-2">
                                <span class="text-[10px] font-bold text-indigo-400 bg-indigo-50 px-3 py-1 rounded-full uppercase tracking-widest">Meter</span>
                                <span class="text-[10px] font-bold text-slate-400 bg-slate-50 px-3 py-1 rounded-full uppercase tracking-widest">%</span>
                            </div>
                        </div>
                        <div class="h-64 relative w-full"><canvas id="chartVolume"></canvas></div>
                    </div>
                </div>

                {{-- Table Section --}}
                <div class="space-y-4">
                    <h4 class="font-black text-slate-800 text-[11px] uppercase tracking-[0.2em] flex items-center gap-3 ml-2">
                        <i data-feather="list" class="w-4 h-4 text-blue-500"></i> Detail Riwayat Bulanan
                    </h4>
                    <div class="bg-white rounded-[2rem] border border-slate-100 overflow-hidden shadow-sm">
                        <div class="max-h-80 overflow-y-auto custom-scrollbar">
                            <table id="raport-table" class="w-full text-sm">
                                <thead>
                                    <tr class="bg-slate-50 text-slate-400 uppercase text-[10px] font-black tracking-widest border-b border-slate-100">
                                        <th class="px-8 py-5 text-left">Bulan</th>
                                        <th class="px-8 py-5 text-left">Waktu</th>
                                        <th class="px-8 py-5 text-left text-indigo-500">Volume</th>
                                        <th class="px-8 py-5 text-left">Intensitas</th>
                                        <th class="px-8 py-5 text-left">Peaking</th>
                                        <th class="px-8 py-5 text-left">Catatan Pelatih</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50 font-medium text-slate-600"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>