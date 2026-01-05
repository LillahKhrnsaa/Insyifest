<div id="editAttendanceModal" class="hidden fixed inset-0 z-[100] overflow-y-auto">
    <div class="fixed inset-0 modal-overlay transition-opacity" onclick="closeEditAttendanceModal()"></div>
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl flex flex-col overflow-hidden">
            
            {{-- Header --}}
            <div class="bg-yellow-500 px-6 py-4 flex justify-between items-center text-white">
                <h3 class="text-lg font-bold">Edit Absensi</h3>
                <button onclick="closeEditAttendanceModal()" class="text-white hover:text-yellow-100"><i data-feather="x" class="w-5 h-5"></i></button>
            </div>

            {{-- Form --}}
            <form id="formEditAttendance" class="p-6 space-y-4">
                <input type="hidden" id="edit_attendance_id">
                
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Tanggal</label>
                    <input type="date" id="edit_date" class="input-field w-full rounded-xl" required>
                </div>
                
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Jam</label>
                    <input type="time" id="edit_time" class="input-field w-full rounded-xl" required>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Lokasi</label>
                    <input type="text" id="edit_place" class="input-field w-full rounded-xl" required>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Catatan</label>
                    <textarea id="edit_notes" rows="3" class="input-field w-full rounded-xl"></textarea>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-3 bg-yellow-500 hover:bg-yellow-600 text-white rounded-xl font-bold transition-all shadow-lg shadow-yellow-200">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>