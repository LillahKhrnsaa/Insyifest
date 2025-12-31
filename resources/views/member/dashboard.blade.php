<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50 scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Member Dashboard - Cikampek Swimming Club</title>
    
    {{-- Vite Assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    {{-- Font Nunito --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=nunito:400,500,600,700,800,900&display=swap" rel="stylesheet">
    
    <style>
        /* Custom Styles - Sama persis dengan Coach Dashboard */
        body { font-family: 'Nunito', sans-serif; }
        [x-cloak] { display: none !important; }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { 
            background: #cbd5e1; 
            border-radius: 3px; 
        }
        ::-webkit-scrollbar-thumb:hover { background: #0891b2; }
        
        /* Animations */
        .fade-in { animation: fadeIn 0.5s ease-out forwards; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Card Hover Effects */
        .card-hover { transition: all 0.3s ease; }
        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px -8px rgba(8, 145, 178, 0.15);
        }
        
        /* Gradient Border */
        .gradient-border {
            position: relative;
            background: white;
            border-radius: 16px;
        }
        .gradient-border::before {
            content: '';
            position: absolute;
            top: -1px; left: -1px; right: -1px; bottom: -1px;
            background: linear-gradient(135deg, #0891b2 0%, #2563eb 100%);
            border-radius: 17px;
            z-index: -1;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .gradient-border:hover::before { opacity: 1; }
        
        /* Table Styles */
        .table-header { background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); }
        .table-row:hover { background-color: #f8fafc; }
        
        /* Status Badge */
        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .status-badge::before {
            content: '';
            width: 8px; height: 8px;
            border-radius: 50%;
            display: inline-block;
        }
        .status-active { background-color: #f0fdf4; color: #16a34a; }
        .status-active::before { background-color: #16a34a; }
        .status-inactive { background-color: #f1f5f9; color: #64748b; }
        .status-inactive::before { background-color: #64748b; }
        
        /* Button Styles */
        .btn-primary {
            background: linear-gradient(135deg, #0891b2 0%, #2563eb 100%);
            color: white;
            font-weight: 700;
            padding: 10px 20px;
            border-radius: 10px;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px -4px rgba(8, 145, 178, 0.3);
        }
        
        /* Input Styles */
        .input-field {
            background: #f8fafc;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        .input-field:focus {
            outline: none;
            border-color: #0891b2;
            background: white;
            box-shadow: 0 0 0 3px rgba(8, 145, 178, 0.1);
        }

        .sidebar-card {
            height: fit-content;
            position: sticky;
            top: 6rem;
        }
    </style>
</head>
<body class="h-full text-slate-700 antialiased">

@if(!isset($member))
    {{-- Error State --}}
    <div class="min-h-screen flex items-center justify-center bg-slate-50">
        <div class="max-w-md w-full bg-white rounded-xl shadow-lg p-8 text-center border border-slate-200">
            <div class="w-20 h-20 mx-auto mb-4 bg-red-50 rounded-full flex items-center justify-center">
                <i data-feather="alert-triangle" class="text-red-500 w-10 h-10"></i>
            </div>
            <h2 class="text-2xl font-bold text-slate-800 mb-2">Data Tidak Ditemukan</h2>
            <p class="text-slate-500 mb-6">Akun Anda belum terhubung dengan data member. Hubungi admin.</p>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-primary w-full flex justify-center items-center gap-2">
                    <i data-feather="log-out" class="w-4 h-4"></i> Keluar
                </button>
            </form>
        </div>
    </div>
@else

    {{-- Wrapper Utama --}}
    <div x-data="{ 
            showRaportModal: false,
            toggleRaportModal() {
                this.showRaportModal = !this.showRaportModal;
                if(this.showRaportModal) {
                    document.body.style.overflow = 'hidden';
                } else {
                    document.body.style.overflow = 'auto';
                }
            }
         }"
         class="min-h-screen"
    >
        {{-- Navbar --}}
        <nav class="bg-white border-b border-slate-100 shadow-sm sticky top-0 z-50">
            <div class="w-full px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    {{-- Logo --}}
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('images/logocsc.png') }}" alt="CSC Logo" class="h-10 w-auto">
                        <div class="hidden md:block">
                            <h1 class="text-lg font-bold text-slate-800 leading-tight">Cikampek Swimming Club</h1>
                            <p class="text-xs text-slate-500">Member Dashboard</p>
                        </div>
                    </div>

                    {{-- User Menu --}}
                    <div class="flex items-center gap-4">
                        @auth
                        <div class="flex items-center gap-3">
                            <div class="text-right hidden sm:block">
                                <p class="text-sm font-bold text-slate-800">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-cyan-600 font-medium">Member</p>
                            </div>
                            
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-bold rounded-lg transition-colors flex items-center gap-2">
                                    <i data-feather="log-out" class="w-4 h-4"></i>
                                    <span class="hidden sm:inline">Keluar</span>
                                </button>
                            </form>
                        </div>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        {{-- Main Content --}}
        <main class="py-6">
            <div class="mx-auto px-4 sm:px-6 lg:px-8 max-w-full">
                
                {{-- Header Section --}}
                <div class="mb-8 fade-in">
                    <div class="bg-gradient-to-r from-cyan-50 to-blue-50 rounded-2xl p-6 border border-cyan-100">
                        <div class="flex flex-col md:flex-row md:items-center justify-between">
                            <div class="flex items-center gap-6">
                                {{-- Foto Profil Member --}}
                                <div class="hidden md:block w-20 h-20 rounded-full bg-white p-1 shadow-md">
                                    @if ($member->user->photo_url)
                                        <img src="{{ $member->user->photo_url }}" class="w-full h-full rounded-full object-cover">
                                    @else
                                        <div class="w-full h-full rounded-full bg-slate-100 flex items-center justify-center text-slate-400">
                                            <i data-feather="user" class="w-8 h-8"></i>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <h1 class="text-2xl md:text-3xl font-bold text-slate-800 mb-1">
                                        Halo, {{ explode(' ', Auth::user()->name)[0] }}
                                    </h1>
                                    <div class="flex items-center gap-3 text-slate-600 text-sm">
                                        <span class="flex items-center gap-1">
                                            <i data-feather="mail" class="w-3 h-3"></i> {{ $member->user->email }}
                                        </span>
                                        <span class="w-1 h-1 bg-slate-400 rounded-full"></span>
                                        <span class="status-badge {{ $member->status == 'AKTIF' ? 'status-active' : 'status-inactive' }}">
                                            {{ $member->status }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4 md:mt-0 text-right">
                                <p class="text-sm text-slate-500">Paket Latihan</p>
                                <p class="text-lg font-bold text-cyan-600">{{ $member->trainingPackage->name ?? 'Tidak ada' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Alerts --}}
                @if (session('success') || session('error'))
                    <div class="mb-8 fade-in" x-data="{ show: true }" x-show="show" x-transition>
                        @if (session('success'))
                            <div class="rounded-xl bg-gradient-to-r from-cyan-50 to-blue-50 border border-cyan-200 p-4 flex items-start gap-3">
                                <div class="bg-cyan-100 p-2 rounded-full text-cyan-600">
                                    <i data-feather="check-circle" class="w-5 h-5"></i>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-sm font-bold text-slate-800">Berhasil</h3>
                                    <p class="text-sm text-slate-600 mt-0.5">{{ session('success') }}</p>
                                </div>
                                <button @click="show = false" class="text-slate-400 hover:text-slate-600">
                                    <i data-feather="x" class="w-4 h-4"></i>
                                </button>
                            </div>
                        @endif
                    </div>
                @endif

                {{-- Quick Stats --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8 fade-in" style="animation-delay: 0.1s;">
                    {{-- Total Kehadiran --}}
                    <div class="bg-white rounded-xl p-5 border border-slate-200 card-hover">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-slate-500 mb-1">Total Kehadiran</p>
                                <h3 class="text-2xl font-bold text-slate-800">{{ $totalAttendances ?? 0 }}</h3>
                            </div>
                            <div class="w-12 h-12 bg-cyan-50 rounded-lg flex items-center justify-center">
                                <i data-feather="calendar" class="w-6 h-6 text-cyan-600"></i>
                            </div>
                        </div>
                        <div class="mt-3 pt-3 border-t border-slate-100">
                            <p class="text-xs text-slate-500">Sesi latihan diikuti</p>
                        </div>
                    </div>

                    {{-- Data Raport --}}
                    <div class="bg-white rounded-xl p-5 border border-slate-200 card-hover">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-slate-500 mb-1">Data Raport</p>
                                <h3 class="text-2xl font-bold text-slate-800">{{ $totalRaports ?? 0 }}</h3>
                            </div>
                            <div class="w-12 h-12 bg-green-50 rounded-lg flex items-center justify-center">
                                <i data-feather="file-text" class="w-6 h-6 text-green-600"></i>
                            </div>
                        </div>
                        <div class="mt-3 pt-3 border-t border-slate-100">
                            <p class="text-xs text-slate-500">Catatan performa</p>
                        </div>
                    </div>

                    {{-- Coach --}}
                    <div class="bg-white rounded-xl p-5 border border-slate-200 card-hover">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-slate-500 mb-1">Coach</p>
                                <h3 class="text-2xl font-bold text-slate-800">{{ $assignedCoaches->count() }}</h3>
                            </div>
                            <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center">
                                <i data-feather="users" class="w-6 h-6 text-blue-600"></i>
                            </div>
                        </div>
                        <div class="mt-3 pt-3 border-t border-slate-100">
                            <p class="text-xs text-slate-500">Pelatih aktif</p>
                        </div>
                    </div>

                    {{-- Jadwal Minggu Ini --}}
                    <div class="bg-white rounded-xl p-5 border border-slate-200 card-hover">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-slate-500 mb-1">Jadwal</p>
                                <h3 class="text-2xl font-bold text-slate-800">{{ $trainingSchedules->count() }}</h3>
                            </div>
                            <div class="w-12 h-12 bg-purple-50 rounded-lg flex items-center justify-center">
                                <i data-feather="clock" class="w-6 h-6 text-purple-600"></i>
                            </div>
                        </div>
                        <div class="mt-3 pt-3 border-t border-slate-100">
                            <p class="text-xs text-slate-500">Sesi minggu ini</p>
                        </div>
                    </div>
                </div>

                {{-- Main Grid Layout --}}
                <div class="mb-8 fade-in" style="animation-delay: 0.2s;">
                    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-6">
                        
                        <div class="space-y-6">

                        {{-- Grafik Performa --}}
                        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden card-hover p-4">
                            <div class="px-4 py-2 border-b border-slate-100 bg-slate-50 flex justify-between items-center flex-wrap gap-2">
                                <h3 class="font-bold text-slate-800 flex items-center gap-2">
                                    <i data-feather="trending-up" class="w-5 h-5 text-red-500"></i>
                                    Grafik Performa
                                </h3>
                                <div class="flex gap-2">
                                    <select id="performanceGaya" class="input-field text-xs py-1 px-2">
                                        @forelse($existingStyles as $style)
                                            <option value="{{ $style }}">
                                                {{ ucwords(str_replace('_', ' ', $style)) }}
                                            </option>
                                        @empty
                                            <option value="" disabled>Belum ada data gaya</option>
                                        @endforelse
                                    </select>
                                    
                                    <input type="number" id="performanceYear" value="{{ now()->year }}" class="input-field text-xs py-1 px-2 w-20">
                                </div>
                            </div>
                            
                            <div class="p-5">
                                <div id="chartLoading" class="text-center py-10 hidden">
                                    <p class="text-slate-400 text-sm">Memuat data...</p>
                                </div>
                                <div id="chartContainer" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="relative w-full h-64">
                                        <h4 class="text-center text-xs font-bold text-slate-400 uppercase mb-2">Waktu (Detik)</h4>
                                        <canvas id="performanceChartValue"></canvas>
                                    </div>
                                    <div class="relative w-full h-64">
                                        <h4 class="text-center text-xs font-bold text-slate-400 uppercase mb-2">Volume & Intensitas</h4>
                                        <canvas id="performanceChartVolume"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        </div>

                        {{-- jadwal dan history --}}
                        <div class="xl:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            {{-- Card Jadwal --}}
                            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden card-hover">
                                <div class="px-5 py-4 border-b border-slate-100 bg-slate-50">
                                    <h3 class="font-bold text-slate-800 flex items-center gap-2">
                                        <i data-feather="calendar" class="w-5 h-5 text-purple-600"></i>
                                        Jadwal Latihan
                                    </h3>
                                </div>
                                <div class="divide-y divide-slate-100 max-h-[400px] overflow-y-auto">
                                    @forelse($trainingSchedules as $schedule)
                                        <div class="px-5 py-4 hover:bg-slate-50 transition-colors">
                                            <div class="flex justify-between items-start mb-1">
                                                <h4 class="font-bold text-slate-800">{{ ucfirst($schedule->day) }}</h4>
                                                <span class="text-xs bg-slate-100 text-slate-600 px-2 py-0.5 rounded">
                                                    {{ $schedule->time ? \Carbon\Carbon::parse($schedule->time)->format('H:i') : '-' }}
                                                </span>
                                            </div>
                                            <div class="text-xs text-slate-500 flex items-center gap-1 mb-2">
                                                <i data-feather="map-pin" class="w-3 h-3"></i> {{ $schedule->place ?? 'Kolam Utama' }}
                                            </div>
                                            <div class="flex flex-wrap gap-1">
                                                @foreach($schedule->coaches as $coach)
                                                    <span class="text-[10px] bg-blue-50 text-blue-600 px-2 py-0.5 rounded-full border border-blue-100">
                                                        {{ $coach->user->name }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </div>
                                    @empty
                                        <div class="px-5 py-8 text-center text-slate-400">
                                            <p>Tidak ada jadwal</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                            {{-- Riwayat Kehadiran --}}
                            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden card-hover">
                                <div class="px-5 py-4 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                                    <h3 class="font-bold text-slate-800 flex items-center gap-2">
                                        <i data-feather="clock" class="w-5 h-5 text-blue-600"></i>
                                        Riwayat Kehadiran
                                    </h3>
                                </div>
                                <div class="overflow-x-auto">
                                    <table class="w-full min-w-[600px]">
                                        <thead class="table-header text-left text-xs text-slate-500 font-bold uppercase tracking-wider">
                                            <tr>
                                                <th class="px-5 py-3">Tanggal</th>
                                                <th class="px-5 py-3">Hari</th>
                                                <th class="px-5 py-3">Lokasi</th>
                                                <th class="px-5 py-3">Coach</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-100">
                                            @forelse($attendances->take(5) as $attendance)
                                                <tr class="table-row hover:bg-slate-50 transition-colors">
                                                    <td class="px-5 py-4 font-bold text-slate-800">
                                                        {{ \Carbon\Carbon::parse($attendance->date)->isoFormat('D MMM Y') }}
                                                    </td>
                                                    <td class="px-5 py-4 text-slate-600">
                                                        {{ $attendance->schedule ? ucfirst($attendance->schedule->day) : '-' }}
                                                    </td>
                                                    <td class="px-5 py-4 text-slate-600 flex items-center gap-1">
                                                        <i data-feather="map-pin" class="w-3 h-3 text-slate-400"></i>
                                                        {{ $attendance->place ?? '-' }}
                                                    </td>
                                                    <td class="px-5 py-4 text-slate-600">
                                                        {{ $attendance->coach->user->name ?? '-' }}
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="px-5 py-8 text-center text-slate-400">
                                                        <p>Belum ada data kehadiran</p>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        {{-- coach dan raport --}}
                        <div class="xl:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Card Coach --}}
                            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden card-hover">
                                <div class="px-5 py-4 border-b border-slate-100 bg-slate-50">
                                    <h3 class="font-bold text-slate-800 flex items-center gap-2">
                                        <i data-feather="users" class="w-5 h-5 text-green-600"></i>
                                        Coach Saya
                                    </h3>
                                </div>
                                <div class="p-4 space-y-3">
                                    @forelse($assignedCoaches as $coach)
                                        <div class="flex items-center gap-3 p-2 hover:bg-slate-50 rounded-lg transition-colors border border-transparent hover:border-slate-100">
                                            <div class="w-10 h-10 rounded-full bg-slate-200 overflow-hidden flex-shrink-0">
                                                @if ($coach->user->photo_url)
                                                    <img src="{{ $coach->user->photo_url }}" class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center text-slate-400">
                                                        <i data-feather="user" class="w-5 h-5"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="overflow-hidden">
                                                <h4 class="font-bold text-slate-800 text-sm truncate">{{ $coach->user->name }}</h4>
                                                <p class="text-xs text-slate-500 truncate">{{ $coach->user->email }}</p>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-center text-slate-400 text-sm py-2">Belum ada coach assigned.</p>
                                    @endforelse
                                </div>
                            </div>

                            {{-- Tombol Lihat Semua Raport --}}
                            <div class="bg-gradient-to-r from-blue-50 to-cyan-50 rounded-xl border border-blue-100 p-5 text-center">
                                <h4 class="text-sm font-bold text-blue-800 mb-2">Lihat Semua Raport</h4>
                                <p class="text-xs text-blue-600 mb-4">Cek detail perkembangan latihanmu</p>
                                <button @click="toggleRaportModal()" class="w-full btn-primary text-sm flex justify-center items-center gap-2">
                                    <i data-feather="file-text" class="w-4 h-4"></i> Buka Raport
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </main>

        {{-- Modal Raport Member --}}
        <div x-show="showRaportModal" x-cloak class="relative z-50">
            <div x-show="showRaportModal" x-transition.opacity class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm"></div>
            
            <div class="fixed inset-0 z-10 overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4">
                    
                    {{-- PERUBAHAN DISINI: max-w-5xl diganti menjadi max-w-4xl agar lebih ramping --}}
                    <div x-show="showRaportModal" 
                         @click.away="toggleRaportModal()" 
                         x-transition.scale 
                         x-data="raportTable({{ json_encode($raports) }})"
                         class="relative bg-white w-full max-w-4xl rounded-xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
                        
                        {{-- Header Modal --}}
                        <div class="bg-gradient-to-r from-cyan-500 to-blue-600 px-6 py-4 flex justify-between items-center shrink-0">
                            <div>
                                <h3 class="text-lg font-bold text-white">Semua Data Raport</h3>
                                <p class="text-xs text-cyan-100 mt-1">Riwayat lengkap performa latihan</p>
                            </div>
                            <button @click="toggleRaportModal()" class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center text-white hover:bg-white/20 transition-colors">
                                <i data-feather="x" class="w-4 h-4"></i>
                            </button>
                        </div>

                        {{-- Section Filter --}}
                        <div class="px-6 py-4 bg-white border-b border-slate-100 flex flex-wrap gap-4 items-center justify-between">
                            <div class="flex flex-wrap gap-3 w-full sm:w-auto">
                                <div class="relative">
                                    <select x-model="filterGaya" class="input-field py-2 pl-3 pr-8 text-sm w-full sm:w-64 cursor-pointer">
                                        <option value="">Semua Kategori</option>
                                        @foreach($existingStyles as $style)
                                            <option value="{{ $style }}">
                                                {{ ucwords(str_replace('_', ' ', $style)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            {{-- Counter Data --}}
                            <div class="text-sm text-slate-500 font-medium">
                                <span x-text="filteredItems.length" class="font-bold text-slate-800"></span> data ditemukan
                            </div>
                        </div>

                        {{-- Tabel Data --}}
                        <div class="p-0 overflow-y-auto bg-slate-50 flex-1">
                            <div class="overflow-x-auto">
                                <table class="w-full min-w-[800px]">
                                    <thead class="bg-slate-100 border-b border-slate-200 sticky top-0 z-10 shadow-sm">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Gaya & Jarak</th>
                                            <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Periode</th>
                                            <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Waktu</th>
                                            <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Volume</th>
                                            <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Coach</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-slate-100">
                                        {{-- Loop Data Kosong --}}
                                        <tr x-show="filteredItems.length === 0">
                                            <td colspan="5" class="px-6 py-8 text-center text-slate-400">
                                                Tidak ada data yang cocok dengan filter.
                                            </td>
                                        </tr>

                                        {{-- Loop Data Alpine --}}
                                        <template x-for="item in filteredItems" :key="item.id">
                                            <tr class="hover:bg-slate-50 transition-colors group">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-slate-800">
                                                    <span x-text="formatGaya(item.gaya)"></span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                                                    <span x-text="capitalize(item.month)"></span> <span x-text="item.year"></span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-cyan-600 font-bold bg-cyan-50/50 rounded-lg w-fit">
                                                    <span x-text="item.formatted_value || '-'"></span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                                                    <div class="flex flex-col">
                                                        <span x-text="item.volume + ' m'"></span>
                                                        <span class="text-[10px] text-slate-400" x-text="'Intensitas: ' + item.intensity + '%'"></span>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                                                    <div class="flex items-center gap-2">
                                                        <div class="w-6 h-6 rounded-full bg-slate-200 overflow-hidden flex-shrink-0">
                                                            <template x-if="item.coach && item.coach.user && item.coach.user.photo_url">
                                                                <img :src="item.coach.user.photo_url" class="w-full h-full object-cover">
                                                            </template>
                                                            <template x-if="!item.coach || !item.coach.user || !item.coach.user.photo_url">
                                                                <div class="w-full h-full flex items-center justify-center text-slate-400">
                                                                    <i data-feather="user" class="w-3 h-3"></i>
                                                                </div>
                                                            </template>
                                                        </div>
                                                        <span class="truncate max-w-[120px]" x-text="item.coach && item.coach.user ? item.coach.user.name : '-'"></span>
                                                    </div>
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Script untuk Chart & Feather Icons --}}
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('raportTable', (initialData) => ({
                items: initialData,
                filterGaya: '',
                filterJarak: '',

                get filteredItems() {
                    return this.items.filter(item => {
                        const matchGaya = this.filterGaya === '' || item.gaya === this.filterGaya;
                        return matchGaya;
                    });
                },

                // Helper functions untuk formatting tampilan
                formatGaya(gayaString) {
                    if (!gayaString) return '-';
                    return gayaString.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                },

                capitalize(str) {
                    if (!str) return '';
                    return str.charAt(0).toUpperCase() + str.slice(1);
                }
            }));
        });

        // VARIABLE GLOBAL UNTUK MENYIMPAN INSTANCE CHART
        window.myChartValue = null;
        window.myChartVolume = null;

        document.addEventListener('DOMContentLoaded', function() {
            if (typeof feather !== 'undefined') feather.replace();
            
            initMemberCharts();

            // Event Listeners untuk Filter
            const gayaSelect = document.getElementById('performanceGaya');
            const yearInput = document.getElementById('performanceYear');
            
            if (gayaSelect) gayaSelect.addEventListener('change', initMemberCharts);
            if (yearInput) yearInput.addEventListener('input', initMemberCharts);
        });

        function initMemberCharts() {
        const selectGaya = document.getElementById('performanceGaya');
            let gaya = selectGaya?.value;
            
            if (!gaya && selectGaya && selectGaya.options.length > 0) {
                gaya = selectGaya.options[0].value;
            }
            
            if (!gaya) {
                console.warn('Tidak ada data gaya renang untuk ditampilkan di grafik.');
                return; 
            }
            const year = document.getElementById('performanceYear')?.value || new Date().getFullYear();
            const container = document.getElementById('chartContainer');
            const loading = document.getElementById('chartLoading');

            if(loading) loading.classList.remove('hidden');
            if(container) container.classList.add('opacity-50');

            fetch(`/member/performance-data?gaya=${gaya}&year=${year}`)
                .then(response => response.json())
                .then(data => {
                    if(loading) loading.classList.add('hidden');
                    if(container) container.classList.remove('opacity-50');

                    if (data.success) {
                        updateCharts(data.chartValue, data.chartVolume);
                    }
                })
                .catch(err => console.error(err));
        }

        function updateCharts(valueData, volumeData) {
            if (typeof Chart === 'undefined') {
                console.error("Chart.js belum dimuat.");
                return;
            }

            const canvasValue = document.getElementById('performanceChartValue');
            const canvasVolume = document.getElementById('performanceChartVolume');

            // --- PERBAIKAN LOOPING CHART DISINI ---
            // 1. Hapus instance lama dari variabel global jika ada
            if (window.myChartValue instanceof Chart) {
                window.myChartValue.destroy();
            }
            if (window.myChartVolume instanceof Chart) {
                window.myChartVolume.destroy();
            }

            // 2. Extra safety: Cek instance yg nempel di canvas via Chart.js registry
            // Ini untuk memastikan bersih total
            const existing1 = Chart.getChart(canvasValue);
            if (existing1) existing1.destroy();
            
            const existing2 = Chart.getChart(canvasVolume);
            if (existing2) existing2.destroy();
            // --------------------------------------

            const colorText = '#64748b'; 
            const colorGrid = '#e2e8f0'; 
            const primaryColor = '#0891b2'; 
            const barColors = ['#0891b2', '#10b981', '#8b5cf6', '#f59e0b'];

            // Chart Waktu (Line)
            window.myChartValue = new Chart(canvasValue.getContext('2d'), {
                type: 'line',
                data: valueData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false, // Wajib false agar ngikutin tinggi parent div
                    elements: {
                        line: { borderColor: primaryColor, borderWidth: 3, tension: 0.4 }, 
                        point: { backgroundColor: '#ffffff', borderColor: primaryColor, borderWidth: 2, radius: 4 }
                    },
                    scales: {
                        y: { reverse: true, ticks: { color: colorText, font: {size:10} }, grid: { color: colorGrid, drawBorder: false } },
                        x: { ticks: { color: colorText, font: {size:10} }, grid: { display: false } }
                    },
                    plugins: { legend: { display: false } }
                }
            });

            // Chart Volume (Bar)
            if (volumeData && volumeData.datasets) {
                volumeData.datasets.forEach((dataset, index) => {
                    dataset.backgroundColor = barColors[index % barColors.length];
                    dataset.borderRadius = 4;
                    dataset.barPercentage = 0.6;
                });
            }

            window.myChartVolume = new Chart(canvasVolume.getContext('2d'), {
                type: 'bar',
                data: volumeData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false, // Wajib false agar ngikutin tinggi parent div
                    scales: {
                        y: { beginAtZero: true, ticks: { color: colorText, font: {size:10} }, grid: { color: colorGrid, drawBorder: false } },
                        x: { ticks: { color: colorText, font: {size:10} }, grid: { display: false } }
                    },
                    plugins: { legend: { display: true, labels: { boxWidth: 10, font: {size:10} } } }
                }
            });
        }
    </script>
@endif

</body>
</html>