<div id="editAttendanceModal" class="hidden fixed inset-0 z-[9999] overflow-y-auto">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeEditAttendanceModal()"></div>
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="relative w-full max-w-md bg-white rounded-[2.5rem] shadow-2xl flex flex-col overflow-hidden border border-slate-100">
            
            {{-- Header --}}
            <div class="px-8 py-6 flex justify-between items-center bg-white border-b border-slate-50 shrink-0">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center">
                        <i data-feather="edit-2" class="w-6 h-6 text-blue-600"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-extrabold text-slate-800 uppercase tracking-tight">Edit Absensi</h3>
                        <p class="text-xs font-medium text-slate-400 uppercase tracking-widest mt-0.5">Koreksi Data Kehadiran</p>
                    </div>
                </div>
                <button onclick="closeEditAttendanceModal()" class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-all">
                    <i data-feather="x" class="w-5 h-5"></i>
                </button>
            </div>

            {{-- Form --}}
            <form id="formEditAttendance" class="p-8 space-y-6">
                <input type="hidden" id="edit_attendance_id">
                
                <div class="space-y-2">
                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Tanggal Latihan</label>
                    <input type="date" id="edit_date" required
                           class="w-full px-4 py-3.5 rounded-2xl bg-slate-50 border border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all text-sm font-semibold text-slate-700">
                </div>
                
                <div class="space-y-2">
                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Jam Mulai</label>
                    <input type="time" id="edit_time" required
                           class="w-full px-4 py-3.5 rounded-2xl bg-slate-50 border border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all text-sm font-semibold text-slate-700">
                </div>

                <div class="space-y-2">
                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Lokasi Latihan</label>
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 group-focus-within:text-blue-500 transition-colors">
                            <i data-feather="map-pin" class="w-4 h-4"></i>
                        </span>
                        <input type="text" id="edit_place" required placeholder="Nama kolam..."
                               class="w-full pl-11 pr-4 py-3.5 rounded-2xl bg-slate-50 border border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all text-sm font-semibold text-slate-700">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Catatan Koreksi</label>
                    <textarea id="edit_notes" rows="3" placeholder="Alasan perubahan data..."
                              class="w-full px-4 py-3.5 rounded-2xl bg-slate-50 border border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all text-sm font-semibold text-slate-700"></textarea>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full py-4 bg-blue-600 text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl shadow-blue-200 hover:bg-blue-700 hover:-translate-y-0.5 transition-all flex items-center justify-center gap-3">
                        <i data-feather="save" class="w-4 h-4"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>