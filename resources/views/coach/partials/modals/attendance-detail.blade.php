<div x-show="showDetailModal" x-cloak class="relative z-[99999]">
    <div x-show="showDetailModal" x-transition.opacity class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showDetailModal = false"></div>

    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div x-show="showDetailModal" x-transition.scale 
            class="relative w-full max-w-sm bg-white rounded-[2rem] shadow-2xl flex flex-col max-h-[80vh] overflow-hidden">
            
            <div class="p-5 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                <h3 class="font-bold text-slate-800 text-sm" x-text="detailTitle"></h3>
                <button @click="showDetailModal = false" class="text-slate-400 hover:text-slate-600">
                    <i data-feather="x" class="w-5 h-5"></i>
                </button>
            </div>

            <div class="p-4 overflow-y-auto custom-scrollbar flex-1">
                <div class="space-y-3">
                    <template x-for="(m, index) in detailMembers" :key="index">
                        <div class="flex items-center justify-between p-3 rounded-2xl border border-slate-50 bg-slate-50/50">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-white border border-slate-200 overflow-hidden shrink-0">
                                    <template x-if="m.photo">
                                        <img :src="m.photo" class="w-full h-full object-cover">
                                    </template>
                                    <template x-if="!m.photo">
                                        <div class="w-full h-full flex items-center justify-center text-slate-300">
                                            <i data-feather="user" class="w-4 h-4"></i>
                                        </div>
                                    </template>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-700" x-text="m.name"></p>
                                    <p class="text-[10px] text-slate-400" x-text="m.category"></p>
                                </div>
                            </div>
                            <span :class="m.is_binaan ? 'bg-blue-100 text-blue-600' : 'bg-slate-200 text-slate-600'" 
                                class="text-[9px] font-bold px-2 py-1 rounded-full uppercase"
                                x-text="m.is_binaan ? 'Binaan' : 'Lainnya'">
                            </span>
                        </div>
                    </template>
                </div>
            </div>

            <div class="p-4 bg-slate-50 text-center">
                <button @click="showDetailModal = false" class="text-xs font-bold text-blue-600">Tutup</button>
            </div>
        </div>
    </div>
</div>