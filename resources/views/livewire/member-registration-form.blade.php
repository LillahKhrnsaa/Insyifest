<div class="min-h-screen py-10 px-4 sm:px-6 lg:px-8 relative overflow-hidden"
    style="background: linear-gradient(135deg, #eff6ff 0%, #f0f9ff 50%, #f5f3ff 100%);">

    {{-- Floating background orbs --}}
    <div class="absolute inset-0 pointer-events-none overflow-hidden z-0">
        <div class="absolute top-[-10%] left-[-5%] w-80 h-80 rounded-full opacity-40"
            style="background: radial-gradient(circle, #bfdbfe, transparent); filter: blur(60px);"></div>
        <div class="absolute top-[40%] right-[-8%] w-96 h-96 rounded-full opacity-30"
            style="background: radial-gradient(circle, #a5f3fc, transparent); filter: blur(80px);"></div>
        <div class="absolute bottom-[-5%] left-[25%] w-72 h-72 rounded-full opacity-30"
            style="background: radial-gradient(circle, #ddd6fe, transparent); filter: blur(60px);"></div>
    </div>

    <div class="max-w-3xl mx-auto relative z-10">

        {{-- ── Header Card ── --}}
        <div class="relative mb-6 rounded-3xl overflow-hidden"
            style="background: linear-gradient(135deg, #1d4ed8 0%, #0369a1 100%); box-shadow: 0 20px 60px -10px rgba(29,78,216,0.35);">
            {{-- Pattern overlay --}}
            <div class="absolute inset-0 opacity-10"
                style="background: repeating-linear-gradient(45deg, transparent, transparent 25px, rgba(255,255,255,0.07) 25px, rgba(255,255,255,0.07) 50px);"></div>
            {{-- Glow circle --}}
            <div class="absolute -right-10 -top-10 w-48 h-48 rounded-full opacity-20"
                style="background: radial-gradient(circle, #93c5fd, transparent);"></div>

            <div class="relative px-8 py-7 flex items-center justify-between">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <div class="w-1.5 h-1.5 rounded-full bg-sky-300 animate-pulse"></div>
                        <span class="text-sky-200 text-xs font-semibold tracking-widest uppercase">Formulir Pendaftaran</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Cikampek Swimming Club</h1>
                    <p class="mt-1 text-blue-200 text-sm font-medium">Pendaftaran Atlet Aktif &mdash; {{ now()->format('F Y') }}</p>
                </div>
                <div class="flex-shrink-0">
                    <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl flex items-center justify-center"
                        style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.25); backdrop-filter: blur(10px);">
                        <img src="{{ asset('images/logocsc.png') }}" class="h-12 sm:h-16 w-auto drop-shadow-xl" alt="CSC Logo">
                    </div>
                </div>
            </div>

            {{-- Step progress --}}
            <div class="px-8 pb-6 flex items-center gap-3">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-full bg-white flex items-center justify-center text-xs font-bold text-blue-700 shadow">1</div>
                    <span class="text-white text-xs font-semibold hidden sm:block">Data Diri</span>
                </div>
                <div class="flex-1 h-px" style="background: rgba(255,255,255,0.35);"></div>
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold text-white"
                        style="border: 1.5px solid rgba(255,255,255,0.45); background: rgba(255,255,255,0.15);">2</div>
                    <span class="text-blue-100 text-xs font-medium hidden sm:block">Paket &amp; Coach</span>
                </div>
                <div class="flex-1 h-px" style="background: rgba(255,255,255,0.35);"></div>
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold text-white"
                        style="border: 1.5px solid rgba(255,255,255,0.45); background: rgba(255,255,255,0.15);">3</div>
                    <span class="text-blue-100 text-xs font-medium hidden sm:block">Jadwal Latihan</span>
                </div>
            </div>
        </div>

        {{-- ── Alerts ── --}}
        @if (session()->has('message'))
            <div class="mb-5 p-4 rounded-2xl flex items-start gap-3"
                style="background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534;">
                <svg class="w-5 h-5 mt-0.5 flex-shrink-0 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="font-semibold text-sm">{{ session('message') }}</span>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="mb-5 p-4 rounded-2xl flex items-start gap-3"
                style="background: #fef2f2; border: 1px solid #fecaca; color: #991b1b;">
                <svg class="w-5 h-5 mt-0.5 flex-shrink-0 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="font-semibold text-sm">{{ session('error') }}</span>
            </div>
        @endif

        {{-- ── Main Form Card ── --}}
        <div class="rounded-3xl overflow-hidden"
            style="background: #ffffff; border: 1px solid #e2e8f0; box-shadow: 0 20px 60px -10px rgba(29,78,216,0.1), 0 4px 20px rgba(0,0,0,0.06);">

            <form wire:submit.prevent="submit">

                {{-- ──────────────────────────────── --}}
                {{-- Section 1: Biodata               --}}
                {{-- ──────────────────────────────── --}}
                <div class="p-7 sm:p-8">
                    {{-- Section Header --}}
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center font-bold text-sm text-white flex-shrink-0"
                            style="background: linear-gradient(135deg, #3b82f6, #1d4ed8); box-shadow: 0 4px 12px rgba(59,130,246,0.35);">1</div>
                        <div>
                            <h2 class="text-base font-bold text-slate-800">Data Diri Calon Atlet</h2>
                            <p class="text-xs text-slate-400 mt-0.5">Isi informasi personal dengan benar</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                        {{-- Nama Lengkap --}}
                        <div class="space-y-1.5">
                            <label class="block text-xs font-semibold tracking-wide text-slate-500 uppercase">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </span>
                                <input type="text" wire:model="namaLengkap" required
                                    placeholder="Contoh: John Doe"
                                    class="w-full pl-10 pr-4 py-3 rounded-xl text-sm text-slate-800 placeholder-slate-400 outline-none transition-all"
                                    style="background: #f8fafc; border: 1.5px solid #e2e8f0;"
                                    onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.12)'; this.style.background='#fff'"
                                    onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'; this.style.background='#f8fafc'">
                            </div>
                            @error('namaLengkap') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Nomor Telepon --}}
                        <div class="space-y-1.5">
                            <label class="block text-xs font-semibold tracking-wide text-slate-500 uppercase">
                                Nomor Telepon <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                </span>
                                <input type="text" wire:model="noTelepon" required
                                    placeholder="08xxxxxxxxxx"
                                    class="w-full pl-10 pr-4 py-3 rounded-xl text-sm text-slate-800 placeholder-slate-400 outline-none transition-all"
                                    style="background: #f8fafc; border: 1.5px solid #e2e8f0;"
                                    onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.12)'; this.style.background='#fff'"
                                    onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'; this.style.background='#f8fafc'">
                            </div>
                            @error('noTelepon') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Pekerjaan Ayah --}}
                        <div class="space-y-1.5">
                            <label class="block text-xs font-semibold tracking-wide text-slate-500 uppercase">
                                Pekerjaan Ayah <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </span>
                                <input type="text" wire:model="pekerjaanAyah" required
                                    placeholder="Masukkan pekerjaan"
                                    class="w-full pl-10 pr-4 py-3 rounded-xl text-sm text-slate-800 placeholder-slate-400 outline-none transition-all"
                                    style="background: #f8fafc; border: 1.5px solid #e2e8f0;"
                                    onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.12)'; this.style.background='#fff'"
                                    onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'; this.style.background='#f8fafc'">
                            </div>
                            @error('pekerjaanAyah') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Tanggal Lahir --}}
                        <div class="space-y-1.5">
                            <label class="block text-xs font-semibold tracking-wide text-slate-500 uppercase">
                                Tanggal Lahir <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </span>
                                <input type="date" wire:model="tanggalLahir" required
                                    class="w-full pl-10 pr-4 py-3 rounded-xl text-sm text-slate-800 outline-none transition-all"
                                    style="background: #f8fafc; border: 1.5px solid #e2e8f0;"
                                    onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.12)'; this.style.background='#fff'"
                                    onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'; this.style.background='#f8fafc'">
                            </div>
                            @error('tanggalLahir') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Jenis Kelamin --}}
                        <div class="sm:col-span-2 space-y-2">
                            <label class="block text-xs font-semibold tracking-wide text-slate-500 uppercase">
                                Jenis Kelamin <span class="text-red-500">*</span>
                            </label>
                            <div class="flex gap-3">
                                {{-- Laki-laki --}}
                                <label class="flex-1 cursor-pointer" wire:key="gender-male">
                                    <input type="radio" wire:model.live="jenisKelamin" value="Laki-laki" class="sr-only">
                                    <div class="flex items-center justify-center gap-2.5 px-5 py-3 rounded-xl border-2 transition-all text-sm font-semibold"
                                        style="{{ $jenisKelamin == 'Laki-laki'
                                            ? 'background: linear-gradient(135deg, #3b82f6, #1d4ed8); border-color: #1d4ed8; color: white; box-shadow: 0 4px 14px rgba(59,130,246,0.35);'
                                            : 'background: #f8fafc; border-color: #e2e8f0; color: #64748b;' }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        Laki-laki
                                    </div>
                                </label>
                                {{-- Perempuan --}}
                                <label class="flex-1 cursor-pointer" wire:key="gender-female">
                                    <input type="radio" wire:model.live="jenisKelamin" value="Perempuan" class="sr-only">
                                    <div class="flex items-center justify-center gap-2.5 px-5 py-3 rounded-xl border-2 transition-all text-sm font-semibold"
                                        style="{{ $jenisKelamin == 'Perempuan'
                                            ? 'background: linear-gradient(135deg, #ec4899, #be185d); border-color: #be185d; color: white; box-shadow: 0 4px 14px rgba(236,72,153,0.35);'
                                            : 'background: #f8fafc; border-color: #e2e8f0; color: #64748b;' }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4a4 4 0 100 8 4 4 0 000-8zm0 10c-4 0-7 2-7 4h14c0-2-3-4-7-4z"/>
                                        </svg>
                                        Perempuan
                                    </div>
                                </label>
                            </div>
                            @error('jenisKelamin') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Password --}}
                        <div class="space-y-1.5">
                            <label class="block text-xs font-semibold tracking-wide text-slate-500 uppercase">
                                Password <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                </span>
                                <input type="password" wire:model="password" required
                                    placeholder="Minimal 6 karakter"
                                    class="w-full pl-10 pr-4 py-3 rounded-xl text-sm text-slate-800 placeholder-slate-400 outline-none transition-all"
                                    style="background: #f8fafc; border: 1.5px solid #e2e8f0;"
                                    onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.12)'; this.style.background='#fff'"
                                    onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'; this.style.background='#f8fafc'">
                            </div>
                            @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Konfirmasi Password --}}
                        <div class="space-y-1.5">
                            <label class="block text-xs font-semibold tracking-wide text-slate-500 uppercase">
                                Konfirmasi Password <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                </span>
                                <input type="password" wire:model="password_confirmation" required
                                    placeholder="Ulangi password"
                                    class="w-full pl-10 pr-4 py-3 rounded-xl text-sm text-slate-800 placeholder-slate-400 outline-none transition-all"
                                    style="background: #f8fafc; border: 1.5px solid #e2e8f0;"
                                    onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.12)'; this.style.background='#fff'"
                                    onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'; this.style.background='#f8fafc'">
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Divider --}}
                <div class="mx-7 sm:mx-8 h-px" style="background: linear-gradient(to right, transparent, #e2e8f0, transparent);"></div>

                {{-- ──────────────────────────────── --}}
                {{-- Section 2: Paket & Coach         --}}
                {{-- ──────────────────────────────── --}}
                <div class="p-7 sm:p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center font-bold text-sm text-white flex-shrink-0"
                            style="background: linear-gradient(135deg, #0ea5e9, #0369a1); box-shadow: 0 4px 12px rgba(14,165,233,0.3);">2</div>
                        <div>
                            <h2 class="text-base font-bold text-slate-800">Paket &amp; Coach</h2>
                            <p class="text-xs text-slate-400 mt-0.5">Pilih program latihan dan pelatih</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                        {{-- Paket Latihan --}}
                        <div class="space-y-1.5">
                            <label class="block text-xs font-semibold tracking-wide text-slate-500 uppercase">
                                Paket Latihan <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <select wire:model="paketLatihan" required
                                    class="w-full px-4 py-3 rounded-xl text-sm text-slate-800 appearance-none outline-none transition-all pr-10"
                                    style="background: #f8fafc; border: 1.5px solid #e2e8f0;"
                                    onfocus="this.style.borderColor='#0ea5e9'; this.style.boxShadow='0 0 0 3px rgba(14,165,233,0.12)'; this.style.background='#fff'"
                                    onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'; this.style.background='#f8fafc'">
                                    <option value="">-- Pilih Paket --</option>
                                    @foreach($packagesData as $package)
                                        <option value="{{ $package->id }}">{{ $package->name }} (Rp {{ number_format($package->price, 0, ',', '.') }})</option>
                                    @endforeach
                                </select>
                                <span class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </span>
                            </div>
                            @error('paketLatihan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Nama Coach --}}
                        <div class="space-y-1.5">
                            <label class="block text-xs font-semibold tracking-wide text-slate-500 uppercase">
                                Nama Coach <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <select wire:model.live="namaCoach" required
                                    class="w-full px-4 py-3 rounded-xl text-sm text-slate-800 appearance-none outline-none transition-all pr-10"
                                    style="background: #f8fafc; border: 1.5px solid #e2e8f0;"
                                    onfocus="this.style.borderColor='#0ea5e9'; this.style.boxShadow='0 0 0 3px rgba(14,165,233,0.12)'; this.style.background='#fff'"
                                    onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'; this.style.background='#f8fafc'">
                                    <option value="">-- Pilih Coach --</option>
                                    @foreach($coachesData as $coach)
                                        <option value="{{ $coach->id }}">{{ $coach->user->name }}</option>
                                    @endforeach
                                </select>
                                <span class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </span>
                            </div>
                            @error('namaCoach') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                    </div>
                </div>

                {{-- Divider --}}
                <div class="mx-7 sm:mx-8 h-px" style="background: linear-gradient(to right, transparent, #e2e8f0, transparent);"></div>

                {{-- ──────────────────────────────── --}}
                {{-- Section 3: Jadwal               --}}
                {{-- ──────────────────────────────── --}}
                <div class="p-7 sm:p-8">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center font-bold text-sm text-white flex-shrink-0"
                            style="background: linear-gradient(135deg, #8b5cf6, #6d28d9); box-shadow: 0 4px 12px rgba(139,92,246,0.3);">3</div>
                        <div>
                            <h2 class="text-base font-bold text-slate-800">Pilih Hari &amp; Jam Latihan</h2>
                            <p class="text-xs text-slate-400 mt-0.5">Pilih satu jadwal per hari yang tersedia</p>
                        </div>
                    </div>

                    {{-- Info banner --}}
                    <div class="mb-5 flex items-center gap-2.5 px-4 py-3 rounded-xl"
                        style="background: #faf5ff; border: 1px solid #ddd6fe;">
                        <svg class="w-4 h-4 text-violet-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-xs text-violet-700 font-medium">Sistem membatasi pendaftaran jika kuota pelatih per jam sudah penuh.</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach($schedulesByDay as $day => $schedules)
                            <div wire:key="day-{{ $day }}"
                                class="rounded-2xl overflow-hidden transition-all"
                                style="background: {{ isset($selectedSchedules[$day]) ? '#f5f3ff' : '#f8fafc' }}; border: 1.5px solid {{ isset($selectedSchedules[$day]) ? '#c4b5fd' : '#e2e8f0' }}; {{ isset($selectedSchedules[$day]) ? 'box-shadow: 0 4px 16px rgba(139,92,246,0.12);' : '' }}">

                                {{-- Day Header --}}
                                <div class="px-4 py-3 flex items-center justify-between"
                                    style="background: {{ isset($selectedSchedules[$day]) ? '#ede9fe' : '#f1f5f9' }}; border-bottom: 1px solid {{ isset($selectedSchedules[$day]) ? '#ddd6fe' : '#e2e8f0' }};">
                                    <div class="flex items-center gap-2">
                                        <div class="w-1.5 h-1.5 rounded-full {{ isset($selectedSchedules[$day]) ? 'bg-violet-500' : 'bg-slate-400' }}"></div>
                                        <span class="font-bold text-sm {{ isset($selectedSchedules[$day]) ? 'text-violet-700' : 'text-slate-600' }}">{{ $day }}</span>
                                    </div>
                                    @if(isset($selectedSchedules[$day]))
                                        <span class="flex items-center gap-1 text-[10px] font-bold text-emerald-600 px-2 py-0.5 rounded-full"
                                            style="background: #dcfce7; border: 1px solid #bbf7d0;">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            Dipilih
                                        </span>
                                    @endif
                                </div>

                                {{-- Slots --}}
                                <div class="p-3 space-y-2">
                                    @if(!$namaCoach)
                                        <div class="flex items-center gap-2 py-2">
                                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                            </svg>
                                            <p class="text-xs text-slate-400">Pilih coach terlebih dahulu</p>
                                        </div>
                                    @elseif(empty($schedules))
                                        <p class="text-xs text-slate-400 py-2">Tidak ada jadwal tersedia</p>
                                    @else
                                        @foreach($schedules as $slot)
                                            <label wire:key="slot-{{ $slot['id'] }}"
                                                class="flex items-center gap-3 px-3 py-2.5 rounded-xl cursor-pointer transition-all {{ $slot['is_full'] ? 'opacity-60 cursor-not-allowed' : 'hover:bg-white' }}"
                                                style="{{ isset($selectedSchedules[$day]) && $selectedSchedules[$day] == $slot['id']
                                                    ? 'background: white; border: 1.5px solid #8b5cf6; box-shadow: 0 2px 8px rgba(139,92,246,0.15);'
                                                    : 'background: transparent; border: 1.5px solid transparent;' }}">
                                                <input type="radio" wire:model.live="selectedSchedules.{{ $day }}" value="{{ $slot['id'] }}" class="sr-only" {{ $slot['is_full'] ? 'disabled' : '' }}>

                                                {{-- Custom radio --}}
                                                <div class="w-4 h-4 rounded-full border-2 flex items-center justify-center flex-shrink-0 transition-all
                                                    {{ isset($selectedSchedules[$day]) && $selectedSchedules[$day] == $slot['id'] ? 'border-violet-500 bg-violet-500' : 'border-slate-300 bg-white' }}
                                                    {{ $slot['is_full'] ? 'bg-slate-200 border-slate-200' : '' }}">
                                                    @if(isset($selectedSchedules[$day]) && $selectedSchedules[$day] == $slot['id'])
                                                        <div class="w-1.5 h-1.5 bg-white rounded-full"></div>
                                                    @endif
                                                </div>

                                                <div class="flex flex-col min-w-0 flex-1">
                                                    <span class="text-xs font-semibold {{ $slot['is_full'] ? 'text-slate-400 line-through' : 'text-slate-700' }}">{{ $slot['time'] }}</span>
                                                    <span class="text-[10px] {{ $slot['is_full'] ? 'text-red-400' : 'text-slate-400' }}">
                                                        {{ $slot['is_full'] ? 'Penuh' : 'Sisa ' . ($slot['quota'] - $slot['usage']) . ' slot' }}
                                                    </span>
                                                </div>

                                                @if(!$slot['is_full'])
                                                    <span class="ml-auto text-[9px] font-bold px-1.5 py-0.5 rounded-full"
                                                        style="{{ isset($selectedSchedules[$day]) && $selectedSchedules[$day] == $slot['id']
                                                            ? 'background: #ede9fe; color: #7c3aed;'
                                                            : 'background: #f1f5f9; color: #94a3b8;' }}">
                                                        {{ $slot['quota'] - $slot['usage'] }}/{{ $slot['quota'] }}
                                                    </span>
                                                @endif
                                            </label>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @error('selectedSchedules') <p class="text-red-500 text-xs mt-3">{{ $message }}</p> @enderror
                </div>

                {{-- ──────────────────────────────── --}}
                {{-- Submit                           --}}
                {{-- ──────────────────────────────── --}}
                <div class="px-7 pb-8 sm:px-8">
                    <div class="pt-4" style="border-top: 1px solid #f1f5f9;">
                        <button type="submit" wire:loading.attr="disabled"
                            class="w-full py-4 rounded-2xl font-bold text-base text-white relative overflow-hidden group transition-all active:scale-95 disabled:opacity-70"
                            style="background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%); box-shadow: 0 8px 24px rgba(37,99,235,0.35);">
                            {{-- Hover shimmer --}}
                            <span class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity"
                                style="background: linear-gradient(135deg, #3b82f6, #2563eb);"></span>
                            <span class="relative flex items-center justify-center gap-2.5">
                                <svg wire:loading.remove class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span wire:loading.remove>Konfirmasi &amp; Daftar Sekarang</span>
                                <svg wire:loading class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                                </svg>
                                <span wire:loading>Memproses Pendaftaran...</span>
                            </span>
                        </button>
                        <!-- <p class="mt-4 text-center text-xs text-slate-400 px-4">
                            Dengan mendaftar, akun perenang anda akan aktif secara otomatis. Password default untuk login pertama kali adalah:
                            <span class="font-bold text-slate-600 px-1.5 py-0.5 rounded ml-1" style="background: #f1f5f9;">password</span>
                        </p> -->
                    </div>
                </div>

            </form>
        </div>

        {{-- Footer --}}
        <p class="text-center text-xs text-slate-400 mt-6 pb-4">
            &copy; {{ date('Y') }} Cikampek Swimming Club. All rights reserved.
        </p>

    </div>

    {{-- Success Modal --}}
    @if($isSuccessModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6" style="background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(8px);">
        <div class="bg-white rounded-3xl w-full max-w-xl overflow-hidden shadow-2xl animate-[slideDown_0.3s_ease-out]">
            {{-- Modal Header --}}
            <div class="bg-gradient-to-r from-emerald-500 to-teal-600 px-8 py-6 text-center relative overflow-hidden">
                <div class="absolute inset-0 opacity-10" style="background: repeating-linear-gradient(45deg, transparent, transparent 15px, rgba(255,255,255,1) 15px, rgba(255,255,255,1) 30px);"></div>
                <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg relative z-10">
                    <svg class="w-8 h-8 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-white relative z-10">Pendaftaran Berhasil!</h2>
                <p class="text-emerald-100 mt-1 relative z-10 text-sm">Selamat datang di Cikampek Swimming Club</p>
            </div>

            {{-- Modal Body --}}
            <div class="px-8 py-6">
                <p class="text-sm text-slate-600 text-center mb-6">Berikut adalah informasi akun Anda. Harap simpan informasi ini baik-baik untuk keperluan login.</p>
                
                <div class="bg-slate-50 rounded-2xl p-5 border border-slate-200 mb-6 space-y-3">
                    <div class="flex justify-between border-b border-slate-200 pb-2">
                        <span class="text-xs text-slate-500 font-semibold uppercase">Nama Lengkap</span>
                        <span class="text-sm font-bold text-slate-800">{{ $registeredData['namaLengkap'] ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between border-b border-slate-200 pb-2">
                        <span class="text-xs text-slate-500 font-semibold uppercase">No. Telepon</span>
                        <span class="text-sm font-bold text-slate-800">{{ $registeredData['noTelepon'] ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between border-b border-slate-200 pb-2">
                        <span class="text-xs text-slate-500 font-semibold uppercase">Paket Latihan</span>
                        <span class="text-sm font-bold text-slate-800">{{ $registeredData['paketLatihan'] ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between border-b border-slate-200 pb-2">
                        <span class="text-xs text-slate-500 font-semibold uppercase">Coach</span>
                        <span class="text-sm font-bold text-slate-800">{{ $registeredData['namaCoach'] ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between border-b border-slate-200 pb-2">
                        <span class="text-xs text-slate-500 font-semibold uppercase">Email Login</span>
                        <span class="text-sm font-bold text-blue-600">{{ $registeredData['email'] ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-slate-500 font-semibold uppercase">Password</span>
                        <span class="text-sm font-bold text-blue-600">{{ $registeredData['password'] ?? '-' }}</span>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-3">
                    <button wire:click="downloadPdf" type="button" class="flex-1 flex items-center justify-center gap-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-semibold py-3 px-4 rounded-xl transition-colors border border-indigo-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Cetak PDF
                    </button>
                    <button wire:click="redirectToLogin" type="button" class="flex-1 flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-xl transition-colors shadow-lg shadow-blue-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                        Lanjut Login
                    </button>
                </div>
            </div>
        </div>
    </div>
    <style>
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
    @endif
</div>
