<!DOCTYPE html>
<html lang="id" class=""> <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coach Dashboard</title>
    
    {{-- 
      Script ini WAJIB ada di <head> (sebelum CSS) untuk mencegah
      'Flash of Unstyled Content' (FOUC) saat memuat tema gelap.
    --}}
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
          document.documentElement.classList.add('dark')
        } else {
          document.documentElement.classList.remove('dark')
        }
    </script>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { 
            font-family: 'Inter', sans-serif; 
        }
        /* Sembunyikan elemen yang belum di-init Alpine */
        [x-cloak] { display: none !important; }
        
        /* Custom scrollbar Light */
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #c1c1c1; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #a8a8a8; }
        
        /* Custom scrollbar Dark */
        .dark .custom-scrollbar::-webkit-scrollbar-track { background: #374151; } /* gray-700 */
        .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #4b5563; } /* gray-600 */
        .dark .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #6b7280; } /* gray-500 */
        
        /* Shell-inspired colors (Tetap dipertahankan & Dipertegas) */
        .shell-red { background-color: #e61739; }
        .shell-yellow { background-color: #f9d616; }
        .shell-blue { background-color: #0051ff; }
    </style>
</head>
{{-- 
  Ubah <body>: Menggunakan background yang sedikit lebih 'cool' (slate-50) agar warna lain pop-out
--}}
<body class="bg-blue-100 dark:bg-slate-900 text-blue-800 dark:text-gray-200 antialiased selection:bg-red-500 selection:text-white">

{{-- 
  BUNGKUS SEMUA KONTEN DENGAN 'x-data' 
--}}
<div x-data="coachDashboard(
        {{ $errors->any() ? 'true' : 'false' }}, 
        {{ old('schedule_id') ?? 'null' }}, 
        '{{ old('place') ?? '' }}'
     )"
     x-init="init()"
>

    {{-- Header: Ditambah border-t-4 warna merah (Khas Persija/Shell) --}}
    <header class="bg-white dark:bg-slate-800 shadow-md sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                {{-- Logo dan Brand --}}
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <div class="h-10 w-10 ml-1 flex items-center justify-center transition-transform hover:scale-110 duration-300">
                            <a href="{{ route('landing') }}" class="flex-shrink-0">
                                <img src="{{ asset('images/logocsc.png') }}" alt="Logo Cikampek Swimming Club" class="h-12 w-auto drop-shadow-sm">
                            </a>
                        </div>
                        
                        <span class="hidden sm:inline-block ml-5 text-xl font-extrabold tracking-tight text-slate-900 dark:text-gray-100">
                            <span class="text-blue-700 dark:text-blue-400">Cikampek Swimming Club</span>
                        </span>

                    </div>
                </div>

                {{-- User Menu & Theme Switcher --}}
                <div class="flex items-center">
                    
                    {{-- Theme Switcher --}}
                    <div class="flex items-center p-1 bg-gray-100 dark:bg-slate-700 rounded-lg border border-gray-200 dark:border-slate-600">
                        <button 
                            type="button"
                            @click="theme = 'light'" 
                            :class="theme === 'light' ? 'bg-white dark:bg-slate-500 shadow-sm text-yellow-500' : 'text-gray-400 dark:text-gray-400 hover:text-gray-600'" 
                            class="p-1.5 rounded-md focus:outline-none transition-all duration-200"
                            aria-label="Light Mode"
                        >
                            <i data-feather="sun" class="w-4 h-4"></i>
                        </button>
                        <button 
                            type="button"
                            @click="theme = 'system'" 
                            :class="theme === 'system' ? 'bg-white dark:bg-slate-500 shadow-sm text-blue-600' : 'text-gray-400 dark:text-gray-400 hover:text-gray-600'" 
                            class="ml-1 p-1.5 rounded-md focus:outline-none transition-all duration-200"
                            aria-label="System Mode"
                        >
                            <i data-feather="monitor" class="w-4 h-4"></i>
                        </button>
                        <button 
                            type="button"
                            @click="theme = 'dark'" 
                            :class="theme === 'dark' ? 'bg-white dark:bg-slate-500 shadow-sm text-purple-400' : 'text-gray-400 dark:text-gray-400 hover:text-gray-600'" 
                            class="ml-1 p-1.5 rounded-md focus:outline-none transition-all duration-200"
                            aria-label="Dark Mode"
                        >
                            <i data-feather="moon" class="w-4 h-4"></i>
                        </button>
                    </div>
                    
                    @auth
                        <div class="p-2 flex items-center space-x-4">
                            {{-- Nama User --}}
                            {{-- <div class="text-right hidden sm:block">
                                <p class="text-sm font-bold text-gray-900 dark:text-gray-100">{{ Auth::user()->name }}</p>
                                <p class="text-xs font-medium text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30 rounded-full inline-block">Coach</p>
                            </div> --}}
                            
                            {{-- Avatar --}}
                            {{-- <div class="relative">
                                <button class="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-white dark:focus:ring-offset-slate-800 focus:ring-red-500 hover:ring-2 hover:ring-red-400 transition-all">
                                    <span class="sr-only">Buka menu pengguna</span>
                                    @if ($coach->user->photo_url)
                                        <img class="h-9 w-9 rounded-full border-2 border-white dark:border-slate-700 shadow-sm" src="{{ $coach->user->photo_url }}" alt="Foto profil">
                                    @else
                                        <div class="h-9 w-9 rounded-full bg-blue-600 flex items-center justify-center border-2 border-white dark:border-slate-700 shadow-sm">
                                            <i data-feather="user" class="text-white w-5 h-5"></i>
                                        </div>
                                    @endif
                                </button>
                            </div>
                             --}}
                            {{-- Tombol Logout --}}
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-gray-300 dark:border-slate-600 text-xs font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-slate-700 hover:bg-red-50 dark:hover:bg-red-900/30 hover:text-red-700 dark:hover:text-red-400 focus:outline-none transition-colors shadow-sm">
                                    <i data-feather="log-out" class="w-4 h-4 mr-0 sm:mr-1"></i>
                                    <span class="hidden sm:inline">Logout</span>
                                </button>
                            </form>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    {{-- Konten Utama Dashboard --}}
    <main class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Notifikasi --}}
            @if (session('success'))
                <div class="mb-6 rounded-lg bg-gradient-to-r from-green-50 to-white dark:from-green-900/40 dark:to-slate-800 p-4 flex items-start border-l-4 border-green-500 shadow-sm">
                    <div class="bg-green-100 dark:bg-green-800 p-1 rounded-full mr-3">
                         <i data-feather="check-circle" class="text-green-600 dark:text-green-300 w-5 h-5"></i>
                    </div>
                    <p class="text-sm font-medium text-green-800 dark:text-green-200 self-center">{{ session('success') }}</p>
                </div>
            @endif
            @if (session('error'))
                <div class="mb-6 rounded-lg bg-gradient-to-r from-red-50 to-white dark:from-red-900/40 dark:to-slate-800 p-4 flex items-start border-l-4 border-red-500 shadow-sm">
                    <div class="bg-red-100 dark:bg-red-800 p-1 rounded-full mr-3">
                        <i data-feather="alert-circle" class="text-red-600 dark:text-red-300 w-5 h-5"></i>
                    </div>
                    <p class="text-sm font-medium text-red-800 dark:text-red-200 self-center">{{ session('error') }}</p>
                </div>
            @endif
            @if($errors->any())
                <div class="mb-6 rounded-lg bg-gradient-to-r from-red-50 to-white dark:from-red-900/40 dark:to-slate-800 p-4 flex items-start border-l-4 border-red-500 shadow-sm">
                     <div class="bg-red-100 dark:bg-red-800 p-1 rounded-full mr-3">
                        <i data-feather="alert-triangle" class="text-red-600 dark:text-red-300 w-5 h-5"></i>
                    </div>
                    <p class="text-sm font-medium text-red-800 dark:text-red-200 self-center">Form tidak valid, silakan periksa kembali input Anda di modal.</p>
                </div>
            @endif

            <div class="space-y-8">

                {{-- Profil + Statistik (Dibuat lebih berwarna dengan border-left biru) --}}
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-md border-l-4 border-blue-600 dark:border-blue-500 overflow-hidden ring-1 ring-black/5 dark:ring-white/10">
                    {{-- Menambahkan gradient halus di background --}}
                    <div class="p-6 bg-gradient-to-br from-white via-white to-blue-50 dark:from-slate-800 dark:to-slate-800/50">
                        <div class="flex flex-col md:flex-row gap-6 items-start">

                            <div class="flex-shrink-0 group flex justify-center md:justify-start w-full md:w-auto">
                                @if ($coach->user->photo_url)
                                    <img 
                                        src="{{ $coach->user->photo_url }}" 
                                        class="w-28 h-28 rounded-full object-cover border-4 border-white dark:border-slate-700 shadow-lg mx-auto">
                                @else
                                    <div class="w-28 h-28 rounded-full flex items-center justify-center
                                                bg-gradient-to-br from-gray-100 to-gray-200 dark:from-slate-700 dark:to-slate-600 border-4 border-white dark:border-slate-700 shadow-lg">
                                        <i data-feather="user" class="w-12 h-12 text-gray-400 dark:text-gray-300"></i>
                                    </div>
                                @endif
                            </div>

                            {{-- Info & Statistik mini --}}
                            <div class="flex-1 w-full">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h2 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">
                                            {{ $coach->user->name }}
                                        </h2>
                                        <div class="flex items-center mt-1 text-blue-600 dark:text-blue-400 font-medium">
                                            <i data-feather="mail" class="w-4 h-4 mr-2"></i>
                                            <p class="text-sm">{{ $coach->user->email }}</p>
                                        </div>
                                    </div>
                                    <span class="hidden sm:inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                        Active Coach
                                    </span>
                                </div>
                                
                                <p class="mt-3 text-gray-600 dark:text-gray-300 max-w-2xl leading-relaxed text-sm bg-white/50 dark:bg-slate-700/50 p-3 rounded-lg border border-gray-100 dark:border-slate-600">
                                    {{ $coach->bio ?? 'Belum ada bio yang ditambahkan.' }}
                                </p>

                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mt-6">
                                    @php
                                        $stats = [
                                            ['label' => 'Total Member', 'value' => $totalMembers, 'color' => 'blue', 'icon' => 'users'],
                                            ['label' => 'Aktif', 'value' => $activeMembers, 'color' => 'green', 'icon' => 'user-check'],
                                            ['label' => 'Tidak Aktif', 'value' => $inactiveMembers, 'color' => 'red', 'icon' => 'user-x'],
                                            ['label' => 'Jadwal Latihan', 'value' => $totalSchedules, 'color' => 'yellow', 'icon' => 'calendar'],
                                        ];
                                        
                                        // Update warna agar lebih vibran/pop
                                        $colorClasses = [
                                            'blue'   => 'bg-blue-50 text-blue-800 border border-blue-100 dark:bg-blue-900/20 dark:text-blue-100 dark:border-blue-700/30 hover:bg-blue-100 transition-colors',
                                            'green'  => 'bg-green-50 text-green-800 border border-green-100 dark:bg-green-900/20 dark:text-green-100 dark:border-green-700/30 hover:bg-green-100 transition-colors',
                                            'red'    => 'bg-red-50 text-red-800 border border-red-100 dark:bg-red-900/20 dark:text-red-100 dark:border-red-700/30 hover:bg-red-100 transition-colors',
                                            'yellow' => 'bg-yellow-50 text-yellow-800 border border-yellow-100 dark:bg-yellow-900/20 dark:text-yellow-100 dark:border-yellow-700/30 hover:bg-yellow-100 transition-colors',
                                        ];
                                        
                                        $iconBg = [
                                            'blue'   => 'bg-blue-500 text-white shadow-blue-500/30',
                                            'green'  => 'bg-green-500 text-white shadow-green-500/30',
                                            'red'    => 'bg-red-500 text-white shadow-red-500/30',
                                            'yellow' => 'bg-yellow-400 text-white shadow-yellow-400/30',
                                        ];
                                    @endphp
                                    @foreach ($stats as $s)
                                        <div class="rounded-xl p-4 flex items-center shadow-sm {{ $colorClasses[$s['color']] ?? 'bg-gray-100' }}">
                                            <div class="flex-shrink-0 p-3 rounded-lg shadow-lg {{ $iconBg[$s['color']] }} mr-4">
                                                <i data-feather="{{ $s['icon'] }}" class="w-5 h-5"></i>
                                            </div>
                                            <div>
                                                <p class="text-xs font-bold uppercase tracking-wider opacity-70">{{ $s['label'] }}</p>
                                                <p class="text-2xl font-black mt-0.5">{{ $s['value'] }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Dua kolom: Atlet & Jadwal --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                    {{-- Atlet yang Dilatih (Aksen Border Hijau) --}}
                    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-md border-t-4 border-blue-500 overflow-hidden ring-1 ring-black/5 dark:ring-white/5">
                        <div class="px-6 py-5 border-b border-gray-100 dark:border-slate-700 bg-gradient-to-r from-white to-green-50/50 dark:from-slate-800 dark:to-slate-800">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="bg-blue-100 dark:bg-blue-900/30 p-2 rounded-lg">
                                        <i data-feather="users" class="text-blue-600 dark:text-blue-400 w-5 h-5"></i>
                                    </div>
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">
                                        Atlet yang Dilatih
                                    </h3>
                                </div>
                                <span class="bg-gray-100 dark:bg-slate-700 text-gray-600 dark:text-gray-300 text-xs font-bold px-3 py-1 rounded-full">
                                    {{ count($coach->members) }}
                                </span>
                            </div>
                        </div>
                        <div class="overflow-hidden">
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead class="bg-gray-50/80 dark:bg-slate-700/50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nama</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                            <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100 dark:divide-slate-700 bg-white dark:bg-slate-800">
                                        @forelse($coach->members as $member)
                                            <tr class="hover:bg-green-50/30 dark:hover:bg-slate-700/50 transition-colors">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gray-200 dark:bg-slate-700 flex items-center justify-center shadow-sm border border-white dark:border-slate-600">
                                                            @if ($member->user->photo_url)
                                                                <img class="h-10 w-10 rounded-full object-cover" src="{{ $member->user->photo_url }}" alt="">
                                                            @else
                                                                <i data-feather="user" class="text-gray-400 dark:text-gray-300 w-5 h-5"></i>
                                                            @endif
                                                        </div>
                                                        <div class="ml-4">
                                                            <div class="text-sm font-bold text-gray-900 dark:text-gray-100">{{ $member->user->name }}</div>
                                                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $member->user->email }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @php
                                                        $statusClasses = $member->status == 'AKTIF'
                                                            ? 'bg-green-100 text-green-700 border border-green-200 dark:bg-green-900/40 dark:text-green-300 dark:border-green-700'
                                                            : 'bg-red-100 text-red-700 border border-red-200 dark:bg-red-900/40 dark:text-red-300 dark:border-red-700';
                                                    @endphp
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold {{ $statusClasses }}">
                                                        {{ $member->status }}
                                                    </span>
                                                </td>
                                                {{-- Di bagian tabel member, ganti button raport --}}
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <button 
                                                        onclick="openRaportModal({{ $member->id }}, '{{ $member->user->name }}')" 
                                                        class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-bold rounded-md text-white shell-blue hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all shadow-md shadow-blue-900/20 transform hover:-translate-y-0.5">
                                                        <i data-feather="file-text" class="w-3 h-3 mr-1.5"></i> Raport
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">
                                                    <div class="flex flex-col items-center justify-center opacity-60">
                                                        <i data-feather="users" class="w-12 h-12 text-gray-300 dark:text-gray-600 mb-3"></i>
                                                        <p class="text-sm">Belum ada member yang dilatih</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- Jadwal Latihan (Aksen Border Kuning) --}}
                    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-md border-t-4 border-blue-500 overflow-hidden ring-1 ring-black/5 dark:ring-white/5">
                        <div class="px-6 py-5 border-b border-gray-100 dark:border-slate-700 bg-gradient-to-r from-white to-yellow-50/50 dark:from-slate-800 dark:to-slate-800">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                     <div class="bg-blue-100 dark:bg-blue-900/30 p-2 rounded-lg">
                                        <i data-feather="calendar" class="text-blue-600 dark:text-blue-400 w-5 h-5"></i>
                                    </div>
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">
                                        Jadwal Latihan
                                    </h3>
                                </div>
                                <span class="bg-gray-100 dark:bg-slate-700 text-gray-600 dark:text-gray-300 text-xs font-bold px-3 py-1 rounded-full">
                                    {{ count($coach->trainingSchedules) }}
                                </span>
                            </div>
                        </div>
                        <div class="overflow-hidden">
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead class="bg-gray-50/80 dark:bg-slate-700/50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Hari</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Waktu</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Lokasi</th>
                                            <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100 dark:divide-slate-700 bg-white dark:bg-slate-800">
                                        @forelse($coach->trainingSchedules as $schedule)
                                            <tr class="hover:bg-yellow-50/30 dark:hover:bg-slate-700/50 transition-colors">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-800 dark:text-gray-100">
                                                    <span class="inline-block bg-gray-100 dark:bg-slate-700 px-2 py-1 rounded text-gray-600 dark:text-gray-300 text-xs uppercase tracking-wide">
                                                        {{ ucfirst(strtolower($schedule->day)) }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300 font-medium">
                                                    {{ $schedule->time ? \Carbon\Carbon::parse($schedule->time)->format('H:i') : '-' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                                    <div class="flex items-center">
                                                        <i data-feather="map-pin" class="w-3 h-3 mr-1 text-gray-400"></i>
                                                        {{ $schedule->place ?? '-' }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <button 
                                                        type="button"
                                                        @click="
                                                            showModal = true; 
                                                            selectedScheduleId = {{ $schedule->id }}; 
                                                            selectedSchedulePlace = '{{ $schedule->place ?? '' }}'
                                                        "
                                                        class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-bold rounded-md text-white shell-blue hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all shadow-md shadow-blue-900/20 transform hover:-translate-y-0.5">
                                                        <i data-feather="check-square" class="w-3 h-3 mr-1.5"></i> Absen
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">
                                                    <div class="flex flex-col items-center justify-center opacity-60">
                                                        <i data-feather="calendar" class="w-12 h-12 text-gray-300 dark:text-gray-600 mb-3"></i>
                                                        <p class="text-sm">Belum ada jadwal latihan.</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- RIWAYAT ABSENSI (Aksen Border Biru) --}}
                    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-md border-t-4 border-blue-500 overflow-hidden ring-1 ring-black/5 dark:ring-white/5 lg:col-span-2">
                        <div class="px-6 py-5 border-b border-gray-100 dark:border-slate-700 bg-gradient-to-r from-white to-blue-50/50 dark:from-slate-800 dark:to-slate-800">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                     <div class="bg-blue-100 dark:bg-blue-900/30 p-2 rounded-lg">
                                        <i data-feather="clipboard" class="text-blue-600 dark:text-blue-400 w-5 h-5"></i>
                                    </div>
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">
                                        Riwayat Absensi
                                    </h3>
                                </div>
                                <span class="bg-gray-100 dark:bg-slate-700 text-gray-600 dark:text-gray-300 text-xs font-bold px-3 py-1 rounded-full">
                                    {{ count($attendances) }} Sesi
                                </span>
                            </div>
                        </div>
                        <div class="overflow-hidden">
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead class="bg-gray-50/80 dark:bg-slate-700/50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tanggal</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Jadwal</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Lokasi</th>
                                            <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Hadir</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100 dark:divide-slate-700 bg-white dark:bg-slate-800">
                                        @forelse($attendances as $attendance)
                                            <tr class="hover:bg-blue-50/30 dark:hover:bg-slate-700/50 transition-colors">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-800 dark:text-gray-100">
                                                    {{ \Carbon\Carbon::parse($attendance->date)->isoFormat('DD MMM YYYY') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                                    <span class="font-medium text-gray-900 dark:text-gray-200">{{ $attendance->schedule ? ucfirst(strtolower($attendance->schedule->day)) : 'N/A' }}</span>
                                                    <span class="text-xs text-gray-400 ml-1">({{ $attendance->schedule && $attendance->schedule->time ? \Carbon\Carbon::parse($attendance->schedule->time)->format('H:i') : '-' }})</span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                                    {{ $attendance->place ?? '-' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold text-blue-600 dark:text-blue-400">
                                                    {{ $attendance->members_count }} Orang
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">
                                                    <div class="flex flex-col items-center justify-center opacity-60">
                                                        <i data-feather="clipboard" class="w-12 h-12 text-gray-300 dark:text-gray-600 mb-3"></i>
                                                        <p class="text-sm">Belum ada riwayat absensi.</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <div 
        x-show="showModal"
        x-cloak 
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 overflow-y-auto"
        aria-labelledby="modal-title" role="dialog" aria-modal="true"
    >
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            {{-- Latar belakang overlay (backdrop) --}}
            <div 
                x-show="showModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm transition-opacity"
                @click="showModal = false"
                aria-hidden="true"
            ></div>

            {{-- Centering trick --}}
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            {{-- Panel Modal --}}
            <div 
                x-show="showModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-2xl px-4 pt-5 pb-4 text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full sm:p-6 border border-gray-100 dark:border-slate-700"
            >
                <div class="absolute top-0 right-0 pt-4 pr-4">
                    <button type="button" @click="showModal = false" class="bg-white dark:bg-slate-800 rounded-full text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-white dark:focus:ring-offset-slate-800 focus:ring-red-500 p-1 hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors">
                        <span class="sr-only">Close</span>
                        <i data-feather="x" class="h-5 w-5"></i>
                    </button>
                </div>
                
                <form 
                    method="POST" 
                    action="{{ route('attendance.store') }}" 
                    enctype="multipart/form-data"
                >
                    @csrf
                    
                    {{-- Konten Form di dalam Modal --}}
                    <div>
                        <div class="mx-auto flex items-center justify-center h-14 w-14 rounded-full bg-blue-100 dark:bg-blue-900/30 border-2 border-blue-50 dark:border-blue-800">
                            <i data-feather="clipboard" class="h-6 w-6 text-blue-600 dark:text-blue-400"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-5">
                            <h3 class="text-xl leading-6 font-bold text-gray-900 dark:text-gray-100" id="modal-title">
                                Form Absensi
                            </h3>
                            <p class="text-sm text-gray-500 mt-1">Silakan isi data latihan hari ini.</p>
                        </div>
                    </div>
                    
                    {{-- Input tersembunyi --}}
                    <input type="hidden" name="schedule_id" x-model="selectedScheduleId">
                    <input type="hidden" name="place" x-model="selectedSchedulePlace">

                    <div class="mt-6 space-y-5">
                        {{-- Tanggal --}}
                        <div>
                            <label for="date" class="block text-sm font-bold text-gray-700 dark:text-gray-300">Tanggal Latihan</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i data-feather="calendar" class="h-4 w-4 text-gray-400 dark:text-gray-500"></i>
                                </div>
                                <input type="date" name="date" id="date"
                                       value="{{ old('date', now()->format('Y-m-d')) }}" required
                                       class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-gray-100 dark:placeholder-gray-400 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 sm:text-sm shadow-sm transition-shadow">
                            </div>
                            @error('date')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Foto (Opsional) --}}
                        <div>
                            <label for="photo" class="block text-sm font-bold text-gray-700 dark:text-gray-300">Foto Sesi (Opsional)</label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-slate-600 border-dashed rounded-lg hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors group cursor-pointer">
                                <div class="space-y-1 text-center relative">
                                    <i data-feather="camera" class="mx-auto h-10 w-10 text-gray-400 dark:text-gray-500 group-hover:text-red-500 transition-colors"></i>
                                    <div class="flex text-sm text-gray-600 dark:text-gray-400 justify-center">
                                        <label for="photo" class="relative cursor-pointer bg-transparent rounded-md font-medium text-red-600 dark:text-red-400 hover:text-red-500 dark:hover:text-red-300 focus-within:outline-none">
                                            <span>Upload file</span>
                                            <input id="photo" name="photo" type="file" class="sr-only">
                                        </label>
                                        <p class="pl-1">atau drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-500">
                                        PNG, JPG, GIF hingga 10MB
                                    </p>
                                </div>
                            </div>
                            @error('photo')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Member Reguler (Checkbox) --}}
                        <div>
                            <span class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Member Reguler Hadir</span>
                            <div class="space-y-2 max-h-48 overflow-y-auto rounded-lg border border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-700/50 p-3 custom-scrollbar">
                                
                                @forelse($activeRegularMembers as $member)
                                    <label class="flex items-center p-2 rounded hover:bg-white dark:hover:bg-slate-600 transition-colors cursor-pointer">
                                        <input type="checkbox" name="members[]" value="{{ $member->id }}"
                                               {{ (is_array(old('members')) && in_array($member->id, old('members'))) ? 'checked' : '' }}
                                               class="h-5 w-5 rounded border-gray-300 dark:border-slate-500 text-red-600 focus:ring-red-500 dark:focus:ring-offset-slate-800 dark:bg-slate-700">
                                        <span class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-200">{{ $member->user->name }}</span>
                                    </label>
                                @empty
                                    <p class="text-sm text-gray-500 dark:text-gray-400 italic text-center py-2">Tidak ada member reguler (yang aktif).</p>
                                @endforelse
                            </div>
                            @error('members')
                                <p class="mt-2 text-sm text-blue-600 dark:text-blue-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Member Tambahan (Checkbox) --}}
                        <div>
                            <span class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Member Tambahan (Opsional)</span>
                            <div class="space-y-2 max-h-40 overflow-y-auto rounded-lg border border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-700/50 p-3 custom-scrollbar">
                                @forelse($allOtherMembers as $member)
                                    <label class="flex items-center p-2 rounded hover:bg-white dark:hover:bg-slate-600 transition-colors cursor-pointer">
                                        <input type="checkbox" name="extra_members[]" value="{{ $member->id }}"
                                               {{ (is_array(old('extra_members')) && in_array($member->id, old('extra_members'))) ? 'checked' : '' }}
                                               class="h-5 w-5 rounded border-gray-300 dark:border-slate-500 text-blue-600 focus:ring-blue-500 dark:focus:ring-offset-slate-800 dark:bg-slate-700">
                                        <span class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-200">{{ $member->user->name }}</span>
                                    </label>
                                @empty
                                    <p class="text-sm text-gray-500 dark:text-gray-400 italic text-center py-2">Tidak ada member lain.</p>
                                @endforelse
                            </div>
                            @error('extra_members')
                                <p class="mt-2 text-sm text-blue-600 dark:text-blue-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Tombol Aksi Modal --}}
                    <div class="mt-8 sm:mt-8 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                        <button type="submit"
                                class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-lg shadow-blue-500/30 px-4 py-2.5 shell-blue text-base font-bold text-white hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-white dark:focus:ring-offset-slate-800 focus:ring-blue-500 sm:col-start-2 sm:text-sm transition-all transform hover:-translate-y-0.5">
                            <i data-feather="save" class="w-4 h-4 mr-2 mt-0.5"></i> Simpan Absensi
                        </button>
                        <button type="button" 
                                @click="showModal = false"
                                class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 dark:border-slate-600 shadow-sm px-4 py-2.5 bg-white dark:bg-slate-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-white dark:focus:ring-offset-slate-800 focus:ring-blue-500 sm:mt-0 sm:col-start-1 sm:text-sm transition-colors">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- Modal Raport --}}
<div id="raportModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-75 overflow-y-auto h-full w-full z-50">
    <div class="relative top-10 mx-auto p-5 border w-11/12 max-w-7xl shadow-lg rounded-xl bg-white dark:bg-slate-800">
        {{-- Header Modal --}}
        <div class="flex justify-between items-center pb-4 mb-4 border-b dark:border-slate-700">
            <h3 id="modalTitle" class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                Raport Member: <span id="memberName"></span>
            </h3>
            <button onclick="closeRaportModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        {{-- Content Modal --}}
        <div class="space-y-6">
            {{-- Filter Section --}}
            <div class="bg-gradient-to-r from-blue-50 to-green-50 dark:from-slate-700 dark:to-slate-700 rounded-lg shadow p-4">
                <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-3">Filter Grafik</h4>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Pilih gaya renang dan tahun untuk melihat grafik performa</p>
                
                <div class="grid grid-cols-2 gap-4">
                    {{-- Select Gaya --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Gaya Renang & Jarak</label>
                        <select id="gaya" class="w-full border border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="gaya_bebas_50">Gaya Bebas 50m</option>
                            <option value="gaya_bebas_100">Gaya Bebas 100m</option>
                            <option value="gaya_bebas_200">Gaya Bebas 200m</option>
                            <option value="gaya_bebas_400">Gaya Bebas 400m</option>
                            <option value="gaya_bebas_800">Gaya Bebas 800m</option>
                            <option value="gaya_bebas_1500">Gaya Bebas 1500m</option>
                            <option value="gaya_dada_50">Gaya Dada 50m</option>
                            <option value="gaya_dada_100">Gaya Dada 100m</option>
                            <option value="gaya_dada_200">Gaya Dada 200m</option>
                            <option value="gaya_punggung_50">Gaya Punggung 50m</option>
                            <option value="gaya_punggung_100">Gaya Punggung 100m</option>
                            <option value="gaya_punggung_200">Gaya Punggung 200m</option>
                            <option value="gaya_kupu_50">Gaya Kupu 50m</option>
                            <option value="gaya_kupu_100">Gaya Kupu 100m</option>
                            <option value="gaya_kupu_200">Gaya Kupu 200m</option>
                            <option value="gaya_ganti_200">Gaya Ganti 200m</option>
                            <option value="gaya_ganti_400">Gaya Ganti 400m</option>
                        </select>
                    </div>

                    {{-- Input Tahun --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tahun</label>
                        <input type="number" id="year" value="{{ now()->year }}" 
                               class="w-full border border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                               min="2000" max="2099">
                    </div>
                </div>
            </div>

            {{-- Loading State --}}
            <div id="loadingState" class="hidden text-center py-8">
                <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500"></div>
                <p class="mt-3 text-gray-600 dark:text-gray-400">Memuat data...</p>
            </div>

            {{-- Detail Data (Placeholder) --}}
            <div id="raport-info" class="bg-gray-50 dark:bg-slate-700 rounded-lg p-4">
                <p class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-2">Detail Data Raport Terpilih</p>
                <div id="raport-detail" class="text-sm text-gray-600 dark:text-gray-400">
                    <!-- Akan diisi via JS -->
                </div>
            </div>

            {{-- Table Section dengan Action Buttons --}}
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-md overflow-hidden ring-1 ring-black/5 dark:ring-white/5">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-slate-700 bg-gradient-to-r from-white to-blue-50/50 dark:from-slate-800 dark:to-slate-800 flex justify-between items-center">
                    <h4 class="font-semibold text-gray-900 dark:text-gray-100">Detail Data Raport</h4>
                    
                    {{-- Button Tambah Data --}}
                    <button id="tambahDataBtn" class="inline-flex items-center px-3 py-1.5 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 transition-colors">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Tambah Data
                    </button>
                </div>
                
                <div class="overflow-x-auto">
                    <table id="raport-table" class="w-full">
                        <thead class="bg-gray-50/80 dark:bg-slate-700/50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Bulan</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Waktu</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Volume</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Intensitas</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Peaking</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Coach</th>
                                <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-slate-700 bg-white dark:bg-slate-800">
                            <!-- Akan diisi via JS -->
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Charts Section --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Chart 1: Waktu Tempuh --}}
                <div class="bg-white dark:bg-slate-800 rounded-lg shadow-md p-5 ring-1 ring-black/5 dark:ring-white/5">
                    <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-4">Grafik Waktu Tempuh (Detik)</h4>
                    <canvas id="chartValue" class="w-full" style="max-height: 300px;"></canvas>
                </div>

                {{-- Chart 2: Volume, Peaking, Intensity --}}
                <div class="bg-white dark:bg-slate-800 rounded-lg shadow-md p-5 ring-1 ring-black/5 dark:ring-white/5">
                    <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-4">Grafik Volume, Peaking, Intensity</h4>
                    <canvas id="chartVolume" class="w-full" style="max-height: 300px;"></canvas>
                </div>
            </div>
        </div>

        {{-- Footer Modal --}}
        <div class="mt-6 flex justify-end">
            <button onclick="closeRaportModal()" class="px-4 py-2 bg-gray-200 dark:bg-slate-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-slate-600 transition-colors">
                Tutup
            </button>
        </div>
    </div>
</div>

{{-- Modal Form Create/Edit Raport --}}
<div id="raportFormModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-70 overflow-y-auto h-full w-full z-[999]">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-2xl rounded-xl bg-white dark:bg-slate-800 z-[1000]">
        <div class="flex justify-between items-center pb-3 mb-4 border-b dark:border-slate-700">
            <h3 id="formModalTitle" class="text-xl font-bold text-gray-900 dark:text-gray-100">Tambah Data Raport</h3>
            <button id="closeFormModalBtn" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <form id="raportForm" class="space-y-4">
            <input type="hidden" id="raport_id" name="raport_id">
            <input type="hidden" id="form_member_id" name="member_id">
            <input type="hidden" id="form_gaya" name="gaya">
            <input type="hidden" id="form_year" name="year">

            {{-- Bulan (hanya untuk Create) --}}
            <div id="monthFieldWrapper">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Bulan</label>
                <select id="month" name="month" class="w-full border border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-gray-200 rounded-lg px-3 py-2">
                    <option value="">-- Pilih Bulan --</option>
                </select>
            </div>

            {{-- Waktu (detik) --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Waktu (detik)</label>
                <input type="number" id="value" name="value" step="0.01" class="w-full border border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-gray-200 rounded-lg px-3 py-2" required>
            </div>

            {{-- Volume --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Volume (meter)</label>
                <input type="number" id="volume" name="volume" class="w-full border border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-gray-200 rounded-lg px-3 py-2" required>
            </div>

            {{-- Intensity --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Intensitas (%)</label>
                <input type="number" id="intensity" name="intensity" class="w-full border border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-gray-200 rounded-lg px-3 py-2" required>
            </div>

            {{-- Peaking --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Peaking</label>
                <input type="number" id="peaking" name="peaking" class="w-full border border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-gray-200 rounded-lg px-3 py-2" required>
            </div>

            {{-- Coach --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Coach</label>
                <select id="coach_id" name="coach_id" class="w-full border border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-gray-200 rounded-lg px-3 py-2" required>
                    <option value="">-- Pilih Coach --</option>
                </select>
            </div>

            {{-- Note --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Catatan</label>
                <textarea id="note" name="note" rows="3" class="w-full border border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-gray-200 rounded-lg px-3 py-2"></textarea>
            </div>

            <div class="flex justify-end space-x-3 pt-4">
                <button type="button" id="cancelFormBtn" class="px-4 py-2 bg-gray-200 dark:bg-slate-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-slate-600 transition-colors">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Load Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

{{-- JavaScript untuk Modal & Chart --}}
<script>
    let currentMemberId = null;
    let chartValue = null;
    let chartVolume = null;
    let isEditMode = false;
    let coaches = []; // Cache coaches list

    // ═══════════════════════════════════════════════════════════════
    // INITIALIZATION - Event Listeners
    // ═══════════════════════════════════════════════════════════════
    
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Initializing Raport Modal System...');
        
        // Event Listeners untuk tombol
        document.getElementById('tambahDataBtn').addEventListener('click', openCreateForm);
        document.getElementById('closeFormModalBtn').addEventListener('click', closeFormModal);
        document.getElementById('cancelFormBtn').addEventListener('click', closeFormModal);
        
        // Event delegation untuk edit dan delete buttons
        document.addEventListener('click', function(e) {
            // Edit Button
            if (e.target.closest('.edit-btn')) {
                const btn = e.target.closest('.edit-btn');
                const id = btn.dataset.id;
                const month = btn.dataset.month;
                const value = btn.dataset.value;
                const volume = btn.dataset.volume;
                const intensity = btn.dataset.intensity;
                const peaking = btn.dataset.peaking;
                const coachId = btn.dataset.coach;
                const note = decodeURIComponent(btn.dataset.note || '');
                
                console.log('Edit button clicked:', { id, month });
                openEditForm(id, month, value, volume, intensity, peaking, coachId, note);
            }
            
            // Delete Button
            if (e.target.closest('.delete-btn')) {
                const btn = e.target.closest('.delete-btn');
                const id = btn.dataset.id;
                const month = btn.dataset.month;
                
                console.log('Delete button clicked:', { id, month });
                confirmDelete(id, month);
            }
        });

        // Filter change events
        document.getElementById('gaya').addEventListener('change', loadRaportData);
        document.getElementById('year').addEventListener('input', loadRaportData);

        // Close modal when click outside
        document.getElementById('raportModal').addEventListener('click', function(e) {
            if (e.target === this) closeRaportModal();
        });
        
        document.getElementById('raportFormModal').addEventListener('click', function(e) {
            if (e.target === this) closeFormModal();
        });

        // Form submit
        document.getElementById('raportForm').addEventListener('submit', handleFormSubmit);
        
        console.log('Raport Modal System initialized successfully');
    });

    // ═══════════════════════════════════════════════════════════════
    // TABLE FUNCTIONS dengan Action Buttons
    // ═══════════════════════════════════════════════════════════════
    
    function updateTable(raports) {
        const tbody = document.querySelector('#raport-table tbody');
        tbody.innerHTML = '';
        
        if (raports.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="7" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">
                        <div class="flex flex-col items-center justify-center opacity-60">
                            <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="text-sm">Tidak ada data raport</p>
                        </div>
                    </td>
                </tr>
            `;
            return;
        }
        
        raports.forEach(raport => {
            const minutes = Math.floor(raport.value / 60);
            const seconds = raport.value - (minutes * 60);
            const formattedTime = `${String(minutes).padStart(2, '0')}:${seconds.toFixed(2).padStart(5, '0')}`;
            
            const intensityBadge = raport.intensity >= 80 ? 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300' :
                                raport.intensity >= 60 ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/40 dark:text-yellow-300' :
                                'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300';
            
            const safeNote = encodeURIComponent(raport.note || '');
            
            const row = document.createElement('tr');
            row.className = 'hover:bg-blue-50/30 dark:hover:bg-slate-700/50 transition-colors';
            row.innerHTML = `
                <td class="px-6 py-3 text-sm">
                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-semibold bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300">
                        ${raport.month.charAt(0).toUpperCase() + raport.month.slice(1)}
                    </span>
                </td>
                <td class="px-6 py-3 text-sm text-gray-900 dark:text-gray-100 font-mono">${formattedTime}</td>
                <td class="px-6 py-3 text-sm text-gray-700 dark:text-gray-300">${raport.volume} m</td>
                <td class="px-6 py-3 text-sm">
                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-semibold ${intensityBadge}">
                        ${raport.intensity}%
                    </span>
                </td>
                <td class="px-6 py-3 text-sm text-gray-700 dark:text-gray-300">${raport.peaking}</td>
                <td class="px-6 py-3 text-sm text-gray-700 dark:text-gray-300">${raport.coach?.user?.name || '-'}</td>
                <td class="px-6 py-3 text-sm text-right">
                    <button class="edit-btn inline-flex items-center justify-center w-8 h-8 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 mr-2 transition-colors" 
                            data-id="${raport.id}" 
                            data-month="${raport.month}" 
                            data-value="${raport.value}" 
                            data-volume="${raport.volume}" 
                            data-intensity="${raport.intensity}" 
                            data-peaking="${raport.peaking}" 
                            data-coach="${raport.coach_id}" 
                            data-note="${safeNote}"
                            title="Edit data">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </button>
                    <button class="delete-btn inline-flex items-center justify-center w-8 h-8 text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 transition-colors" 
                            data-id="${raport.id}" 
                            data-month="${raport.month}"
                            title="Hapus data">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </td>
            `;
            
            tbody.appendChild(row);
        });
    }

    function updateCharts(valueData, volumeData) {
        if (chartValue) chartValue.destroy();
        if (chartVolume) chartVolume.destroy();

        const isDark = document.documentElement.classList.contains('dark');
        const textColor = isDark ? '#e5e7eb' : '#374151';
        const gridColor = isDark ? '#374151' : '#e5e7eb';

        const ctx1 = document.getElementById('chartValue').getContext('2d');
        chartValue = new Chart(ctx1, {
            type: 'line',
            data: valueData,
            options: {
                responsive: true,
                maintainAspectRatio: true,
                scales: {
                    y: {
                        reverse: true,
                        title: { display: true, text: 'Waktu (detik)', color: textColor },
                        ticks: { color: textColor },
                        grid: { color: gridColor }
                    },
                    x: {
                        title: { display: true, text: 'Bulan', color: textColor },
                        ticks: { color: textColor },
                        grid: { color: gridColor }
                    }
                },
                plugins: { legend: { labels: { color: textColor } } }
            }
        });

        const ctx2 = document.getElementById('chartVolume').getContext('2d');
        chartVolume = new Chart(ctx2, {
            type: 'line',
            data: volumeData,
            options: {
                responsive: true,
                maintainAspectRatio: true,
                scales: {
                    y: { 
                        title: { display: true, text: 'Nilai', color: textColor },
                        ticks: { color: textColor },
                        grid: { color: gridColor }
                    },
                    x: { 
                        title: { display: true, text: 'Bulan', color: textColor },
                        ticks: { color: textColor },
                        grid: { color: gridColor }
                    }
                },
                plugins: { legend: { labels: { color: textColor } } }
            }
        });
    }

    // ═══════════════════════════════════════════════════════════════
    // FORM MODAL FUNCTIONS
    // ═══════════════════════════════════════════════════════════════
    
    function openCreateForm() {
        console.log('Opening create form');
        
        isEditMode = false;
        document.getElementById('formModalTitle').textContent = 'Tambah Data Raport';
        document.getElementById('raportForm').reset();
        document.getElementById('raport_id').value = '';
        
        // Set hidden fields
        document.getElementById('form_member_id').value = currentMemberId;
        document.getElementById('form_gaya').value = document.getElementById('gaya').value;
        document.getElementById('form_year').value = document.getElementById('year').value;
        
        // Show month field untuk create
        document.getElementById('monthFieldWrapper').style.display = 'block';
        
        // Load available months
        loadAvailableMonths();
        
        // Show modal
        document.getElementById('raportFormModal').classList.remove('hidden');
        console.log('Create form modal opened');
    }

    function openEditForm(id, month, value, volume, intensity, peaking, coachId, note) {
        console.log('Opening edit form for ID:', id);
        
        isEditMode = true;
        document.getElementById('formModalTitle').textContent = 'Edit Data Raport';
        
        // Set values
        document.getElementById('raport_id').value = id;
        document.getElementById('value').value = parseFloat(value).toFixed(2);
        document.getElementById('volume').value = volume;
        document.getElementById('intensity').value = intensity;
        document.getElementById('peaking').value = peaking;
        document.getElementById('coach_id').value = coachId;
        document.getElementById('note').value = note;
        
        // Set hidden fields
        document.getElementById('form_member_id').value = currentMemberId;
        document.getElementById('form_gaya').value = document.getElementById('gaya').value;
        document.getElementById('form_year').value = document.getElementById('year').value;
        
        // Hide month field untuk edit
        document.getElementById('monthFieldWrapper').style.display = 'none';
        
        // Show modal
        document.getElementById('raportFormModal').classList.remove('hidden');
        console.log('Edit form modal opened');
    }

    function closeFormModal() {
        console.log('Closing form modal');
        document.getElementById('raportFormModal').classList.add('hidden');
        document.getElementById('raportForm').reset();
    }

    // ═══════════════════════════════════════════════════════════════
    // FORM SUBMIT HANDLER
    // ═══════════════════════════════════════════════════════════════
    
    function handleFormSubmit(e) {
        e.preventDefault();
        console.log('Form submitted, edit mode:', isEditMode);
        
        const formData = new FormData(this);
        const data = Object.fromEntries(formData.entries());
        
        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
        
        if (isEditMode) {
            // UPDATE
            const raportId = document.getElementById('raport_id').value;
            console.log('Updating raport ID:', raportId);
            
            fetch(`/api/raport/update/${raportId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('Raport berhasil diupdate!', 'success');
                    closeFormModal();
                    loadRaportData();
                } else {
                    showAlert(data.message || 'Gagal mengupdate raport', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Terjadi kesalahan saat mengupdate raport', 'error');
            });
            
        } else {
            // CREATE
            console.log('Creating new raport');
            
            fetch('/api/raport/create', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('Raport berhasil ditambahkan!', 'success');
                    closeFormModal();
                    loadRaportData();
                } else {
                    showAlert(data.message || 'Gagal menambahkan raport', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Terjadi kesalahan saat menambahkan raport', 'error');
            });
        }
    }

    // ═══════════════════════════════════════════════════════════════
    // UTILITY FUNCTIONS
    // ═══════════════════════════════════════════════════════════════
    
    function loadCoachesList() {
        fetch('/api/raport/coaches')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    coaches = data.coaches;
                    const select = document.getElementById('coach_id');
                    select.innerHTML = '<option value="">-- Pilih Coach --</option>';
                    
                    coaches.forEach(coach => {
                        select.innerHTML += `<option value="${coach.id}">${coach.name}</option>`;
                    });
                }
            })
            .catch(error => console.error('Error loading coaches:', error));
    }

    function loadAvailableMonths() {
        const gaya = document.getElementById('gaya').value;
        const year = document.getElementById('year').value;
        
        fetch(`/api/raport/available-months?member_id=${currentMemberId}&gaya=${gaya}&year=${year}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const select = document.getElementById('month');
                    select.innerHTML = '<option value="">-- Pilih Bulan --</option>';
                    
                    Object.entries(data.months).forEach(([key, value]) => {
                        select.innerHTML += `<option value="${key}">${value}</option>`;
                    });
                }
            })
            .catch(error => console.error('Error loading months:', error));
    }

    function confirmDelete(id, month) {
        if (confirm(`Apakah Anda yakin ingin menghapus data raport bulan ${month}?`)) {
            deleteRaport(id);
        }
    }

    function deleteRaport(id) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
        
        fetch(`/api/raport/delete/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('Raport berhasil dihapus!', 'success');
                loadRaportData();
            } else {
                showAlert(data.message || 'Gagal menghapus raport', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Terjadi kesalahan saat menghapus raport', 'error');
        });
    }

    function showAlert(message, type = 'success') {
        const alertDiv = document.createElement('div');
        alertDiv.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-[70] ${
            type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
        }`;
        alertDiv.textContent = message;
        
        document.body.appendChild(alertDiv);
        
        setTimeout(() => {
            alertDiv.remove();
        }, 3000);
    }

    // ═══════════════════════════════════════════════════════════════
    // MODAL MANAGEMENT FUNCTIONS
    // ═══════════════════════════════════════════════════════════════

    let modalStack = []; // Untuk melacak modal yang terbuka

    function openRaportModal(memberId, memberName) {
        console.log('Opening raport modal for:', memberId, memberName);
        
        currentMemberId = memberId;
        document.getElementById('memberName').textContent = memberName;
        document.getElementById('raportModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        // Tambah ke stack
        modalStack.push('raportModal');
        
        // Load coaches list
        loadCoachesList();
        
        // Load data pertama kali
        loadRaportData();
    }

    function openCreateForm() {
        console.log('Opening create form');
        
        isEditMode = false;
        document.getElementById('formModalTitle').textContent = 'Tambah Data Raport';
        document.getElementById('raportForm').reset();
        document.getElementById('raport_id').value = '';
        
        // Set hidden fields
        document.getElementById('form_member_id').value = currentMemberId;
        document.getElementById('form_gaya').value = document.getElementById('gaya').value;
        document.getElementById('form_year').value = document.getElementById('year').value;
        
        // Show month field untuk create
        document.getElementById('monthFieldWrapper').style.display = 'block';
        
        // Load available months
        loadAvailableMonths();
        
        // Show modal form dengan z-index tinggi
        document.getElementById('raportFormModal').classList.remove('hidden');
        
        // Tambah ke stack
        modalStack.push('raportFormModal');
        
        console.log('Create form modal opened');
    }

    function openEditForm(id, month, value, volume, intensity, peaking, coachId, note) {
        console.log('Opening edit form for ID:', id);
        
        isEditMode = true;
        document.getElementById('formModalTitle').textContent = 'Edit Data Raport';
        
        // Set values
        document.getElementById('raport_id').value = id;
        document.getElementById('value').value = parseFloat(value).toFixed(2);
        document.getElementById('volume').value = volume;
        document.getElementById('intensity').value = intensity;
        document.getElementById('peaking').value = peaking;
        document.getElementById('coach_id').value = coachId;
        document.getElementById('note').value = note;
        
        // Set hidden fields
        document.getElementById('form_member_id').value = currentMemberId;
        document.getElementById('form_gaya').value = document.getElementById('gaya').value;
        document.getElementById('form_year').value = document.getElementById('year').value;
        
        // Hide month field untuk edit
        document.getElementById('monthFieldWrapper').style.display = 'none';
        
        // Show modal form
        document.getElementById('raportFormModal').classList.remove('hidden');
        
        // Tambah ke stack
        modalStack.push('raportFormModal');
        
        console.log('Edit form modal opened');
    }

    function closeFormModal() {
        console.log('Closing form modal');
        document.getElementById('raportFormModal').classList.add('hidden');
        document.getElementById('raportForm').reset();
        
        // Hapus dari stack
        modalStack = modalStack.filter(modal => modal !== 'raportFormModal');
    }

    function closeRaportModal() {
        console.log('Closing raport modal');
        
        document.getElementById('raportModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
        
        // Juga tutup form modal jika terbuka
        if (modalStack.includes('raportFormModal')) {
            closeFormModal();
        }
        
        // Reset stack
        modalStack = [];
        
        if (chartValue) {
            chartValue.destroy();
            chartValue = null;
        }
        if (chartVolume) {
            chartVolume.destroy();
            chartVolume = null;
        }
    }

    // Event listener untuk klik outside modal form
    document.getElementById('raportFormModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeFormModal();
        }
    });

    // Event listener untuk klik outside modal raport
    document.getElementById('raportModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeRaportModal();
        }
    });

    // ═══════════════════════════════════════════════════════════════
    // MODAL RAPORT FUNCTIONS
    // ═══════════════════════════════════════════════════════════════
    
    function openRaportModal(memberId, memberName) {
        console.log('Opening raport modal for:', memberId, memberName);
        
        currentMemberId = memberId;
        document.getElementById('memberName').textContent = memberName;
        document.getElementById('raportModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        // Load coaches list
        loadCoachesList();
        
        // Load data pertama kali
        loadRaportData();
    }

    function closeRaportModal() {
        console.log('Closing raport modal');
        
        document.getElementById('raportModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
        
        if (chartValue) {
            chartValue.destroy();
            chartValue = null;
        }
        if (chartVolume) {
            chartVolume.destroy();
            chartVolume = null;
        }
    }

    // ═══════════════════════════════════════════════════════════════
    // LOAD DATA FUNCTIONS
    // ═══════════════════════════════════════════════════════════════
    
    function loadRaportData() {
        const gaya = document.getElementById('gaya').value;
        const year = document.getElementById('year').value;
        
        console.log('Loading raport data:', { currentMemberId, gaya, year });
        
        document.getElementById('loadingState').classList.remove('hidden');

        fetch(`/api/raport/chart-data?member_id=${currentMemberId}&gaya=${gaya}&year=${year}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('loadingState').classList.add('hidden');
                
                if (data.success) {
                    console.log('Data loaded successfully:', data.raports.length, 'records');
                    updateDetailInfo(data.raports);
                    updateTable(data.raports);
                    updateCharts(data.chartValue, data.chartVolume);
                } else {
                    console.error('Failed to load data:', data.message);
                    showAlert('Gagal memuat data: ' + data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error loading raport data:', error);
                document.getElementById('loadingState').classList.add('hidden');
                showAlert('Gagal memuat data raport', 'error');
            });
    }

    function updateDetailInfo(raports) {
        const detailDiv = document.getElementById('raport-detail');
        
        if (raports.length === 0) {
            detailDiv.innerHTML = '<p class="text-sm text-gray-500 dark:text-gray-400">Tidak ada data raport untuk gaya dan tahun yang dipilih.</p>';
            return;
        }

        let html = `<p class="text-sm font-semibold mb-3 text-gray-900 dark:text-gray-100">Total Data: ${raports.length} bulan</p>`;
        html += '<div class="grid grid-cols-2 md:grid-cols-3 gap-3">';
        
        raports.forEach(raport => {
            const minutes = Math.floor(raport.value / 60);
            const seconds = raport.value - (minutes * 60);
            const formattedTime = `${String(minutes).padStart(2, '0')}:${seconds.toFixed(2).padStart(5, '0')}`;
            
            html += `
                <div class="border border-gray-200 dark:border-slate-600 rounded-lg p-3 bg-white dark:bg-slate-800">
                    <p class="font-bold text-blue-600 dark:text-blue-400 mb-1">${raport.month.charAt(0).toUpperCase() + raport.month.slice(1)}</p>
                    <p class="text-xs text-gray-600 dark:text-gray-400">⏱️ ${formattedTime}</p>
                    <p class="text-xs text-gray-600 dark:text-gray-400">📊 ${raport.volume || '-'}m</p>
                </div>
            `;
        });
        
        html += '</div>';
        detailDiv.innerHTML = html;
    }

</script>

</body>
</html>