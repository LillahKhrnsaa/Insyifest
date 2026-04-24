<!-- Modal Input/Edit Hasil Tes Fisik -->
<div id="physFormModal" class="hidden fixed inset-0 z-[9999] overflow-y-auto">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closePhysFormModal()"></div>
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="relative w-full max-w-lg bg-white rounded-[2.5rem] shadow-2xl flex flex-col overflow-hidden border border-slate-100 animate-in fade-in zoom-in duration-300">
            
            {{-- Header --}}
            <div class="px-8 py-6 flex justify-between items-center bg-white border-b border-slate-50">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center">
                        <i data-feather="activity" class="w-6 h-6 text-blue-600"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-extrabold text-slate-800 uppercase tracking-tight">Input Data Fisik</h3>
                        <p class="text-xs font-medium text-slate-400 uppercase tracking-widest mt-0.5">Parameter Kebugaran Atlet</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <button onclick="openConfigModal()" class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-all" title="Atur Variabel">
                        <i data-feather="settings" class="w-5 h-5"></i>
                    </button>
                    <button onclick="closePhysFormModal()" class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-all">
                        <i data-feather="x" class="w-5 h-5"></i>
                    </button>
                </div>
            </div>

            <form id="physForm" class="p-8 space-y-6 max-h-[70vh] overflow-y-auto custom-scrollbar">
                @csrf
                <input type="hidden" name="id" id="phys_id">
                <input type="hidden" name="member_id" id="phys_form_member_id">
                <input type="hidden" name="year" id="phys_form_year">
                
                <div class="space-y-2">
                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Pilih Bulan Tes</label>
                    <select name="month" id="phys_month" class="w-full px-4 py-3.5 rounded-2xl bg-slate-50 border border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all text-sm font-semibold text-slate-700" required>
                        @foreach(['januari','februari','maret','april','mei','juni','juli','agustus','september','oktober','november','desember'] as $m)
                            <option value="{{ $m }}">{{ ucfirst($m) }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Container untuk Variabel Dinamis -->
                <div id="dynamic-variables-container" class="space-y-6">
                    <!-- Akan diisi via JavaScript -->
                    <div class="flex flex-col items-center justify-center py-12 text-slate-400">
                        <div class="w-12 h-12 rounded-full border-4 border-blue-100 border-t-blue-600 animate-spin mb-4"></div>
                        <p class="text-xs uppercase font-black tracking-widest text-slate-300">Memuat Variabel...</p>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Catatan Tambahan</label>
                    <textarea name="note" id="phys_note" rows="2" placeholder="Tulis catatan jika diperlukan..."
                              class="w-full px-4 py-3.5 rounded-2xl bg-slate-50 border border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all text-sm font-semibold text-slate-700"></textarea>
                </div>
            </form>

            <div class="p-8 bg-slate-50/50 border-t border-slate-100 shrink-0">
                <button type="submit" form="physForm" class="w-full py-4 bg-blue-600 text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl shadow-blue-200 hover:bg-blue-700 hover:-translate-y-0.5 transition-all flex items-center justify-center gap-3">
                    <i data-feather="save" class="w-4 h-4"></i> Simpan Hasil Tes
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfigurasi Variabel Fisik -->
<div id="configPhysModal" class="hidden fixed inset-0 z-[99999] overflow-y-auto">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeConfigModal()"></div>
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="relative w-full max-w-lg bg-white rounded-[2.5rem] shadow-2xl flex flex-col overflow-hidden border border-slate-100">
            
            <div class="px-8 py-6 flex justify-between items-center bg-white border-b border-slate-50">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-slate-100 flex items-center justify-center text-slate-500">
                        <i data-feather="settings" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-extrabold text-slate-800 uppercase tracking-tight">Pengaturan Variabel</h3>
                        <p class="text-xs font-medium text-slate-400 uppercase tracking-widest mt-0.5">Parameter & Target Goals</p>
                    </div>
                </div>
                <button onclick="closeConfigModal()" class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 hover:text-blue-600 transition-all">
                    <i data-feather="x" class="w-5 h-5"></i>
                </button>
            </div>

            <div class="p-8 space-y-6 max-h-[60vh] overflow-y-auto custom-scrollbar">
                <div id="config-variables-list" class="space-y-4">
                    <!-- List variabel akan diisi di sini -->
                </div>

                <button onclick="addVariableRow()" class="w-full py-4 border-2 border-dashed border-slate-100 text-slate-400 hover:text-blue-600 hover:border-blue-200 rounded-2xl flex items-center justify-center gap-2 transition-all font-black text-[10px] uppercase tracking-widest bg-slate-50/50">
                    <i data-feather="plus-circle" class="w-4 h-4"></i> Tambah Variabel Baru
                </button>
            </div>

            <div class="p-8 bg-slate-50 border-t border-slate-100 flex gap-4">
                <button onclick="closeConfigModal()" class="flex-1 py-4 bg-white border border-slate-200 text-slate-600 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-slate-100 transition-all">Batal</button>
                <button onclick="savePhysicalVariables()" class="flex-1 py-4 bg-blue-600 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-xl shadow-blue-200 hover:bg-blue-700 transition-all">Simpan Perubahan</button>
            </div>
        </div>
    </div>
</div>