<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50 scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Member Dashboard - Cikampek Swimming Club</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=nunito:400,500,600,700,800,900&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Nunito', sans-serif; }
        [x-cloak] { display: none !important; }
        
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #0891b2; }
        
        .fade-in { animation: fadeIn 0.5s ease-out forwards; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .slide-up { animation: slideUp 0.3s ease-out; }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .card-hover { transition: all 0.3s ease; }
        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px -8px rgba(8, 145, 178, 0.15);
        }
        
        .status-badge {
            padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 700;
            display: inline-flex; align-items: center; gap: 6px;
        }
        .status-badge::before {
            content: ''; width: 8px; height: 8px; border-radius: 50%; display: inline-block;
        }
        .status-active { background-color: #f0fdf4; color: #16a34a; }
        .status-active::before { background-color: #16a34a; }
        .status-inactive { background-color: #f1f5f9; color: #64748b; }
        .status-inactive::before { background-color: #64748b; }
        
        .btn-primary {
            background: linear-gradient(135deg, #0891b2 0%, #2563eb 100%);
            color: white; font-weight: 700; padding: 10px 20px; border-radius: 10px;
            transition: all 0.3s ease; border: none; cursor: pointer;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px -4px rgba(8, 145, 178, 0.3);
        }
        
        .input-field {
            background: #f8fafc; border: 2px solid #e2e8f0; border-radius: 10px;
            padding: 12px 16px; font-size: 14px; transition: all 0.3s ease;
        }
        .input-field:focus {
            outline: none; border-color: #0891b2; background: white;
            box-shadow: 0 0 0 3px rgba(8, 145, 178, 0.1);
        }
        
        .modal-overlay {
            background: rgba(15, 23, 42, 0.5); backdrop-filter: blur(4px);
        }
    </style>
</head>
<body class="h-full text-slate-700 antialiased">

@if(!isset($member))
    <div class="min-h-screen flex items-center justify-center bg-slate-50">
        <div class="max-w-md w-full bg-white rounded-xl shadow-lg p-8 text-center border border-slate-200">
            <h2 class="text-2xl font-bold text-slate-800 mb-2">Data Tidak Ditemukan</h2>
            <p class="text-slate-500 mb-6">Akun Anda belum terhubung dengan data member.</p>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-primary w-full">Keluar</button>
            </form>
        </div>
    </div>
@else

    <div x-data="{}" class="min-h-screen">
        {{-- Navbar --}}
        <nav class="bg-white border-b border-slate-100 shadow-sm sticky top-0 z-50">
            <div class="w-full px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('images/logocsc.png') }}" alt="CSC Logo" class="h-10 w-auto">
                        <div class="hidden md:block">
                            <h1 class="text-lg font-bold text-slate-800 leading-tight">Cikampek Swimming Club</h1>
                            <p class="text-xs text-slate-500">Member Dashboard</p>
                        </div>
                    </div>
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
                    </div>

                    {{-- Jadwal --}}
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
                    </div>
                </div>

                {{-- Konten Utama --}}
                <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-6">
                    
                    {{-- Kolom Kiri: Tombol Akses Raport --}}
                    <div class="space-y-6">
                        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden card-hover p-6 text-center">
                            <div class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i data-feather="bar-chart-2" class="w-8 h-8 text-blue-600"></i>
                            </div>
                            <h3 class="text-lg font-bold text-slate-800 mb-2">Raport Performa</h3>
                            <p class="text-sm text-slate-500 mb-6">Lihat grafik perkembangan waktu, volume, dan intensitas latihan Anda.</p>
                            <button onclick="openRaportModal()" class="w-full btn-primary flex justify-center items-center gap-2">
                                <i data-feather="eye" class="w-4 h-4"></i> Buka Raport Lengkap
                            </button>
                        </div>

                        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden card-hover p-6 text-center">
                            <div class="w-16 h-16 bg-pink-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i data-feather="activity" class="w-8 h-8 text-pink-600"></i>
                            </div>
                            <h3 class="text-lg font-bold text-slate-800 mb-2">Tes Fisik</h3>
                            <p class="text-sm text-slate-500 mb-6">Lihat hasil analisis kondisi fisik (VO2 Max, Sprint, Agility).</p>
                            <button onclick="openPhysicalModal()" class="w-full py-2.5 bg-pink-600 hover:bg-pink-700 text-white rounded-xl font-bold transition-colors flex justify-center items-center gap-2">
                                <i data-feather="eye" class="w-4 h-4"></i> Lihat Hasil Tes
                            </button>
                        </div>
                    </div>

                    {{-- Kolom Tengah & Kanan: Jadwal & Riwayat (Sama seperti sebelumnya) --}}
                    <div class="xl:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Jadwal --}}
                        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden card-hover">
                            <div class="px-5 py-4 border-b border-slate-100 bg-slate-50">
                                <h3 class="font-bold text-slate-800 flex items-center gap-2">
                                    <i data-feather="calendar" class="w-5 h-5 text-purple-600"></i> Jadwal Latihan
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
                                    </div>
                                @empty
                                    <div class="px-5 py-8 text-center text-slate-400">
                                        <p>Tidak ada jadwal</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        {{-- Riwayat --}}
                        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden card-hover">
                            <div class="px-5 py-4 border-b border-slate-100 bg-slate-50">
                                <h3 class="font-bold text-slate-800 flex items-center gap-2">
                                    <i data-feather="clock" class="w-5 h-5 text-blue-600"></i> Riwayat Kehadiran
                                </h3>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead class="table-header text-left text-xs text-slate-500 font-bold uppercase tracking-wider">
                                        <tr>
                                            <th class="px-5 py-3">Tanggal</th>
                                            <th class="px-5 py-3">Lokasi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">
                                        @forelse($attendances->take(5) as $attendance)
                                            <tr class="table-row hover:bg-slate-50 transition-colors">
                                                <td class="px-5 py-4 font-bold text-slate-800 text-sm">
                                                    {{ \Carbon\Carbon::parse($attendance->date)->isoFormat('D MMM Y') }}
                                                </td>
                                                <td class="px-5 py-4 text-slate-600 text-sm">
                                                    {{ $attendance->place ?? '-' }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="2" class="px-5 py-8 text-center text-slate-400">Belum ada riwayat</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </main>

        {{-- ========================================== --}}
        {{-- MODAL RAPORT (READ ONLY) --}}
        {{-- ========================================== --}}
        <div id="raportModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
            <div class="fixed inset-0 modal-overlay transition-opacity" onclick="closeRaportModal()"></div>
            <div class="flex min-h-screen items-center justify-center px-4 py-10 text-center sm:px-6">
                <div class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-xl transition-all sm:w-full sm:max-w-6xl flex flex-col max-h-[85vh]">
                    
                    {{-- Header --}}
                    <div class="bg-gradient-to-r from-cyan-500 to-blue-600 px-5 py-4 flex justify-between items-center shrink-0">
                        <div>
                            <h3 class="text-lg font-bold text-white">Raport Performa Saya</h3>
                            <p class="text-xs text-cyan-100 mt-0.5">Pantau perkembangan latihan Anda</p>
                        </div>
                        <button onclick="closeRaportModal()" class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center text-white hover:bg-white/20 transition-colors">
                            <i data-feather="x" class="w-5 h-5"></i>
                        </button>
                    </div>

                    {{-- Body --}}
                    <div class="p-5 space-y-6 overflow-y-auto custom-scrollbar">
                        {{-- Filter --}}
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Kategori Gaya</label>
                                    <select id="gaya" class="input-field w-full py-2 text-sm bg-white">
                                        <option value="" selected disabled>-- Pilih Gaya Renang --</option>
                                        @forelse($existingStyles as $style)
                                            <option value="{{ $style }}">{{ ucwords(str_replace('_', ' ', $style)) }}</option>
                                        @empty
                                            <option value="" disabled>Belum ada data</option>
                                        @endforelse
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Tahun</label>
                                    <input type="number" id="year" value="{{ now()->year }}" class="input-field w-full py-2 text-sm bg-white">
                                </div>
                                <div class="flex items-end">
                                    <button onclick="loadRaportData()" class="w-full btn-primary py-2 flex items-center justify-center gap-2 text-sm shadow-sm hover:shadow-md">
                                        <i data-feather="refresh-cw" class="w-4 h-4"></i> Muat Data
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Charts --}}
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
                                <h4 class="font-bold text-slate-800 mb-4 text-xs uppercase tracking-wide flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-cyan-500"></span> Grafik Waktu (Detik)
                                </h4>
                                <div class="h-56 relative w-full"><canvas id="chartValue"></canvas></div>
                            </div>
                            <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
                                <h4 class="font-bold text-slate-800 mb-4 text-xs uppercase tracking-wide flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Volume & Intensitas
                                </h4>
                                <div class="h-56 relative w-full"><canvas id="chartVolume"></canvas></div>
                            </div>
                        </div>

                        {{-- Detail Info --}}
                        <div id="raport-detail"></div>

                        {{-- Tabel --}}
                        <div class="border border-slate-200 rounded-xl overflow-hidden shadow-sm">
                            <div class="max-h-64 overflow-y-auto">
                                <table id="raport-table" class="w-full text-sm">
                                    <thead class="bg-slate-50 text-slate-500 uppercase text-xs font-bold sticky top-0 z-10 shadow-sm">
                                        <tr>
                                            <th class="px-5 py-3 text-left bg-slate-50">Bulan</th>
                                            <th class="px-5 py-3 text-left bg-slate-50">Waktu</th>
                                            <th class="px-5 py-3 text-left bg-slate-50">Volume</th>
                                            <th class="px-5 py-3 text-left bg-slate-50">Intensitas</th>
                                            <th class="px-5 py-3 text-left bg-slate-50">Peaking</th>
                                            <th class="px-5 py-3 text-left bg-slate-50">Catatan Coach</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 bg-white"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ========================================== --}}
        {{-- MODAL FISIK (READ ONLY) --}}
        {{-- ========================================== --}}
        <div id="physicalModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
            <div class="fixed inset-0 modal-overlay transition-opacity" onclick="closePhysicalModal()"></div>
            <div class="flex min-h-screen items-center justify-center px-4 py-10">
                <div class="relative w-full max-w-5xl bg-white rounded-3xl shadow-2xl flex flex-col max-h-[90vh] overflow-hidden border">
                    <div class="bg-gradient-to-r from-pink-500 to-rose-600 px-6 py-5 flex justify-between items-center shrink-0">
                        <div>
                            <h3 class="text-xl font-bold text-white uppercase tracking-wider">Hasil Tes Fisik</h3>
                            <p class="text-sm text-pink-100 mt-0.5">Evaluasi kondisi fisik Anda</p>
                        </div>
                        <button onclick="closePhysicalModal()" class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center text-white hover:rotate-90 transition-all">
                            <i data-feather="x"></i>
                        </button>
                    </div>

                    <div class="p-6 overflow-y-auto custom-scrollbar space-y-6">
                        <div class="flex flex-wrap gap-4 bg-slate-50 p-4 rounded-2xl border border-slate-200 items-end">
                            <div class="flex-1 min-w-[200px]">
                                <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Tahun Evaluasi</label>
                                <input type="number" id="phys_year" value="{{ now()->year }}" class="input-field w-full py-2 bg-white rounded-xl">
                            </div>
                            <button onclick="loadPhysicalData()" class="px-6 py-2 bg-white border border-slate-200 text-slate-700 rounded-xl font-bold hover:bg-slate-100 transition-all flex items-center gap-2">
                                <i data-feather="refresh-cw" class="w-4 h-4"></i> Muat
                            </button>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                            <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm flex flex-col items-center">
                                <h4 class="font-black text-slate-400 text-[10px] uppercase mb-4 tracking-[0.2em]">Spider Chart Analysis</h4>
                                <div class="w-full aspect-square"><canvas id="chartRadar"></canvas></div>
                            </div>
                            <div class="lg:col-span-2 bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
                                <div class="overflow-x-auto">
                                    <table id="phys-table" class="w-full text-sm text-left">
                                        <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-[10px]">
                                            <tr>
                                                <th class="px-6 py-4">Bulan</th>
                                                <th class="px-6 py-4 text-rose-600">VO2 Max</th>
                                                <th class="px-6 py-4">Sprint</th>
                                                <th class="px-6 py-4">P.Up/S.Up</th>
                                                <th class="px-6 py-4">Agility</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-100"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Script JavaScript --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof feather !== 'undefined') feather.replace();
        });

        // ==========================================
        // CONFIG & VARIABLES
        // ==========================================
        // ID Member dari PHP Blade (Member yang sedang login)
        const currentMemberId = {{ $member->id }}; 
        
        let chartValue = null;
        let chartVolume = null;
        let chartRadar = null;

        // ==========================================
        // FUNGSI MODAL RAPORT (READ ONLY)
        // ==========================================
        function openRaportModal() {
            document.getElementById('raportModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            const gayaSelect = document.getElementById('gaya');
            if(gayaSelect.value) loadRaportData();
        }

        function closeRaportModal() {
            document.getElementById('raportModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
            if (chartValue) { chartValue.destroy(); chartValue = null; }
            if (chartVolume) { chartVolume.destroy(); chartVolume = null; }
        }

        function loadRaportData() {
            const gayaSelect = document.getElementById('gaya');
            const gaya = gayaSelect.value;
            const year = document.getElementById('year').value;
            
            // Validasi: Jangan fetch jika gaya belum dipilih
            if (!gaya) {
                const tbody = document.querySelector('#raport-table tbody');
                const detail = document.getElementById('raport-detail');
                if(tbody) tbody.innerHTML = '<tr><td colspan="6" class="px-6 py-8 text-center text-slate-400">Silakan pilih Kategori Gaya terlebih dahulu.</td></tr>';
                if(detail) detail.innerHTML = '';
                return;
            }

            fetch(`/member/performance-data?gaya=${gaya}&year=${year}`)
                .then(response => {
                    if (!response.ok) throw new Error("Gagal mengambil data");
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        updateDetailInfo(data.raports);
                        updateTable(data.raports);
                        updateCharts(data.chartValue, data.chartVolume);
                    } else {
                        alert('Gagal memuat data: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }

        function updateTable(raports) {
            const tbody = document.querySelector('#raport-table tbody');
            tbody.innerHTML = '';
            
            if (raports.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" class="px-6 py-8 text-center text-slate-400">Tidak ada data untuk periode ini.</td></tr>';
                return;
            }
            
            raports.forEach(r => {
                const formattedTime = `${String(Math.floor(r.value / 60)).padStart(2, '0')}:${(r.value % 60).toFixed(2).padStart(5, '0')}`;
                const row = `
                    <tr class="hover:bg-slate-50 transition-colors border-b border-slate-100">
                        <td class="px-5 py-3 font-bold text-slate-800 capitalize">${r.month}</td>
                        <td class="px-5 py-3 text-cyan-600 font-mono font-bold">${formattedTime}</td>
                        <td class="px-5 py-3 text-slate-600">${r.volume}m</td>
                        <td class="px-5 py-3"><span class="px-2 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-600">${r.intensity}%</span></td>
                        <td class="px-5 py-3 font-medium text-slate-700">${r.peaking || '-'}</td>
                        <td class="px-5 py-3 text-xs text-slate-500 italic">${r.note || '-'}</td>
                    </tr>
                `;
                tbody.insertAdjacentHTML('beforeend', row);
            });
        }

        function updateDetailInfo(raports) {
            const detailDiv = document.getElementById('raport-detail');
            if (!detailDiv) return;

            if (raports.length === 0) {
                detailDiv.innerHTML = '';
                return;
            }
            
            let html = '<div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4 mb-6 md:mb-8">';
            raports.slice(0, 4).forEach(r => {
                 const formattedTime = `${String(Math.floor(r.value / 60)).padStart(2, '0')}:${(r.value % 60).toFixed(2).padStart(5, '0')}`;
                 html += `
                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                        <div class="text-xs font-bold text-slate-400 uppercase tracking-wide">${r.month}</div>
                        <div class="text-xl font-bold text-cyan-600 font-mono mt-1">${formattedTime}</div>
                        <div class="text-xs text-slate-500 mt-1 font-medium">${r.volume}m</div>
                    </div>
                 `;
            });
            html += '</div>';
            detailDiv.innerHTML = html;
        }

        function updateCharts(valueData, volumeData) {
            if (typeof Chart === 'undefined') return;

            if (chartValue) chartValue.destroy();
            if (chartVolume) chartVolume.destroy();

            const commonOptions = {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { display: false } }
                }
            };

            // Chart Waktu
            const ctx1 = document.getElementById('chartValue').getContext('2d');
            chartValue = new Chart(ctx1, {
                type: 'line',
                data: valueData,
                options: {
                    ...commonOptions,
                    elements: { line: { borderColor: '#0891b2', borderWidth: 3, tension: 0.4 }, point: { radius: 4 } },
                    scales: { y: { reverse: true } }
                }
            });

            // Chart Volume
            const ctx2 = document.getElementById('chartVolume').getContext('2d');
            if(volumeData.datasets) {
                const colors = ['#0891b2', '#10b981', '#8b5cf6'];
                volumeData.datasets.forEach((ds, i) => {
                    ds.backgroundColor = colors[i % colors.length];
                    ds.borderRadius = 4;
                });
            }

            chartVolume = new Chart(ctx2, {
                type: 'bar',
                data: volumeData,
                options: {
                    ...commonOptions,
                    plugins: { legend: { display: true } }
                }
            });
        }

        // ==========================================
        // FUNGSI MODAL FISIK (READ ONLY)
        // ==========================================
        function openPhysicalModal() {
            document.getElementById('physicalModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            loadPhysicalData();
        }

        function closePhysicalModal() {
            document.getElementById('physicalModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
            if (chartRadar) { chartRadar.destroy(); chartRadar = null; }
        }

        function loadPhysicalData() {
            const year = document.getElementById('phys_year').value;
            fetch(`/api/physical/data?member_id=${currentMemberId}&year=${year}`)
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        updatePhysicalTable(data.history);
                        renderRadarChart(data.radarData);
                    }
                });
        }

        function updatePhysicalTable(history) {
            const tbody = document.querySelector('#phys-table tbody');
            tbody.innerHTML = history.length ? '' : '<tr><td colspan="5" class="px-4 py-10 text-center text-slate-400">Belum ada data fisik.</td></tr>';
            
            history.forEach(h => {
                tbody.insertAdjacentHTML('beforeend', `
                    <tr class="hover:bg-slate-50 transition-colors border-b border-slate-100">
                        <td class="px-4 py-4 font-bold text-slate-800 capitalize">${h.month}</td>
                        <td class="px-4 py-4 text-rose-600 font-black">${h.vo2max || '-'}</td>
                        <td class="px-4 py-4 text-slate-600">${h.sprint_20m || '-'}s</td>
                        <td class="px-4 py-4 text-slate-600">${h.push_up || 0}/${h.sit_up || 0}</td>
                        <td class="px-4 py-4 text-slate-600">${h.shuttle_run || '-'}s</td>
                    </tr>
                `);
            });
        }

        function renderRadarChart(radarData) {
            const canvas = document.getElementById('chartRadar');
            if (!canvas) return;
            if (chartRadar) chartRadar.destroy();
            
            const ctx = canvas.getContext('2d');
            chartRadar = new Chart(ctx, {
                type: 'radar',
                data: {
                    labels: ['Speed', 'Strength', 'Endurance', 'Flexibility', 'Agility'],
                    datasets: [{
                        label: 'Profil Atlet',
                        data: radarData || [0,0,0,0,0],
                        backgroundColor: 'rgba(244, 63, 94, 0.2)',
                        borderColor: 'rgb(244, 63, 94)',
                        pointBackgroundColor: 'rgb(244, 63, 94)',
                        borderWidth: 2
                    }]
                },
                options: { 
                    scales: { r: { min: 0, max: 5, ticks: { display: false } } }, 
                    plugins: { legend: { display: false } } 
                }
            });
        }
    </script>
@endif

</body>
</html>