<div class="mb-6 bg-white rounded-xl border border-slate-200 overflow-hidden card-hover">
    {{-- Header Card --}}
    <div class="px-5 py-4 border-b border-slate-100 bg-slate-50">
        <div class="flex items-center justify-between">
            <h3 class="font-bold text-slate-800 flex items-center gap-2">
                <i data-feather="users" class="w-5 h-5 text-cyan-600"></i>
                Daftar Atlet Binaan
            </h3>
            <span class="text-xs text-slate-500 bg-slate-100 px-2 py-1 rounded">{{ $coach->members->count() }} atlet</span>
        </div>
    </div>
                
    {{-- Body List --}}
    <div class="overflow-hidden">
        <div class="divide-y divide-slate-100">
            @php
                $sortedMembers = $coach->members->sortBy(function($member) {
                    return $member->user->name;
                })->take(6); 
            @endphp

            @forelse($sortedMembers as $member)
            <div class="px-5 py-4 hover:bg-slate-50 transition-colors">
                <div class="flex items-center justify-between">
                    {{-- Sisi Kiri --}}
                    <div class="flex items-center gap-4">
                        <span class="text-slate-400 font-bold text-sm min-w-[20px]">{{ $loop->iteration }}.</span>
                        <div class="w-10 h-10 rounded-full bg-slate-100 overflow-hidden flex-shrink-0 border border-slate-200">
                            @if ($member->user->photo_url)
                                <img src="{{ $member->user->photo_url }}" class="w-full h-full object-cover" alt="Atlet Photo">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <i data-feather="user" class="w-5 h-5 text-slate-400"></i>
                                </div>
                            @endif
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-800 text-sm">{{ $member->user->name }}</h4>
                            <p class="text-xs text-slate-500">{{ $member->user->email }}</p> 
                        </div>
                    </div>

                    {{-- Sisi Kanan --}}
                    <div class="flex items-center gap-3">
                        <span class="status-badge {{ $member->status == 'AKTIF' ? 'status-active' : 'status-inactive' }}">
                            {{ $member->status }}
                        </span>
                        <button onclick="openRaportModal({{ $member->id }}, '{{ $member->user->name }}')" 
                                class="p-2 hover:bg-slate-100 rounded-lg transition-colors"
                                title="Lihat Raport">
                            <i data-feather="file-text" class="w-4 h-4 text-slate-500"></i>
                        </button>
                        <button onclick="openPhysicalModal({{ $member->id }}, '{{ $member->user->name }}')" 
                            class="p-2 bg-pink-50 text-pink-600 hover:bg-pink-100 rounded-lg transition-colors"
                            title="Tes Fisik">
                            <i data-feather="activity" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>
            </div>
            @empty
            <div class="px-5 py-8 text-center text-slate-400">
                <i data-feather="user-x" class="w-8 h-8 mx-auto mb-2 opacity-50"></i>
                <p>Tidak ada atlet terdaftar</p>
            </div>
            @endforelse
        </div>
    </div>
    
    {{-- Footer Card --}}
    <div class="px-5 py-3 border-t border-slate-100 bg-slate-50">
        <button @click="showAllMembers = true" class="w-full text-sm font-bold text-cyan-600 hover:text-cyan-700 flex items-center justify-center gap-1 transition-colors">
            <span>Lihat Semua Atlet</span>
            <i data-feather="chevron-right" class="w-4 h-4"></i>
        </button>
    </div>
</div>