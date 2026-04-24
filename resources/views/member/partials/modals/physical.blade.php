<div id="physicalModal" class="hidden fixed inset-0 z-[9999] overflow-y-auto">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closePhysicalModal()"></div>
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="relative w-full max-w-6xl bg-white rounded-[2.5rem] shadow-2xl flex flex-col max-h-[90vh] overflow-hidden border border-slate-100">
            
            {{-- Header --}}
            <div class="px-8 py-6 flex justify-between items-center bg-white border-b border-slate-50 shrink-0">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-50 flex items-center justify-center">
                        <i data-feather="zap" class="w-6 h-6 text-indigo-600"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-extrabold text-slate-800 uppercase tracking-tight">Hasil Tes Fisik</h3>
                        <p class="text-xs font-medium text-slate-400 uppercase tracking-widest mt-0.5 italic">Monitor kebugaran dan kekuatan fisik Anda secara objektif</p>
                    </div>
                </div>
                <button onclick="closePhysicalModal()" class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-all">
                    <i data-feather="x" class="w-5 h-5"></i>
                </button>
            </div>

            <div class="p-8 overflow-y-auto custom-scrollbar space-y-8 bg-slate-50/30">
                
                {{-- Filter Section --}}
                <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end">
                        <div class="space-y-2">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Tahun Evaluasi</label>
                            <input type="number" id="phys_year" value="{{ now()->year }}" class="w-full px-4 py-3.5 rounded-2xl bg-slate-50 border border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all text-sm font-semibold text-slate-700">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Fokus Bulan (Radar)</label>
                            <select id="phys_month" class="w-full px-4 py-3.5 rounded-2xl bg-slate-50 border border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all text-sm font-semibold text-slate-700">
                                <option value="">Semua Data / Terbaru</option>
                                @foreach(['januari','februari','maret','april','mei','juni','juli','agustus','september','oktober','november','desember'] as $m)
                                    <option value="{{ $m }}">{{ strtoupper($m) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button onclick="loadPhysicalData()" class="py-3.5 bg-blue-600 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-blue-100 hover:bg-blue-700 transition-all flex items-center justify-center gap-2">
                            <i data-feather="refresh-cw" class="w-4 h-4"></i> Muat Ulang Data
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                    {{-- Radar Chart --}}
                    <div class="lg:col-span-5 bg-white p-10 rounded-[2.5rem] border border-slate-100 shadow-sm relative overflow-hidden group">
                        <div class="relative">
                            <h4 class="font-black text-indigo-600 text-[11px] uppercase tracking-[0.3em] mb-10 flex items-center gap-3">
                                <span class="w-10 h-[2px] bg-indigo-600"></span> Performance Radar
                            </h4>
                            <div class="w-full aspect-square flex items-center justify-center">
                                <canvas id="chartRadar"></canvas>
                            </div>
                        </div>
                    </div>

                    {{-- Data Table --}}
                    <div class="lg:col-span-7 bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden flex flex-col">
                        <div class="px-8 py-6 border-b border-slate-50 flex justify-between items-center bg-slate-50/30">
                            <div>
                                <h4 class="font-black text-slate-800 text-base tracking-tight">Timeline Kondisi Fisik</h4>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Perbandingan hasil antar periode</p>
                            </div>
                        </div>
                        
                        <div class="flex-1 overflow-x-auto custom-scrollbar">
                            <table id="phys-table" class="w-full text-sm text-left">
                                <thead class="sticky top-0 bg-white/95 backdrop-blur-sm z-20">
                                    <tr class="text-slate-400 font-black uppercase text-[10px] tracking-widest border-b border-slate-50 bg-slate-50/30">
                                        <th class="px-8 py-5">Bulan</th>
                                        <th class="px-8 py-5">Data Parameter</th>
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