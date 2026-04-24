@if(config('app.demo_mode', false))
<div class="fixed top-0 right-0 z-[10000] pointer-events-none overflow-hidden w-48 h-48">
    <div class="absolute bg-red-600 text-white text-xs font-black px-12 py-2 shadow-[0_0_20px_rgba(220,38,38,0.3)] transform rotate-45 top-[45px] -right-[55px] border-y-2 border-red-700 uppercase tracking-[0.2em] text-center w-[250px]">
        DEMO
    </div>
</div>
@endif
