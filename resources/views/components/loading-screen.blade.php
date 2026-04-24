<div 
    x-data="{ pageLoading: true }" 
    x-init="setTimeout(() => pageLoading = false, 800)"
    class="fixed inset-0 z-[9999] items-center justify-center bg-white transition-opacity duration-500"
    :class="pageLoading ? 'flex opacity-100' : 'hidden opacity-0'"
    wire:loading.delay.class="!flex !opacity-100"
>
    <div class="text-center px-4">
        {{-- Illustration Loading --}}
        <div class="relative w-56 h-56 mx-auto mb-8 flex items-center justify-center">
            <div class="absolute inset-0 bg-blue-50 rounded-[3rem] rotate-12 animate-pulse"></div>
            <div class="absolute inset-0 bg-blue-100/50 rounded-[3rem] -rotate-12 animate-pulse" style="animation-delay: 0.2s"></div>
            
            <img src="{{ asset('images/logocsc.png') }}" alt="CSC" class="w-24 h-24 object-contain relative z-10 filter grayscale brightness-110">
            
            {{-- Spinner Ring --}}
            <div class="absolute inset-4 border-[6px] border-slate-50 rounded-[2.5rem]"></div>
            <div class="absolute inset-4 border-[6px] border-blue-600 rounded-[2.5rem] border-t-transparent border-l-transparent animate-spin duration-1000"></div>
        </div>

        {{-- Text Info --}}
        <h3 class="text-2xl font-black text-slate-800 mb-2 uppercase tracking-tighter">Cikampek Swimming Club</h3>
        <p class="text-slate-400 text-[10px] font-black uppercase tracking-[0.3em] max-w-xs mx-auto italic">Menyiapkan Informasi Terbaik Untuk Anda</p>
        
        {{-- Progress Bar Mini --}}
        <div class="w-64 h-1.5 bg-slate-100 rounded-full mx-auto mt-10 overflow-hidden shadow-inner">
            <div class="h-full bg-blue-600 rounded-full animate-[loading_1.5s_ease-in-out_infinite] shadow-[0_0_15px_rgba(37,99,235,0.5)]"></div>
        </div>
    </div>
</div>

<style>
    @keyframes loading {
        0% { width: 0%; transform: translateX(-100%); }
        50% { width: 60%; transform: translateX(50%); }
        100% { width: 0%; transform: translateX(150%); }
    }
</style>
