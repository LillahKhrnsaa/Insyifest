<div id="physicalModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="fixed inset-0 modal-overlay transition-opacity" onclick="closePhysicalModal()"></div>
    <div class="flex min-h-screen items-center justify-center px-4 py-10">
        <div class="relative w-full max-w-5xl bg-white rounded-3xl shadow-2xl flex flex-col max-h-[90vh] overflow-hidden border">
            <div class="bg-gradient-to-r from-pink-500 to-rose-600 px-6 py-5 flex justify-between items-center shrink-0">
                <div>
                    <h3 class="text-xl font-bold text-white uppercase tracking-wider">Hasil Tes Fisik</h3>
                    <p class="text-sm text-pink-100 mt-0.5">Evaluasi kondisi fisik Anda</p>
                </div>
                <button onclick="closePhysicalModal()" class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center text-white hover:rotate-90 transition-all">
                    <i data-feather="x"></i>
                </button>
            </div>

            <div class="p-6 overflow-y-auto custom-scrollbar space-y-6">
                <div class="flex flex-wrap gap-4 bg-slate-50 p-4 rounded-2xl border border-slate-200 items-end">
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Tahun Evaluasi</label>
                        <input type="number" id="phys_year" value="{{ now()->year }}" class="input-field w-full py-2 bg-white rounded-xl">
                    </div>
                    <button onclick="loadPhysicalData()" class="px-6 py-2 bg-white border border-slate-200 text-slate-700 rounded-xl font-bold hover:bg-slate-100 transition-all flex items-center gap-2">
                        <i data-feather="refresh-cw" class="w-4 h-4"></i> Muat
                    </button>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm flex flex-col items-center">
                        <h4 class="font-black text-slate-400 text-[10px] uppercase mb-4 tracking-[0.2em]">Spider Chart Analysis</h4>
                        <div class="w-full aspect-square"><canvas id="chartRadar"></canvas></div>
                    </div>
                    <div class="lg:col-span-2 bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
                        <div class="overflow-x-auto">
                            <table id="phys-table" class="w-full text-sm text-left">
                                <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-[10px]">
                                    <tr>
                                        <th class="px-6 py-4">Bulan</th>
                                        <th class="px-6 py-4 text-rose-600">VO2 Max</th>
                                        <th class="px-6 py-4">Sprint</th>
                                        <th class="px-6 py-4">P.Up/S.Up</th>
                                        <th class="px-6 py-4">Agility</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>