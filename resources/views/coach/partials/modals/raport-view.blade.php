<div id="raportModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="fixed inset-0 modal-overlay transition-opacity" onclick="closeRaportModal()"></div>
    <div class="flex min-h-screen items-center justify-center px-4 py-10 text-center sm:px-6">
        <div class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-xl transition-all sm:w-full sm:max-w-6xl flex flex-col max-h-[85vh]">
            
            <div class="bg-gradient-to-r from-cyan-500 to-blue-600 px-5 py-4 flex justify-between items-center shrink-0">
                <div>
                    <h3 class="text-lg font-bold text-white">Raport Performa Atlet</h3>
                    <p class="text-xs text-cyan-100 mt-0.5">Atlet: <span id="memberName" class="font-bold"></span></p>
                </div>
                <button onclick="closeRaportModal()" class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center text-white hover:bg-white/20 transition-colors focus:outline-none">
                    <i data-feather="x" class="w-5 h-5"></i>
                </button>
            </div>

            <div class="p-5 space-y-6 overflow-y-auto custom-scrollbar">
                
                {{-- Filter --}}
                <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Kategori Gaya</label>
                            <select id="gaya" class="input-field w-full py-2 text-sm bg-white">
                                <option value="" selected disabled>-- Pilih Gaya Renang --</option>
                                @forelse($existingStyles as $style)
                                    <option value="{{ $style }}">{{ ucwords(str_replace('_', ' ', $style)) }}</option>
                                @empty
                                    <option value="" disabled>Belum ada data gaya</option>
                                @endforelse
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Tahun</label>
                            <input type="number" id="year" value="{{ now()->year }}" class="input-field w-full py-2 text-sm bg-white">
                        </div>
                        <div class="flex items-end">
                            <button onclick="loadRaportData()" class="w-full btn-primary py-2 flex items-center justify-center gap-2 text-sm shadow-sm hover:shadow-md">
                                <i data-feather="refresh-cw" class="w-4 h-4"></i> Muat Data
                            </button>
                        </div>
                    </div>

                    {{-- Tombol Tambah --}}
                    <div class="border-t border-slate-500 pt-4 flex justify-end">
                        <button onclick="openCreateForm()" class="px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-black rounded-lg text-sm font-bold transition-colors flex items-center gap-2 shadow-sm">
                            <i data-feather="plus-circle" class="w-4 h-4"></i> Tambah Data Baru
                        </button>
                    </div>
                </div>

                {{-- Charts --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
                        <h4 class="font-bold text-slate-800 mb-4 text-xs uppercase tracking-wide flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-cyan-500"></span> Grafik Waktu (Detik)
                        </h4>
                        <div class="h-56 relative w-full"><canvas id="chartValue"></canvas></div>
                    </div>
                    <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
                        <h4 class="font-bold text-slate-800 mb-4 text-xs uppercase tracking-wide flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Volume & Intensitas
                        </h4>
                        <div class="h-56 relative w-full"><canvas id="chartVolume"></canvas></div>
                    </div>
                </div>

                {{-- Tabel --}}
                <div class="flex flex-col pb-4">
                    <div class="flex justify-between items-center mb-3">
                        <h4 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                            <i data-feather="list" class="w-4 h-4 text-slate-400"></i> Detail Data Bulanan
                        </h4>
                    </div>
                    <div class="border border-slate-200 rounded-xl overflow-hidden shadow-sm">
                        <div class="max-h-64 overflow-y-auto">
                            <table id="raport-table" class="w-full text-sm">
                                <thead class="bg-slate-50 text-slate-500 uppercase text-xs font-bold sticky top-0 z-10 shadow-sm">
                                    <tr>
                                        <th class="px-5 py-3 text-left bg-slate-50">Bulan</th>
                                        <th class="px-5 py-3 text-left bg-slate-50">Waktu</th>
                                        <th class="px-5 py-3 text-left bg-slate-50">Volume</th>
                                        <th class="px-5 py-3 text-left bg-slate-50">Intensitas</th>
                                        <th class="px-5 py-3 text-left bg-slate-50">Peaking</th>
                                        <th class="px-5 py-3 text-center bg-slate-50">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 bg-white"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>