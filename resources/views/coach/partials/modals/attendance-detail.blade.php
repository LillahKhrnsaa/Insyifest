<div x-show="showDetailModal" x-cloak class="relative z-[9999]">
    <div x-show="showDetailModal" x-transition.opacity class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showDetailModal = false"></div>

    <div class="fixed inset-0 z-[9999] flex items-center justify-center p-4">
        <div x-show="showDetailModal" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             class="relative w-full max-w-md bg-white rounded-[2.5rem] shadow-2xl flex flex-col max-h-[80vh] overflow-hidden border border-slate-100">
            
            {{-- Header --}}
            <div class="px-8 py-6 flex justify-between items-center bg-white border-b border-slate-50 shrink-0">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center">
                        <i data-feather="users" class="w-6 h-6 text-blue-600"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-extrabold text-slate-800 uppercase tracking-tight" x-text="detailTitle"></h3>
                        <p class="text-[10px] font-medium text-slate-400 uppercase tracking-widest mt-0.5">Daftar Kehadiran Atlet</p>
                    </div>
                </div>
                <button @click="showDetailModal = false" class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-all">
                    <i data-feather="x" class="w-5 h-5"></i>
                </button>
            </div>

            <div class="p-8 overflow-y-auto custom-scrollbar flex-1 bg-slate-50/30 space-y-4">
                <template x-for="(m, index) in detailMembers" :key="index">
                    <div class="flex items-center justify-between p-4 rounded-3xl border border-slate-100 bg-white shadow-sm hover:shadow-md transition-all group">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-blue-50 overflow-hidden shrink-0 border border-blue-100">
                                <template x-if="m.photo">
                                    <img :src="m.photo" class="w-full h-full object-cover">
                                </template>
                                <template x-if="!m.photo">
                                    <div class="w-full h-full flex items-center justify-center text-blue-200">
                                        <i data-feather="user" class="w-5 h-5"></i>
                                    </div>
                                </template>
                            </div>
                            <div>
                                <p class="text-sm font-black text-slate-700 group-hover:text-blue-600 transition-colors leading-tight" x-text="m.name"></p>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter mt-0.5" x-text="m.category"></p>
                            </div>
                        </div>
                        <span :class="m.is_binaan ? 'bg-blue-50 text-blue-600 border-blue-100' : 'bg-slate-100 text-slate-500 border-slate-200'" 
                            class="text-[9px] font-black px-3 py-1.5 rounded-full uppercase tracking-widest border shadow-sm"
                            x-text="m.is_binaan ? 'Binaan' : 'Lainnya'">
                        </span>
                    </div>
                </template>

                <template x-if="detailMembers.length === 0">
                    <div class="py-12 flex flex-col items-center justify-center text-slate-400">
                        <i data-feather="user-x" class="w-12 h-12 mb-4 opacity-10"></i>
                        <p class="text-xs font-black uppercase tracking-[0.2em] text-slate-300">Data tidak ditemukan</p>
                    </div>
                </template>
            </div>

            <div class="p-8 bg-white border-t border-slate-50 shrink-0">
                <button @click="showDetailModal = false" class="w-full py-4 bg-slate-100 text-slate-600 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-slate-200 transition-all">Tutup Jendela</button>
            </div>
        </div>
    </div>
</div>