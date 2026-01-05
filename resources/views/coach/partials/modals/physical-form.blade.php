<div id="physFormModal" class="hidden fixed inset-0 overflow-y-auto" style="z-index: 100;">
    <div class="fixed inset-0 modal-overlay transition-opacity" onclick="closePhysFormModal()"></div>
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="relative w-full max-w-md bg-white dark:bg-slate-900 rounded-[2rem] shadow-2xl flex flex-col overflow-hidden border dark:border-slate-700">
            
            <div class="bg-rose-600 px-6 py-5 flex justify-between items-center text-white">
                <div>
                    <h3 class="text-xl font-bold uppercase tracking-wide">Input Data Fisik</h3>
                    <p class="text-[10px] text-rose-100 uppercase opacity-70">Parameter Kebugaran Atlet</p>
                </div>
                <button onclick="closePhysFormModal()" class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center hover:bg-white/30"><i data-feather="x" class="w-4 h-4"></i></button>
            </div>

            <form id="physForm" class="p-6 space-y-4 max-h-[75vh] overflow-y-auto custom-scrollbar">
                @csrf
                <input type="hidden" name="member_id" id="phys_form_member_id">
                <input type="hidden" name="year" id="phys_form_year">
                
                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase mb-1">Pilih Bulan Tes</label>
                    <select name="month" id="phys_month" class="input-field w-full py-2.5 bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-xl" required>
                        @foreach(['januari','februari','maret','april','mei','juni','juli','agustus','september','oktober','november','desember'] as $m)
                            <option value="{{ $m }}">{{ ucfirst($m) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="bg-slate-50 dark:bg-slate-800/50 p-4 rounded-2xl border border-slate-200 dark:border-slate-700 space-y-3">
                    <div class="text-[10px] font-black text-rose-600 uppercase mb-1 flex items-center gap-2">
                        <i data-feather="zap" class="w-3 h-3"></i> Bleep Test (VO2 Max)
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-[10px] text-slate-400 font-bold uppercase">Level</label>
                            <input type="number" name="bleep_level" id="bleep_level" oninput="calculateBleep()" placeholder="8" class="input-field w-full py-2 bg-white dark:bg-slate-900 border-slate-200 dark:border-slate-700 rounded-xl text-sm">
                        </div>
                        <div>
                            <label class="text-[10px] text-slate-400 font-bold uppercase">Shuttle</label>
                            <input type="number" name="bleep_shuttle" id="bleep_shuttle" oninput="calculateBleep()" placeholder="5" class="input-field w-full py-2 bg-white dark:bg-slate-900 border-slate-200 dark:border-slate-700 rounded-xl text-sm">
                        </div>
                    </div>
                    <div>
                        <label class="text-[10px] text-slate-400 font-bold uppercase">Hasil Estimasi VO2 Max</label>
                        <input type="text" id="vo2max" readonly class="input-field w-full py-2 bg-rose-50 dark:bg-rose-900/20 border-none font-black text-rose-600 text-center rounded-xl">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="col-span-1">
                        <label class="text-[10px] text-slate-500 font-bold uppercase">Sprint 20m (s)</label>
                        <input type="number" step="0.01" name="sprint_20m" class="input-field w-full py-2 bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-xl">
                    </div>
                    <div class="col-span-1">
                        <label class="text-[10px] text-slate-500 font-bold uppercase">Agility (s)</label>
                        <input type="number" step="0.01" name="shuttle_run" class="input-field w-full py-2 bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-xl">
                    </div>
                    <div class="col-span-1">
                        <label class="text-[10px] text-slate-500 font-bold uppercase">Push Up (x)</label>
                        <input type="number" name="push_up" class="input-field w-full py-2 bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-xl">
                    </div>
                    <div class="col-span-1">
                        <label class="text-[10px] text-slate-500 font-bold uppercase">Sit Up (x)</label>
                        <input type="number" name="sit_up" class="input-field w-full py-2 bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-xl">
                    </div>
                </div>

                <button type="submit" class="w-full py-3.5 bg-rose-600 text-white rounded-[1.25rem] font-black text-sm uppercase tracking-widest shadow-xl shadow-rose-200 dark:shadow-none hover:bg-rose-700 hover:-translate-y-0.5 transition-all flex items-center justify-center gap-3 mt-4">
                    <i data-feather="save" class="w-4 h-4"></i> Simpan Hasil Tes
                </button>
            </form>
        </div>
    </div>
</div>