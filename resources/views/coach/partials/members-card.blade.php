<div class="mb-6 bg-white rounded-3xl border border-slate-100 overflow-hidden shadow-sm hover:shadow-md transition-all duration-300">
    {{-- Header Card --}}
    <div class="px-6 py-5 border-b border-blue-100 relative overflow-hidden" style="background: linear-gradient(to right, #eff6ff, #ffffff);">
        <div class="absolute top-0 right-0 w-32 h-32 bg-blue-100 rounded-full blur-2xl -mr-10 -mt-10 opacity-50"></div>
        <div class="flex items-center justify-between relative z-10">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-blue-500 flex items-center justify-center shadow-inner">
                    <i data-feather="users" class="w-6 h-6 text-white"></i>
                </div>
                <div>
                    <h3 class="font-bold text-slate-800 text-lg">Atlet Binaan</h3>
                    <p class="text-sm font-medium text-slate-500 mt-0.5">{{ $coach->members->count() }} Anggota Terdaftar</p>
                </div>
            </div>
            <button @click="showMemberModal = true; memberModalMode = 'create'; memberForm = { name: '', email: '', password: '', phone: '', gender: '', training_package_id: '', status: 'AKTIF' }" 
                    class="px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-100 transition-all flex items-center gap-2 text-xs font-black uppercase tracking-widest">
                <i data-feather="plus" class="w-4 h-4"></i>
                <span>Tambah</span>
            </button>
        </div>
    </div>
                
    {{-- Body List --}}
    <div class="overflow-x-auto">
        <div class="min-w-full divide-y divide-slate-50">
            @php
                $sortedMembers = $coach->members->sortBy(function($member) {
                    return $member->user->name;
                })->take(6); 
            @endphp

            @forelse($sortedMembers as $member)
            <div class="px-6 py-4 hover:bg-blue-50/30 transition-all group flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-slate-50 overflow-hidden border border-slate-100 shadow-sm group-hover:scale-110 transition-all duration-500">
                        @if ($member->user->photo_url)
                            <img src="{{ $member->user->photo_url }}" class="w-full h-full object-cover" alt="Atlet Photo">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-slate-50 text-slate-300">
                                <i data-feather="user" class="w-5 h-5"></i>
                            </div>
                        @endif
                    </div>
                    <div>
                        <h4 class="font-black text-slate-700 group-hover:text-blue-600 transition-colors text-sm">{{ $member->user->name }}</h4>
                        <p class="text-xs font-medium text-slate-400 italic">{{ $member->user->email }}</p> 
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest border shadow-sm
                        {{ $member->status == 'AKTIF' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-slate-100 text-slate-500 border-slate-200' }}">
                        {{ $member->status }}
                    </span>
                    <div class="flex gap-1 ml-2">
                        <button onclick="openRaportModal({{ $member->id }}, '{{ $member->user->name }}')" 
                                class="w-9 h-9 bg-white border border-slate-100 text-slate-400 hover:text-blue-600 hover:border-blue-100 hover:shadow-lg hover:shadow-blue-100 rounded-xl transition-all flex items-center justify-center"
                                title="Lihat Raport">
                            <i data-feather="bar-chart-2" class="w-4 h-4"></i>
                        </button>
                        <button onclick="openPhysicalModal({{ $member->id }}, '{{ $member->user->name }}')" 
                                class="w-9 h-9 bg-white border border-slate-100 text-slate-400 hover:text-indigo-600 hover:border-indigo-100 hover:shadow-lg hover:shadow-indigo-100 rounded-xl transition-all flex items-center justify-center"
                                title="Tes Fisik">
                            <i data-feather="activity" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>
            </div>
            @empty
            <div class="px-6 py-12 text-center">
                <div class="w-16 h-16 bg-slate-50 rounded-3xl flex items-center justify-center mx-auto mb-4">
                    <i data-feather="users" class="w-8 h-8 text-slate-200"></i>
                </div>
                <p class="text-xs font-black text-slate-300 uppercase tracking-widest">Belum ada atlet</p>
            </div>
            @endforelse
        </div>
    </div>
    
    {{-- Footer Card --}}
    <div class="px-6 py-4 border-t border-slate-50 bg-white">
        <button @click="showAllMembers = true" class="w-full py-3 rounded-2xl bg-slate-50 text-slate-400 hover:bg-blue-50 hover:text-blue-600 font-black text-[10px] uppercase tracking-[0.2em] transition-all flex items-center justify-center gap-2">
            <span>Seluruh Atlet</span>
            <i data-feather="arrow-right" class="w-4 h-4"></i>
        </button>
    </div>
</div>