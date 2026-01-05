<div x-show="showModal" x-cloak class="relative z-[9999]">
    <div x-show="showModal" 
        x-transition.opacity 
        class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"
        @click="showModal = false"></div>

    {{-- Modal Container --}}
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div x-show="showModal" 
            x-transition.scale.origin.center
            class="relative w-full max-w-md bg-white rounded-[2.5rem] shadow-2xl flex flex-col max-h-[90vh] overflow-hidden">
            
            {{-- Header (Sticky) --}}
            <div class="bg-gradient-to-r from-cyan-600 to-blue-700 px-6 py-5 flex items-center justify-between shrink-0 shadow-sm">
                <div>
                    <h3 class="text-xl font-bold text-white leading-tight">Form Absensi</h3>
                    <p class="text-xs text-cyan-100 mt-0.5">Catat kehadiran latihan</p>
                </div>
                <button @click="showModal = false" class="w-9 h-9 rounded-full bg-white/10 flex items-center justify-center text-white hover:bg-white/20 transition-colors">
                    <i data-feather="x" class="w-5 h-5"></i>
                </button>
            </div>

            {{-- Body (Scrollable) --}}
            <div class="p-6 overflow-y-auto custom-scrollbar flex-1 bg-white">
                <form method="POST" action="{{ route('attendance.store') }}" enctype="multipart/form-data" id="form-attendance">
                    @csrf
                    
                    <div class="space-y-6">
                        {{-- 1. Pilih Jadwal --}}
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Jadwal (Opsional)</label>
                            <select name="schedule_id" x-model="selectedSchedule" @change="autoFill()" 
                                    class="input-field w-full border-slate-200 focus:border-cyan-500 rounded-2xl">
                                <option value="">-- Luar Jadwal / Tambahan --</option>
                                <template x-for="s in schedules" :key="s.id">
                                    <option :value="s.id" x-text="s.label"></option>
                                </template>
                            </select>
                        </div>

                        {{-- 2. Tanggal & Jam --}}
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Tanggal</label>
                                <input type="date" name="date" value="{{ date('Y-m-d') }}" required 
                                    class="input-field w-full rounded-2xl border-slate-200">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Jam</label>
                                <input type="time" name="time" x-ref="timeInput" required 
                                    class="input-field w-full rounded-2xl border-slate-200">
                            </div>
                        </div>

                        {{-- 3. Lokasi --}}
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Lokasi</label>
                            <input type="text" name="place" x-ref="placeInput" required 
                                    placeholder="Lokasi kolam..." 
                                    class="input-field w-full rounded-2xl border-slate-200">
                        </div>

                        {{-- 4. Daftar Atlet --}}
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-widest mb-3">Daftar Atlet</label>
                            
                            {{-- Search --}}
                            <div class="relative mb-3">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400">
                                    <i data-feather="search" class="w-4 h-4"></i>
                                </span>
                                <input type="text" x-model="searchTerm" placeholder="Cari nama atlet..." 
                                    class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-sm focus:ring-2 focus:ring-cyan-500 focus:bg-white transition-all">
                            </div>

                            {{-- List Checkbox --}}
                            <div class="bg-slate-50 rounded-[1.5rem] p-2 max-h-60 overflow-y-auto border border-slate-100 custom-scrollbar">
                                @php
                                    $mergedMembers = $activeRegularMembers->concat($allOtherMembers);
                                @endphp

                                @forelse($mergedMembers as $member)
                                    @php $safeName = addslashes(strtolower($member->user->name)); @endphp

                                    <label x-show="'{{ $safeName }}'.includes(searchTerm.toLowerCase())"
                                        class="flex items-center p-3 rounded-2xl hover:bg-white cursor-pointer transition-all border border-transparent hover:border-slate-100 mb-1 last:mb-0 group shadow-sm hover:shadow-md">
                                        <input type="checkbox" name="members[]" value="{{ $member->id }}" 
                                            class="w-5 h-5 rounded-lg border-slate-300 text-cyan-600 focus:ring-cyan-500">
                                        
                                        <div class="ml-3 flex items-center gap-3 truncate">
                                            <div class="w-8 h-8 rounded-full bg-slate-200 overflow-hidden flex-shrink-0">
                                                @if ($member->user->photo_url)
                                                    <img src="{{ $member->user->photo_url }}" class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center text-slate-400 bg-slate-200">
                                                        <i data-feather="user" class="w-4 h-4"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="truncate">
                                                <span class="text-sm font-bold text-slate-700 block truncate group-hover:text-cyan-600 transition-colors">{{ $member->user->name }}</span>
                                                <span class="text-[10px] {{ $activeRegularMembers->contains($member->id) ? 'text-blue-600 bg-blue-50' : 'text-slate-400 bg-slate-100' }} px-2 py-0.5 rounded-full font-bold uppercase tracking-tighter">
                                                    {{ $activeRegularMembers->contains($member->id) ? 'Binaan Saya' : 'Lainnya' }}
                                                </span>
                                            </div>
                                        </div>
                                    </label>
                                @empty
                                    <p class="text-xs text-slate-400 text-center py-6">Tidak ada atlet aktif.</p>
                                @endforelse
                            </div>
                        </div>

                        {{-- 5. Catatan & Foto --}}
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Catatan</label>
                                <textarea name="notes" rows="2" class="input-field w-full rounded-2xl text-sm" placeholder="Opsional..."></textarea>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Dokumentasi</label>
                                <input type="file" name="photo" id="photo-upload" class="hidden" accept="image/*">
                                <label for="photo-upload" class="flex items-center justify-center gap-3 border-2 border-dashed border-slate-200 rounded-2xl p-4 text-center hover:bg-slate-50 transition-colors cursor-pointer group">
                                    <i data-feather="camera" class="w-5 h-5 text-slate-400 group-hover:text-cyan-600"></i>
                                    <span class="text-xs text-slate-500 font-medium">Upload Foto</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Footer (Sticky) --}}
            <div class="p-6 border-t border-slate-50 bg-white shrink-0">
                <div class="flex gap-3">
                    <button type="button" @click="showModal = false" 
                            class="flex-1 px-4 py-3.5 bg-slate-100 text-slate-600 font-bold rounded-2xl hover:bg-slate-200 transition-colors text-sm">
                        Batal
                    </button>
                    <button type="submit" form="form-attendance"
                            class="flex-[2] px-4 py-3.5 bg-gradient-to-r from-cyan-600 to-blue-600 text-white font-bold rounded-2xl flex items-center justify-center gap-2 shadow-lg shadow-cyan-200 hover:brightness-110 transition-all text-sm">
                        <i data-feather="save" class="w-4 h-4"></i>
                        Simpan Absensi
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>