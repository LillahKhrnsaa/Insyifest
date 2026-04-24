<div id="raportFormModal" class="hidden fixed inset-0 z-[9999] overflow-y-auto">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" id="closeFormModalBtn"></div>
    
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="relative w-full max-w-lg transform overflow-hidden rounded-[2.5rem] bg-white text-left shadow-2xl transition-all flex flex-col border border-slate-100">
            
            {{-- Header --}}
            <div class="px-8 py-6 flex justify-between items-center bg-white border-b border-slate-50 shrink-0">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center">
                        <i data-feather="bar-chart-2" class="w-6 h-6 text-blue-600"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-extrabold text-slate-800 uppercase tracking-tight" id="formModalTitle">Tambah Data Raport</h3>
                        <p class="text-xs font-medium text-slate-400 uppercase tracking-widest mt-0.5">Input performa atlet</p>
                    </div>
                </div>
                <button type="button" id="cancelFormBtn" class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-all">
                    <i data-feather="x" class="w-5 h-5"></i>
                </button>
            </div>

            <div class="p-8 overflow-y-auto custom-scrollbar max-h-[70vh]">
                <form id="raportForm" class="space-y-6">
                    <input type="hidden" name="id" id="raport_id">
                    <input type="hidden" name="member_id" id="form_member_id">
                    <input type="hidden" name="gaya" id="form_gaya">
                    <input type="hidden" name="year" id="form_year">

                    <div id="monthFieldWrapper" class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Bulan</label>
                        <select name="month" id="month" class="w-full px-4 py-3.5 rounded-2xl bg-slate-50 border border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all text-sm font-semibold text-slate-700" required>
                            <option value="">-- Pilih Bulan --</option>
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Waktu (Detik)</label>
                        <div class="relative group">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 group-focus-within:text-blue-500 transition-colors">
                                <i data-feather="clock" class="w-4 h-4"></i>
                            </span>
                            <input type="number" step="0.01" name="value" id="value" placeholder="Contoh: 30.50" required
                                   class="w-full pl-11 pr-4 py-3.5 rounded-2xl bg-slate-50 border border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all text-sm font-semibold text-slate-700">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Volume (m)</label>
                            <input type="number" name="volume" id="volume" placeholder="Total meter" required
                                   class="w-full px-4 py-3.5 rounded-2xl bg-slate-50 border border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all text-sm font-semibold text-slate-700">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Intensitas (%)</label>
                            <input type="number" name="intensity" id="intensity" placeholder="0-100" required
                                   class="w-full px-4 py-3.5 rounded-2xl bg-slate-50 border border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all text-sm font-semibold text-slate-700">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Peaking</label>
                        <input type="number" name="peaking" id="peaking" placeholder="Nilai Peaking"
                               class="w-full px-4 py-3.5 rounded-2xl bg-slate-50 border border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all text-sm font-semibold text-slate-700">
                    </div>

                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Coach Penilai</label>
                        <div class="relative group mb-2">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 group-focus-within:text-blue-500 transition-colors">
                                <i data-feather="search" class="w-4 h-4"></i>
                            </span>
                            <input type="text" id="coach_search" placeholder="Cari nama coach..."
                                   class="w-full pl-11 pr-4 py-3.5 rounded-2xl bg-slate-50 border border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all text-sm font-semibold text-slate-700">
                        </div>
                        <select name="coach_id" id="coach_id" class="w-full px-4 py-3.5 rounded-2xl bg-slate-50 border border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all text-sm font-semibold text-slate-700" required>
                            <option value="">-- Pilih Coach --</option>
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Catatan Tambahan</label>
                        <textarea name="note" id="note" rows="3" placeholder="Tulis evaluasi singkat..."
                                  class="w-full px-4 py-3.5 rounded-2xl bg-slate-50 border border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all text-sm font-semibold text-slate-700"></textarea>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full py-4 bg-blue-600 text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl shadow-blue-200 hover:bg-blue-700 hover:-translate-y-0.5 transition-all flex items-center justify-center gap-3">
                            <i data-feather="save" class="w-4 h-4"></i> Simpan Data Raport
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>