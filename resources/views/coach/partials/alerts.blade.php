@if (session('success') || session('error') || $errors->any())
    <div class="mb-8 fade-in" x-data="{ show: true }" x-show="show" x-transition>
        @if (session('success'))
            <div class="rounded-xl bg-gradient-to-r from-cyan-50 to-blue-50 border border-cyan-200 p-4 flex items-start gap-3">
                <div class="bg-cyan-100 p-2 rounded-full text-cyan-600"><i data-feather="check-circle" class="w-5 h-5"></i></div>
                <div class="flex-1">
                    <h3 class="text-sm font-bold text-slate-800">Berhasil</h3>
                    <p class="text-sm text-slate-600 mt-0.5">{{ session('success') }}</p>
                </div>
                <button @click="show = false" class="text-slate-400 hover:text-slate-600"><i data-feather="x" class="w-4 h-4"></i></button>
            </div>
        @endif
        
        @if (session('error') || $errors->any())
            <div class="rounded-xl bg-gradient-to-r from-red-50 to-pink-50 border border-red-200 p-4 flex items-start gap-3 mt-4">
                <div class="bg-red-100 p-2 rounded-full text-red-500"><i data-feather="alert-triangle" class="w-5 h-5"></i></div>
                <div class="flex-1">
                    <h3 class="text-sm font-bold text-slate-800">Perhatian</h3>
                    <p class="text-sm text-slate-600 mt-0.5">{{ session('error') ?? 'Terdapat kesalahan input pada form.' }}</p>
                </div>
                <button @click="show = false" class="text-slate-400 hover:text-slate-600"><i data-feather="x" class="w-4 h-4"></i></button>
            </div>
        @endif
    </div>
@endif