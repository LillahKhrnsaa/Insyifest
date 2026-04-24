<div x-show="showModal" x-cloak class="relative z-[9999]">
    <div x-show="showModal" 
        x-transition.opacity 
        class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"
        @click="showModal = false"></div>

    {{-- Modal Container --}}
    <div class="fixed inset-0 z-[9999] flex items-center justify-center p-4">
        <div x-show="showModal" 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95 translate-y-4"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            class="relative w-full max-w-lg bg-white rounded-[2.5rem] shadow-2xl flex flex-col max-h-[90vh] overflow-hidden border border-slate-100">
            
            {{-- Header --}}
            <div class="px-8 py-6 flex justify-between items-center bg-white border-b border-slate-50 shrink-0">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center">
                        <i data-feather="check-square" class="w-6 h-6 text-blue-600"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-extrabold text-slate-800 uppercase tracking-tight">Form Absensi</h3>
                        <p class="text-xs font-medium text-slate-400 uppercase tracking-widest mt-0.5">Catat Kehadiran Latihan</p>
                    </div>
                </div>
                <button @click="showModal = false" class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-all">
                    <i data-feather="x" class="w-5 h-5"></i>
                </button>
            </div>

            {{-- Body --}}
            <div class="p-8 overflow-y-auto custom-scrollbar flex-1 bg-white">
                <form method="POST" action="{{ route('attendance.store') }}" enctype="multipart/form-data" id="form-attendance">
                    @csrf
                    
                    <div class="space-y-6">
                        {{-- 1. Pilih Jadwal --}}
                        <div class="space-y-2">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Jadwal Latihan (Opsional)</label>
                            <select name="schedule_id" x-model="selectedSchedule" @change="autoFill()" 
                                    class="w-full px-4 py-3.5 rounded-2xl bg-slate-50 border border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all text-sm font-semibold text-slate-700">
                                <option value="">-- Luar Jadwal / Tambahan --</option>
                                <template x-for="s in schedules" :key="s.id">
                                    <option :value="s.id" x-text="s.label"></option>
                                </template>
                            </select>
                        </div>

                        {{-- 2. Tanggal & Jam --}}
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Tanggal</label>
                                <input type="date" name="date" value="{{ date('Y-m-d') }}" required 
                                    class="w-full px-4 py-3.5 rounded-2xl bg-slate-50 border border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all text-sm font-semibold text-slate-700">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Jam</label>
                                <input type="time" name="time" x-ref="timeInput" required 
                                    class="w-full px-4 py-3.5 rounded-2xl bg-slate-50 border border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all text-sm font-semibold text-slate-700">
                            </div>
                        </div>

                        {{-- 3. Lokasi --}}
                        <div class="space-y-2">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Lokasi Latihan</label>
                            <div class="relative group">
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 group-focus-within:text-blue-500 transition-colors">
                                    <i data-feather="map-pin" class="w-4 h-4"></i>
                                </span>
                                <input type="text" name="place" x-ref="placeInput" required placeholder="Nama kolam atau tempat..." 
                                       class="w-full pl-11 pr-4 py-3.5 rounded-2xl bg-slate-50 border border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all text-sm font-semibold text-slate-700 placeholder:text-slate-300">
                            </div>
                        </div>

                        {{-- 4. Daftar Atlet --}}
                        <div class="space-y-3">
                            <label class="text-[11px] font-black text-slate-700 uppercase tracking-widest ml-1">Daftar Atlet Hadir</label>
                            
                            {{-- Search --}}
                            <div class="relative group">
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 group-focus-within:text-blue-500 transition-colors">
                                    <i data-feather="search" class="w-4 h-4"></i>
                                </span>
                                <input type="text" x-model="searchTerm" placeholder="Cari nama atlet..." 
                                    class="w-full pl-11 pr-4 py-3.5 rounded-2xl bg-slate-50 border border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all text-sm font-semibold text-slate-700 placeholder:text-slate-300">
                            </div>

                            {{-- List Checkbox --}}
                            <div class="bg-slate-50/50 rounded-3xl p-3 max-h-60 overflow-y-auto border border-slate-100 custom-scrollbar space-y-1">
                                @php
                                    $mergedMembers = $activeRegularMembers->concat($allOtherMembers);
                                @endphp

                                @forelse($mergedMembers as $member)
                                    @php $safeName = addslashes(strtolower($member->user->name)); @endphp

                                    <label x-show="'{{ $safeName }}'.includes(searchTerm.toLowerCase())"
                                        class="flex items-center p-3 rounded-2xl hover:bg-white cursor-pointer transition-all border-2 border-transparent hover:border-blue-100 group shadow-sm hover:shadow-md bg-white/50">
                                        <div class="relative flex items-center">
                                            <input type="checkbox" name="members[]" value="{{ $member->id }}" 
                                                class="w-6 h-6 rounded-lg border-slate-200 text-blue-600 focus:ring-blue-500/20 transition-all">
                                        </div>
                                        
                                        <div class="ml-4 flex items-center gap-3 truncate flex-1">
                                            <div class="w-10 h-10 rounded-xl bg-blue-50 overflow-hidden flex-shrink-0 border border-blue-100 shadow-sm">
                                                @if ($member->user->photo_url)
                                                    <img src="{{ $member->user->photo_url }}" class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center text-blue-300">
                                                        <i data-feather="user" class="w-5 h-5"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="truncate">
                                                <span class="text-sm font-extrabold text-slate-700 block truncate group-hover:text-blue-600 transition-colors leading-tight">{{ $member->user->name }}</span>
                                                <div class="flex items-center gap-1.5 mt-0.5">
                                                    <span class="text-[9px] {{ $activeRegularMembers->contains($member->id) ? 'text-blue-600 bg-blue-50' : 'text-slate-400 bg-slate-100' }} px-2 py-0.5 rounded-lg font-black uppercase tracking-widest">
                                                        {{ $activeRegularMembers->contains($member->id) ? 'Binaan Saya' : 'Lainnya' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                @empty
                                    <div class="py-12 flex flex-col items-center justify-center text-slate-400">
                                        <i data-feather="users" class="w-8 h-8 mb-2 opacity-20"></i>
                                        <p class="text-[10px] font-bold uppercase tracking-widest">Tidak ada atlet aktif</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        {{-- 5. Catatan & Foto --}}
                        <div class="space-y-4">
                            <div class="space-y-2">
                                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Catatan</label>
                                <textarea name="notes" rows="2" class="w-full px-4 py-3.5 rounded-2xl bg-slate-50 border border-slate-100 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all text-sm font-semibold text-slate-700" placeholder="Catatan tambahan..."></textarea>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Dokumentasi Foto</label>
                                <input type="file" name="photo" id="photo-upload" class="hidden" accept="image/*">
                                <label for="photo-upload" class="flex items-center justify-center gap-4 border-2 border-dashed border-slate-100 rounded-2xl p-6 text-center hover:bg-blue-50/30 hover:border-blue-200 transition-all cursor-pointer group bg-slate-50/50">
                                    <div class="w-10 h-10 rounded-xl bg-white shadow-sm flex items-center justify-center text-slate-400 group-hover:text-blue-600 group-hover:scale-110 transition-all">
                                        <i data-feather="camera" class="w-5 h-5"></i>
                                    </div>
                                    <div class="text-left">
                                        <span class="text-xs text-slate-600 font-bold block">Pilih File Foto</span>
                                        <span class="text-[10px] text-slate-400 font-medium">JPG, PNG maks 2MB</span>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Footer --}}
            <div class="p-8 bg-slate-50/50 border-t border-slate-100 shrink-0">
                <div class="flex gap-4">
                    <button type="button" @click="showModal = false" 
                            class="flex-1 px-6 py-4 bg-white border border-slate-200 text-slate-600 font-black rounded-2xl hover:bg-slate-100 transition-all text-[10px] uppercase tracking-widest">
                        Batal
                    </button>
                    <button type="submit" form="form-attendance"
                            class="flex-[2] px-6 py-4 bg-blue-600 text-white font-black rounded-2xl flex items-center justify-center gap-3 shadow-xl shadow-blue-200 hover:bg-blue-700 hover:-translate-y-0.5 transition-all text-[10px] uppercase tracking-widest">
                        <i data-feather="save" class="w-4 h-4"></i>
                        Simpan Absensi
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>