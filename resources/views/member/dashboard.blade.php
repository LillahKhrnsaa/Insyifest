<!DOCTYPE html>
<html lang="id" class="">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Dashboard - Cikampek Swimming Club</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
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
        [x-cloak] { display: none !important; }
        
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #c1c1c1; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #a8a8a8; }
        
        .dark .custom-scrollbar::-webkit-scrollbar-track { background: #374151; }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #4b5563; }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #6b7280; }
        
        .shell-blue { background-color: #0051ff; }
        
        /* Loading spinner */
        .loading-spinner {
            border: 3px solid #f3f3f3;
            border-top: 3px solid #3498db;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body class="bg-blue-100 dark:bg-slate-900 text-blue-800 dark:text-gray-200 antialiased selection:bg-blue-500 selection:text-white">

@if(!isset($member))
    {{-- Tampilkan error page jika tidak ada data member --}}
    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full bg-white dark:bg-slate-800 rounded-xl shadow-md p-8 text-center">
            <div class="w-20 h-20 mx-auto mb-4 bg-red-100 dark:bg-red-900 rounded-full flex items-center justify-center">
                <i data-feather="alert-triangle" class="text-red-600 dark:text-red-400 w-10 h-10"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Data Member Tidak Ditemukan</h2>
            <p class="text-gray-600 dark:text-gray-300 mb-6">
                Akun Anda tidak memiliki data member. Silakan hubungi administrator untuk mengaktifkan akun member Anda.
            </p>
            <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <i data-feather="arrow-left" class="w-4 h-4 mr-2"></i>
                Kembali ke Dashboard
            </a>
        </div>
    </div>
@else
<div x-data="memberDashboard()" x-init="init()">
    {{-- Header --}}
    <header class="bg-white dark:bg-slate-800 shadow-md sticky top-0 z-40">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
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
                        <button @click="theme = 'light'" 
                                :class="theme === 'light' ? 'bg-white dark:bg-slate-500 shadow-sm text-yellow-500' : 'text-gray-400 dark:text-gray-400 hover:text-gray-600'" 
                                class="p-1.5 rounded-md focus:outline-none transition-all duration-200">
                            <i data-feather="sun" class="w-4 h-4"></i>
                        </button>
                        <button @click="theme = 'system'" 
                                :class="theme === 'system' ? 'bg-white dark:bg-slate-500 shadow-sm text-blue-600' : 'text-gray-400 dark:text-gray-400 hover:text-gray-600'" 
                                class="ml-1 p-1.5 rounded-md focus:outline-none transition-all duration-200">
                            <i data-feather="monitor" class="w-4 h-4"></i>
                        </button>
                        <button @click="theme = 'dark'" 
                                :class="theme === 'dark' ? 'bg-white dark:bg-slate-500 shadow-sm text-red-400' : 'text-gray-400 dark:text-gray-400 hover:text-gray-600'" 
                                class="ml-1 p-1.5 rounded-md focus:outline-none transition-all duration-200">
                            <i data-feather="moon" class="w-4 h-4"></i>
                        </button>
                    </div>
                    
                    @auth
                        <div class="p-2 flex items-center space-x-4">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-gray-300 dark:border-slate-600 text-xs font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-slate-700 hover:bg-blue-50 dark:hover:bg-blue-900/30 hover:text-blue-700 dark:hover:text-blue-400 focus:outline-none transition-colors shadow-sm">
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

    {{-- Main Content --}}
    <main class="py-8">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">

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

            <div class="space-y-8">

                {{-- Profil Member --}}
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-md border-l-4 border-blue-600 dark:border-blue-500 overflow-hidden ring-1 ring-black/5 dark:ring-white/10">
                    <div class="p-6 bg-gradient-to-br from-white via-white to-blue-50 dark:from-slate-800 dark:to-slate-800/50">
                        <div class="flex flex-col md:flex-row gap-6 items-start">
                            <div class="flex-shrink-0 group flex justify-center md:justify-start w-full md:w-auto">
                                @if ($member->user->photo_url)
                                    <img 
                                        src="{{ $member->user->photo_url }}" 
                                        class="w-28 h-28 rounded-full object-cover border-4 border-white dark:border-slate-700 shadow-lg mx-auto">
                                @else
                                    <div class="w-28 h-28 rounded-full flex items-center justify-center
                                                bg-gradient-to-br from-gray-100 to-gray-200 dark:from-slate-700 dark:to-slate-600 border-4 border-white dark:border-slate-700 shadow-lg">
                                        <i data-feather="user" class="w-12 h-12 text-gray-400 dark:text-gray-300"></i>
                                    </div>
                                @endif
                            </div>

                            <div class="flex-1 w-full">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h2 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">
                                            {{ $member->user->name }}
                                        </h2>
                                        <div class="flex items-center mt-1 text-blue-600 dark:text-blue-400 font-medium">
                                            <i data-feather="mail" class="w-4 h-4 mr-2"></i>
                                            <p class="text-sm">{{ $member->user->email }}</p>
                                        </div>
                                    </div>
                                    <span class="hidden sm:inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                                        {{ $member->status == 'AKTIF' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                        {{ $member->status }}
                                    </span>
                                </div>
                                
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mt-6">
                                    @php
                                        $stats = [
                                            ['label' => 'Total Kehadiran', 'value' => $totalAttendances, 'color' => 'blue', 'icon' => 'calendar'],
                                            ['label' => 'Total Raport', 'value' => $totalRaports, 'color' => 'green', 'icon' => 'file-text'],
                                            ['label' => 'Coach', 'value' => $totalCoaches, 'color' => 'yellow', 'icon' => 'users'],
                                            ['label' => 'Paket Latihan', 'value' => $member->trainingPackage->name ?? 'Tidak ada', 'color' => 'red', 'icon' => 'package'],
                                        ];
                                        
                                        $colorClasses = [
                                            'blue'   => 'bg-blue-50 text-blue-800 border border-blue-100 dark:bg-blue-900/20 dark:text-blue-100 dark:border-blue-700/30 hover:bg-blue-100 transition-colors',
                                            'green'  => 'bg-green-50 text-green-800 border border-green-100 dark:bg-green-900/20 dark:text-green-100 dark:border-green-700/30 hover:bg-green-100 transition-colors',
                                            'yellow' => 'bg-yellow-50 text-yellow-800 border border-yellow-100 dark:bg-yellow-900/20 dark:text-yellow-100 dark:border-yellow-700/30 hover:bg-yellow-100 transition-colors',
                                            'red' => 'bg-red-50 text-red-800 border border-red-100 dark:bg-red-900/20 dark:text-red-100 dark:border-red-700/30 hover:bg-red-100 transition-colors',
                                        ];
                                        
                                        $iconBg = [
                                            'blue'   => 'bg-blue-500 text-white shadow-blue-500/30',
                                            'green'  => 'bg-green-500 text-white shadow-green-500/30',
                                            'yellow' => 'bg-yellow-400 text-white shadow-yellow-400/30',
                                            'red' => 'bg-red-500 text-white shadow-red-500/30',
                                        ];
                                    @endphp
                                    @foreach ($stats as $s)
                                        <div class="rounded-xl p-4 flex items-center shadow-sm {{ $colorClasses[$s['color']] ?? 'bg-gray-100' }}">
                                            <div class="flex-shrink-0 p-3 rounded-lg shadow-lg {{ $iconBg[$s['color']] }} mr-4">
                                                <i data-feather="{{ $s['icon'] }}" class="w-5 h-5"></i>
                                            </div>
                                            <div>
                                                <p class="text-xs font-bold uppercase tracking-wider opacity-70">{{ $s['label'] }}</p>
                                                <p class="text-lg font-black mt-0.5">{{ $s['value'] }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Dua Kolom: Coach & Jadwal --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                    {{-- Coach Terkait --}}
                    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-md border-t-4 border-blue-500 overflow-hidden ring-1 ring-black/5 dark:ring-white/5">
                        <div class="px-6 py-5 border-b border-gray-100 dark:border-slate-700 bg-gradient-to-r from-white to-green-50/50 dark:from-slate-800 dark:to-slate-800">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="bg-blue-100 dark:bg-blue-900/30 p-2 rounded-lg">
                                        <i data-feather="users" class="text-blue-600 dark:text-blue-400 w-5 h-5"></i>
                                    </div>
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">
                                        Coach Saya
                                    </h3>
                                </div>
                                <span class="bg-gray-100 dark:bg-slate-700 text-gray-600 dark:text-gray-300 text-xs font-bold px-3 py-1 rounded-full">
                                    {{ $assignedCoaches->count() }}
                                </span>
                            </div>
                        </div>
                        <div class="overflow-hidden">
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead class="bg-gray-50/80 dark:bg-slate-700/50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nama Coach</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Email</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Bio</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100 dark:divide-slate-700 bg-white dark:bg-slate-800">
                                        @forelse($assignedCoaches as $coach)
                                            <tr class="hover:bg-green-50/30 dark:hover:bg-slate-700/50 transition-colors">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gray-200 dark:bg-slate-700 flex items-center justify-center shadow-sm border border-white dark:border-slate-600">
                                                            @if ($coach->user->photo_url)
                                                                <img class="h-10 w-10 rounded-full object-cover" src="{{ $coach->user->photo_url }}" alt="">
                                                            @else
                                                                <i data-feather="user" class="text-gray-400 dark:text-gray-300 w-5 h-5"></i>
                                                            @endif
                                                        </div>
                                                        <div class="ml-4">
                                                            <div class="text-sm font-bold text-gray-900 dark:text-gray-100">{{ $coach->user->name }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                                    {{ $coach->user->email }}
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                                    {{ Str::limit($coach->bio ?? 'Tidak ada bio', 50) }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">
                                                    <div class="flex flex-col items-center justify-center opacity-60">
                                                        <i data-feather="users" class="w-12 h-12 text-gray-300 dark:text-gray-600 mb-3"></i>
                                                        <p class="text-sm">Belum ada coach yang ditugaskan</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- Jadwal Latihan --}}
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
                                    {{ $trainingSchedules->count() }}
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
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Coach</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100 dark:divide-slate-700 bg-white dark:bg-slate-800">
                                        @forelse($trainingSchedules as $schedule)
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
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                                    @forelse($schedule->coaches as $coach)
                                                        <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full mr-1 mb-1">
                                                            {{ $coach->user->name }}
                                                        </span>
                                                    @empty
                                                        <span class="text-gray-400">-</span>
                                                    @endforelse
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
                </div>

                {{-- Dua Kolom: Riwayat Absensi & Raport --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                    {{-- Riwayat Absensi --}}
                    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-md border-t-4 border-blue-500 overflow-hidden ring-1 ring-black/5 dark:ring-white/5">
                        <div class="px-6 py-5 border-b border-gray-100 dark:border-slate-700 bg-gradient-to-r from-white to-blue-50/50 dark:from-slate-800 dark:to-slate-800">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="bg-blue-100 dark:bg-blue-900/30 p-2 rounded-lg">
                                        <i data-feather="clipboard" class="text-blue-600 dark:text-blue-400 w-5 h-5"></i>
                                    </div>
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">
                                        Riwayat Kehadiran
                                    </h3>
                                </div>
                                <span class="bg-gray-100 dark:bg-slate-700 text-gray-600 dark:text-gray-300 text-xs font-bold px-3 py-1 rounded-full">
                                    {{ $attendances->count() }} Sesi
                                </span>
                            </div>
                        </div>
                        <div class="overflow-hidden">
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead class="bg-gray-50/80 dark:bg-slate-700/50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tanggal</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Hari</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Lokasi</th>
                                            <th scope="col" class="px-6py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Coach</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100 dark:divide-slate-700 bg-white dark:bg-slate-800">
                                        @forelse($attendances as $attendance)
                                            <tr class="hover:bg-blue-50/30 dark:hover:bg-slate-700/50 transition-colors">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-800 dark:text-gray-100">
                                                    {{ \Carbon\Carbon::parse($attendance->date)->isoFormat('DD MMM YYYY') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                                    <span class="font-medium text-gray-900 dark:text-gray-200">
                                                        {{ $attendance->schedule ? ucfirst(strtolower($attendance->schedule->day)) : 'N/A' }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                                    {{ $attendance->place ?? '-' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                                    {{ $attendance->coach->user->name ?? '-' }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">
                                                    <div class="flex flex-col items-center justify-center opacity-60">
                                                        <i data-feather="clipboard" class="w-12 h-12 text-gray-300 dark:text-gray-600 mb-3"></i>
                                                        <p class="text-sm">Belum ada riwayat kehadiran.</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- Data Raport --}}
                    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-md border-t-4 border-blue-500 overflow-hidden ring-1 ring-black/5 dark:ring-white/5">
                        <div class="px-6 py-5 border-b border-gray-100 dark:border-slate-700 bg-gradient-to-r from-white to-green-50/50 dark:from-slate-800 dark:to-slate-800">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="bg-blue-100 dark:bg-blue-900/30 p-2 rounded-lg">
                                        <i data-feather="bar-chart-2" class="text-blue-600 dark:text-blue-400 w-5 h-5"></i>
                                    </div>
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">
                                        Data Raport Terbaru
                                    </h3>
                                </div>
                                <button onclick="openRaportModal()" 
                                        class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-bold rounded-md text-white shell-blue hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all shadow-md shadow-blue-900/20 transform hover:-translate-y-0.5">
                                    <i data-feather="eye" class="w-3 h-3 mr-1.5"></i> Lihat Semua
                                </button>
                            </div>
                        </div>
                        <div class="overflow-hidden">
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead class="bg-gray-50/80 dark:bg-slate-700/50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Gaya & Jarak</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Bulan/Tahun</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Waktu</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Coach</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100 dark:divide-slate-700 bg-white dark:bg-slate-800">
                                        @forelse($raports->take(5) as $raport)
                                            <tr class="hover:bg-green-50/30 dark:hover:bg-slate-700/50 transition-colors">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-800 dark:text-gray-100">
                                                    {{ ucfirst(str_replace('_', ' ', $raport->gaya)) }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                                    {{ ucfirst($raport->month) }} {{ $raport->year }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300 font-mono">
                                                    {{ $raport->formatted_value ?? '-' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                                    {{ $raport->coach->user->name ?? '-' }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">
                                                    <div class="flex flex-col items-center justify-center opacity-60">
                                                        <i data-feather="bar-chart-2" class="w-12 h-12 text-gray-300 dark:text-gray-600 mb-3"></i>
                                                        <p class="text-sm">Belum ada data raport.</p>
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

                {{-- Grafik Performa --}}
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-md border-t-4 border-blue-500 overflow-hidden ring-1 ring-black/5 dark:ring-white/5">
                    <div class="px-6 py-5 border-b border-gray-100 dark:border-slate-700 bg-gradient-to-r from-white to-red-50/50 dark:from-slate-800 dark:to-slate-800">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="bg-blue-100 dark:bg-blue-900/30 p-2 rounded-lg">
                                    <i data-feather="trending-up" class="text-blue-600 dark:text-blue-400 w-5 h-5"></i>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">
                                    Grafik Performa
                                </h3>
                            </div>
                            <div class="flex space-x-2">
                                <select id="performanceGaya" class="border border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-gray-200 rounded-lg px-3 py-1 text-sm">
                                    <option value="gaya_bebas_50">Gaya Bebas 50m</option>
                                    <option value="gaya_bebas_100">Gaya Bebas 100m</option>
                                    <option value="gaya_bebas_200">Gaya Bebas 200m</option>
                                    <option value="gaya_dada_50">Gaya Dada 50m</option>
                                    <option value="gaya_dada_100">Gaya Dada 100m</option>
                                </select>
                                <input type="number" id="performanceYear" value="{{ now()->year }}" 
                                       class="w-20 border border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-gray-200 rounded-lg px-3 py-1 text-sm">
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div id="chartLoading" class="hidden text-center py-8">
                            <div class="loading-spinner mx-auto"></div>
                            <p class="mt-3 text-gray-600 dark:text-gray-400">Memuat data grafik...</p>
                        </div>
                        <div id="chartContainer">
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <div>
                                    <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-4 text-center">Waktu Tempuh (Detik)</h4>
                                    <canvas id="performanceChartValue" class="w-full" style="max-height: 300px;"></canvas>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-4 text-center">Volume & Intensitas</h4>
                                    <canvas id="performanceChartVolume" class="w-full" style="max-height: 300px;"></canvas>
                                </div>
                            </div>
                        </div>
                        <div id="chartError" class="hidden text-center py-8 text-red-600 dark:text-red-400">
                            <i data-feather="alert-triangle" class="w-12 h-12 mx-auto mb-3"></i>
                            <p>Gagal memuat data grafik. Silakan coba lagi.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

{{-- Modal untuk melihat semua raport --}}
<div id="raportModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-75 overflow-y-auto h-full w-full z-50">
    <div class="relative top-10 mx-auto p-5 border w-11/12 max-w-6xl shadow-lg rounded-xl bg-white dark:bg-slate-800">
        <div class="flex justify-between items-center pb-4 mb-4 border-b dark:border-slate-700">
            <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Semua Data Raport</h3>
            <button onclick="closeRaportModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-slate-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Gaya & Jarak</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Bulan/Tahun</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Waktu</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Volume</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Intensitas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Peaking</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Coach</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-slate-800 divide-y divide-gray-200 dark:divide-slate-700">
                    @foreach($raports as $raport)
                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-700">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                            {{ ucfirst(str_replace('_', ' ', $raport->gaya)) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ ucfirst($raport->month) }} {{ $raport->year }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 font-mono">
                            {{ $raport->formatted_value ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ $raport->volume }} m
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ $raport->intensity }}%
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ $raport->peaking }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ $raport->coach->user->name ?? '-' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="mt-6 flex justify-end">
            <button onclick="closeRaportModal()" class="px-4 py-2 bg-gray-200 dark:bg-slate-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-slate-600 transition-colors">
                Tutup
            </button>
        </div>
    </div>
</div>

<script>
// Pastikan Chart.js tersedia
function memberDashboard() {
    return {
        theme: localStorage.theme || 'system',
        performanceChartValue: null,
        performanceChartVolume: null,
        
        init() {
            this.updateTheme();
            this.loadPerformanceData();
            
            // Event listeners untuk filter performa
            const gayaSelect = document.getElementById('performanceGaya');
            const yearInput = document.getElementById('performanceYear');
            
            if (gayaSelect) {
                gayaSelect.addEventListener('change', () => this.loadPerformanceData());
            }
            if (yearInput) {
                yearInput.addEventListener('input', () => this.loadPerformanceData());
            }
        },
        
        updateTheme() {
            if (this.theme === 'dark' || (this.theme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
            localStorage.theme = this.theme;
        },
        
        async loadPerformanceData() {
            const gaya = document.getElementById('performanceGaya')?.value || 'gaya_bebas_50';
            const year = document.getElementById('performanceYear')?.value || new Date().getFullYear();
            
            // Show loading
            document.getElementById('chartLoading').classList.remove('hidden');
            document.getElementById('chartContainer').classList.add('hidden');
            document.getElementById('chartError').classList.add('hidden');
            
            try {
                const response = await fetch(`/member/performance-data?gaya=${gaya}&year=${year}`);
                const data = await response.json();
                
                document.getElementById('chartLoading').classList.add('hidden');
                
                if (data.success) {
                    document.getElementById('chartContainer').classList.remove('hidden');
                    this.updatePerformanceCharts(data.chartValue, data.chartVolume);
                } else {
                    document.getElementById('chartError').classList.remove('hidden');
                    console.error('Failed to load performance data:', data.message);
                }
            } catch (error) {
                document.getElementById('chartLoading').classList.add('hidden');
                document.getElementById('chartError').classList.remove('hidden');
                console.error('Error loading performance data:', error);
            }
        },
        
        updatePerformanceCharts(valueData, volumeData) {
            const isDark = document.documentElement.classList.contains('dark');
            const textColor = isDark ? '#e5e7eb' : '#374151';
            const gridColor = isDark ? '#374151' : '#e5e7eb';
            
            // Destroy existing charts
            if (this.performanceChartValue) {
                this.performanceChartValue.destroy();
            }
            if (this.performanceChartVolume) {
                this.performanceChartVolume.destroy();
            }
            
            // Create new charts
            const ctx1 = document.getElementById('performanceChartValue');
            if (ctx1) {
                this.performanceChartValue = new Chart(ctx1, {
                    type: 'line',
                    data: valueData,
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
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
                        plugins: {
                            legend: {
                                labels: { color: textColor }
                            }
                        }
                    }
                });
            }
            
            const ctx2 = document.getElementById('performanceChartVolume');
            if (ctx2) {
                this.performanceChartVolume = new Chart(ctx2, {
                    type: 'line',
                    data: volumeData,
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
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
                        plugins: {
                            legend: {
                                labels: { color: textColor }
                            }
                        }
                    }
                });
            }
        }
    }
}

function openRaportModal() {
    document.getElementById('raportModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeRaportModal() {
    document.getElementById('raportModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Initialize Feather Icons
document.addEventListener('DOMContentLoaded', function() {
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
});
</script>
@endif

</body>
</html>