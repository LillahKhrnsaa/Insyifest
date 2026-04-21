<div id="raportFormModal" class="hidden fixed inset-0 overflow-y-auto" style="z-index: 100;">
    <div class="fixed inset-0 modal-overlay transition-opacity" id="closeFormModalBtn"></div>
    
    <div class="flex min-h-screen items-center justify-center px-4 py-10 text-center sm:px-6">
        <div class="relative w-full max-w-md transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all flex flex-col">
            
            <div class="bg-gradient-to-r from-cyan-500 to-blue-600 px-6 py-5 flex justify-between items-center shrink-0">
                <div>
                    <h3 class="text-xl font-bold text-white" id="formModalTitle">Tambah Data Raport</h3>
                    <p class="text-sm text-cyan-100 mt-0.5">Input data performa atlet</p>
                </div>
                <button type="button" id="cancelFormBtn" class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center text-white hover:bg-white/20 transition-colors">
                    <i data-feather="x" class="w-5 h-5"></i>
                </button>
            </div>

            <div class="p-6 overflow-y-auto custom-scrollbar max-h-[80vh]">
                <form id="raportForm" class="space-y-5">
                    <input type="hidden" name="id" id="raport_id">
                    <input type="hidden" name="member_id" id="form_member_id">
                    <input type="hidden" name="gaya" id="form_gaya">
                    <input type="hidden" name="year" id="form_year">

                    <div id="monthFieldWrapper">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Bulan</label>
                        <select name="month" id="month" class="input-field w-full" required>
                            <option value="">-- Pilih Bulan --</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Waktu (Detik)</label>
                        <div class="relative">
                            <input type="number" step="0.01" name="value" id="value" class="input-field w-full pl-10" placeholder="Contoh: 30.50" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Volume (m)</label>
                            <input type="number" name="volume" id="volume" class="input-field w-full" placeholder="Total meter" required>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Intensitas (%)</label>
                            <input type="number" name="intensity" id="intensity" class="input-field w-full" placeholder="0-100" required>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Peaking</label>
                        <input type="number" name="peaking" id="peaking" class="input-field w-full" placeholder="Nilai Peaking">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Coach Penilai</label>
                        <div class="relative mb-2 group">
                            <input type="text" id="coach_search" 
                                   class="w-full pl-9 pr-4 py-2 text-xs border border-slate-200 rounded-xl bg-slate-50 focus:bg-white focus:ring-2 focus:ring-cyan-500/20 focus:border-cyan-500 transition-all outline-none" 
                                   placeholder="Cari nama coach...">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 group-focus-within:text-cyan-500 transition-colors">
                                <i data-feather="search" class="w-3.5 h-3.5"></i>
                            </div>
                        </div>
                        <select name="coach_id" id="coach_id" class="input-field w-full bg-white" required>
                            <option value="">-- Pilih Coach --</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Catatan (Opsional)</label>
                        <textarea name="note" id="note" rows="3" class="input-field w-full" placeholder="Catatan tambahan..."></textarea>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full btn-primary py-3 flex items-center justify-center gap-2 font-bold shadow-lg hover:shadow-cyan-500/30">
                            <i data-feather="save" class="w-4 h-4"></i> Simpan Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>