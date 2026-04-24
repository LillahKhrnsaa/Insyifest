<!-- Modal View Riwayat Tes Fisik -->
<div id="physicalModal" class="hidden fixed inset-0 z-[9999] overflow-y-auto">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closePhysicalModal()"></div>
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="relative w-full max-w-7xl bg-white rounded-[2.5rem] shadow-2xl flex flex-col max-h-[90vh] overflow-hidden border border-slate-100">
            
            {{-- Header --}}
            <div class="px-8 py-6 flex justify-between items-center bg-white border-b border-slate-50 shrink-0">
                <div class="flex items-center gap-6">
                    <div class="w-16 h-16 rounded-[1.5rem] bg-blue-600 flex items-center justify-center shadow-lg shadow-blue-200">
                        <i data-feather="zap" class="text-white w-8 h-8"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-black text-slate-800 uppercase tracking-tighter leading-none">Physique Analysis</h3>
                        <div class="flex items-center gap-2 mt-2">
                            <span class="px-2 py-0.5 bg-blue-50 text-blue-600 text-[10px] font-black rounded-md tracking-widest uppercase">ATHLETE</span>
                            <p class="text-sm text-slate-500 font-bold tracking-wide" id="physMemberName"></p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <button onclick="closePhysicalModal()" class="w-12 h-12 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-all border border-slate-100">
                        <i data-feather="x" class="w-6 h-6"></i>
                    </button>
                </div>
            </div>

            <div class="p-8 overflow-y-auto custom-scrollbar space-y-10 bg-slate-50/30">
                
                {{-- Action Bar --}}
                <div class="flex flex-col md:flex-row gap-6 items-center justify-between bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm">
                    <div class="flex gap-4 w-full md:w-auto">
                        <div class="space-y-2">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Tahun</label>
                            <input type="number" id="phys_year" value="{{ now()->year }}" class="w-32 px-4 py-3 rounded-2xl bg-slate-50 border border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all text-sm font-bold text-slate-700">
                        </div>
                        <div class="space-y-2 flex-1">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Bulan Fokus</label>
                            <select id="phys_month" class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all text-sm font-bold text-slate-700">
                                <option value="">SEMUA DATA</option>
                                @foreach(['januari','februari','maret','april','mei','juni','juli','agustus','september','oktober','november','desember'] as $m)
                                    <option value="{{ $m }}">{{ strtoupper($m) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 w-full md:w-auto">
                        <button onclick="loadPhysicalData()" class="flex-1 px-6 py-3.5 bg-slate-100 text-slate-600 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-slate-200 transition-all flex items-center justify-center gap-2">
                            <i data-feather="refresh-cw" class="w-4 h-4"></i> Muat
                        </button>
                        <button onclick="openConfigModal()" class="flex-1 px-6 py-3.5 bg-white border-2 border-blue-100 text-blue-600 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-blue-50 transition-all flex items-center justify-center gap-2">
                            <i data-feather="settings" class="w-4 h-4"></i> Atur
                        </button>
                        <button onclick="openPhysForm()" class="flex-[2] px-8 py-3.5 bg-blue-600 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-xl shadow-blue-200 hover:bg-blue-700 transition-all flex items-center justify-center gap-2">
                            <i data-feather="plus" class="w-4 h-4"></i> Input Hasil
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-1 xl:grid-cols-12 gap-10">
                    {{-- Radar Chart Section --}}
                    <div class="xl:col-span-5 space-y-8">
                        <div class="bg-white p-10 rounded-[2.5rem] border border-slate-100 shadow-sm relative overflow-hidden group">
                            <div class="relative">
                                <h4 class="font-black text-blue-600 text-[11px] uppercase tracking-[0.3em] mb-10 flex items-center gap-3">
                                    <span class="w-10 h-[2px] bg-blue-600"></span> Performance Radar
                                </h4>
                                <div class="w-full aspect-square flex items-center justify-center">
                                    <canvas id="chartRadar"></canvas>
                                </div>
                            </div>
                        </div>

                        <div class="bg-blue-600 p-8 rounded-[2rem] text-white shadow-xl shadow-blue-100">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center">
                                    <i data-feather="info" class="w-4 h-4 text-white"></i>
                                </div>
                                <h5 class="text-[11px] font-black uppercase tracking-widest">Quick Insight</h5>
                            </div>
                            <p class="text-sm leading-relaxed text-blue-50/80 font-medium">Grafik radar menunjukkan perbandingan hasil tes fisik atlet terhadap **Goal Target** yang telah ditentukan. Semakin lebar area grafik, semakin mendekati target performa.</p>
                        </div>
                    </div>

                    {{-- Data Table Section --}}
                    <div class="xl:col-span-7 bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden flex flex-col">
                        <div class="px-10 py-8 border-b border-slate-50 flex justify-between items-center bg-slate-50/30">
                            <div>
                                <h4 class="font-black text-slate-800 text-lg tracking-tight">Timeline Evaluasi</h4>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Riwayat pengukuran per bulan</p>
                            </div>
                        </div>
                        
                        <div class="flex-1 overflow-x-auto custom-scrollbar">
                            <table id="phys-table" class="w-full text-sm text-left">
                                <thead class="sticky top-0 bg-white/95 backdrop-blur-sm z-20">
                                    <tr id="phys-table-header" class="text-slate-400 font-black uppercase text-[10px] tracking-widest border-b border-slate-100">
                                        <th class="px-10 py-6">Bulan</th>
                                        <th class="px-10 py-6">Parameter Fisik</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50 font-medium text-slate-600">
                                    {{-- Dynamic Rows --}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>