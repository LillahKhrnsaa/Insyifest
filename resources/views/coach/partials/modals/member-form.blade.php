<div x-show="showMemberModal" x-cloak class="relative z-[9999]">
    <div x-show="showMemberModal" x-transition.opacity class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
    <div class="fixed inset-0 z-[9999] flex items-center justify-center p-4">
        <div x-show="showMemberModal" @click.away="showMemberModal = false" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             class="relative w-full max-w-lg bg-white rounded-[2.5rem] shadow-2xl flex flex-col overflow-hidden border border-slate-100">
            
            {{-- Header --}}
            <div class="px-8 py-6 flex justify-between items-center bg-white border-b border-slate-50 shrink-0">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center">
                        <i data-feather="user-plus" class="w-6 h-6 text-blue-600" x-show="memberModalMode === 'create'"></i>
                        <i data-feather="edit-3" class="w-6 h-6 text-blue-600" x-show="memberModalMode === 'edit'"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-extrabold text-slate-800" x-text="memberModalMode === 'create' ? 'Tambah Atlet Baru' : 'Edit Data Atlet'"></h3>
                        <p class="text-xs font-medium text-slate-400 uppercase tracking-widest mt-0.5">Manajemen Anggota</p>
                    </div>
                </div>
                <button @click="showMemberModal = false" class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-all">
                    <i data-feather="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form @submit.prevent="submitMemberForm" class="p-8 space-y-6 overflow-y-auto max-h-[70vh] custom-scrollbar">
                <div class="space-y-2">
                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Nama Lengkap</label>
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 group-focus-within:text-blue-500 transition-colors">
                            <i data-feather="user" class="w-4 h-4"></i>
                        </span>
                        <input type="text" x-model="memberForm.name" required placeholder="Masukkan nama lengkap..."
                               class="w-full pl-11 pr-4 py-3.5 rounded-2xl bg-slate-50 border border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all text-sm font-semibold text-slate-700 placeholder:text-slate-300">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Email</label>
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 group-focus-within:text-blue-500 transition-colors">
                            <i data-feather="mail" class="w-4 h-4"></i>
                        </span>
                        <input type="email" x-model="memberForm.email" required placeholder="email@contoh.com"
                               class="w-full pl-11 pr-4 py-3.5 rounded-2xl bg-slate-50 border border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all text-sm font-semibold text-slate-700 placeholder:text-slate-300">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Nomor HP</label>
                        <input type="text" x-model="memberForm.phone" required placeholder="08..."
                               class="w-full px-4 py-3.5 rounded-2xl bg-slate-50 border border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all text-sm font-semibold text-slate-700">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Jenis Kelamin</label>
                        <select x-model="memberForm.gender" required
                                class="w-full px-4 py-3.5 rounded-2xl bg-slate-50 border border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all text-sm font-semibold text-slate-700">
                            <option value="">Pilih</option>
                            <option value="MALE">Laki-laki</option>
                            <option value="FEMALE">Perempuan</option>
                        </select>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1" x-text="memberModalMode === 'create' ? 'Password' : 'Password (Kosongkan jika tidak diubah)'"></label>
                    <input type="password" x-model="memberForm.password" :required="memberModalMode === 'create'"
                           class="w-full px-4 py-3.5 rounded-2xl bg-slate-50 border border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all text-sm font-semibold text-slate-700">
                </div>

                <div class="space-y-2">
                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Paket Latihan</label>
                    <select x-model="memberForm.training_package_id" required
                            class="w-full px-4 py-3.5 rounded-2xl bg-slate-50 border border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all text-sm font-semibold text-slate-700">
                        <option value="">Pilih Paket</option>
                        @foreach($packages as $package)
                            <option value="{{ $package->id }}">{{ $package->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Status Keanggotaan</label>
                    <div class="grid grid-cols-2 gap-3 mt-1">
                        <label class="relative flex items-center justify-center p-3 rounded-2xl border-2 cursor-pointer transition-all"
                               :class="memberForm.status === 'AKTIF' ? 'border-blue-500 bg-blue-50/50 text-blue-700' : 'border-slate-100 bg-slate-50 text-slate-400 hover:border-slate-200'">
                            <input type="radio" x-model="memberForm.status" value="AKTIF" class="sr-only">
                            <span class="text-xs font-bold uppercase">Aktif</span>
                        </label>
                        <label class="relative flex items-center justify-center p-3 rounded-2xl border-2 cursor-pointer transition-all"
                               :class="memberForm.status === 'TIDAK_AKTIF' ? 'border-blue-500 bg-blue-50/50 text-blue-700' : 'border-slate-100 bg-slate-50 text-slate-400 hover:border-slate-200'">
                            <input type="radio" x-model="memberForm.status" value="TIDAK_AKTIF" class="sr-only">
                            <span class="text-xs font-bold uppercase">Tidak Aktif</span>
                        </label>
                    </div>
                </div>
            </form>

            <div class="p-8 bg-slate-50/50 border-t border-slate-100 flex gap-3 shrink-0">
                <button type="button" @click="showMemberModal = false"
                        class="flex-1 px-6 py-4 rounded-2xl bg-white border border-slate-200 font-bold text-slate-600 hover:bg-slate-100 transition-all text-[10px] uppercase tracking-widest">
                    Batal
                </button>
                <button type="submit" @click="submitMemberForm"
                        class="flex-[2] px-6 py-4 rounded-2xl bg-blue-600 font-bold text-white hover:bg-blue-700 shadow-xl shadow-blue-200 transition-all flex items-center justify-center gap-2 text-[10px] uppercase tracking-widest">
                    <i data-feather="save" class="w-4 h-4"></i>
                    <span x-text="memberModalMode === 'create' ? 'Simpan Atlet' : 'Update Atlet'"></span>
                </button>
            </div>
        </div>
    </div>
</div>
