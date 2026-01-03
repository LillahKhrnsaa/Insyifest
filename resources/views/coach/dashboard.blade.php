<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50 scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Coach Dashboard - Cikampek Swimming Club</title>
    
    {{-- Vite Assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    {{-- Font Nunito --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=nunito:400,500,600,700,800,900&display=swap" rel="stylesheet">
    
    <style>
        /* Custom Styles */
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
        .slide-up { animation: slideUp 0.3s ease-out; }
        .slide-down { animation: slideDown 0.3s ease-out; }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Card Hover Effects */
        .card-hover {
            transition: all 0.3s ease;
        }
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
            top: -1px;
            left: -1px;
            right: -1px;
            bottom: -1px;
            background: linear-gradient(135deg, #0891b2 0%, #2563eb 100%);
            border-radius: 17px;
            z-index: -1;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .gradient-border:hover::before {
            opacity: 1;
        }
        
        /* Table Styles */
        .table-header {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        }
        .table-row:hover {
            background-color: #f8fafc;
        }
        
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
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
        }
        .status-active {
            background-color: #f0fdf4;
            color: #16a34a;
        }
        .status-active::before {
            background-color: #16a34a;
        }
        .status-inactive {
            background-color: #f1f5f9;
            color: #64748b;
        }
        .status-inactive::before {
            background-color: #64748b;
        }
        
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
        
        /* Modal Overlay */
        .modal-overlay {
            background: rgba(15, 23, 42, 0.5);
            backdrop-filter: blur(4px);
        }
        
        /* Layout Improvements */
        .main-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
        
        @media (min-width: 1024px) {
            .main-grid {
                grid-template-columns: 2fr 1fr;
            }
        }
        
        /* Loading Spinner */
        .spinner {
            width: 40px;
            height: 40px;
            border: 3px solid #f1f5f9;
            border-top-color: #0891b2;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body class="h-full text-slate-700 antialiased">
    {{-- Wrapper Utama --}}
    <div x-data="{ 
            showModal: {{ $errors->any() ? 'true' : 'false' }}, 
            showAllMembers: false,
            showAllSchedules: false,
            showAllHistory: false,
            showDetailModal: false,

            filterMonth: '{{ date('Y-m') }}',
            detailMembers: [], 
            detailTitle: '',

            // Variabel Form
            selectedScheduleId: {{ old('schedule_id') ?? 'null' }}, 
            selectedSchedulePlace: '{{ old('place') ?? '' }}',
            searchTerm: '',
            selectedSchedule: '', 
            
            // Data Jadwal
            schedules: {{ $coach->trainingSchedules->map(fn($s) => ['id' => $s->id, 'time' => $s->time, 'place' => $s->place, 'label' => ucfirst($s->day).' ('.$s->time.')']) }},

            // Fungsi Detail Kehadiran
            openDetail(members, date) {
                this.detailMembers = members;
                this.detailTitle = 'Detail Kehadiran - ' + date;
                this.showDetailModal = true;
            }, // <--- Pastikan ada koma di sini

            // Fungsi Otomatis Isi (Auto-fill)
            autoFill() {
                let found = this.schedules.find(s => s.id == this.selectedSchedule);
                if (found) {
                    this.$refs.timeInput.value = found.time;
                    this.$refs.placeInput.value = found.place;
                    this.selectedScheduleId = found.id;
                    this.selectedSchedulePlace = found.place;
                }
            },

            // Fungsi Toggle Modal Absen
            toggleModal(id = null, place = '') {
                this.selectedScheduleId = id;
                this.selectedSchedule = id ? id : '';
                this.selectedSchedulePlace = place;
                this.showModal = true;
                
                this.$nextTick(() => {
                    if(!id) {
                        if(this.$refs.timeInput) this.$refs.timeInput.value = '';
                        if(this.$refs.placeInput) this.$refs.placeInput.value = '';
                    } else {
                        this.autoFill();
                    }
                });
            }
        }" class="min-h-screen">
        {{-- Navbar --}}
        <nav class="bg-white border-b border-slate-100 shadow-sm sticky top-0 z-50">
            <div class="mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    {{-- Logo --}}
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('images/logocsc.png') }}" alt="CSC Logo" class="h-10 w-auto">
                        <div class="hidden md:block">
                            <h1 class="text-lg font-bold text-slate-800 leading-tight">Cikampek Swimming Club</h1>
                            <p class="text-xs text-slate-500">Coach Dashboard</p>
                        </div>
                    </div>

                    {{-- User Menu --}}
                    <div class="flex items-center gap-4">
                        @auth
                        <div class="flex items-center gap-3">
                            <div class="text-right hidden sm:block">
                                <p class="text-sm font-bold text-slate-800">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-cyan-600 font-medium">Coach</p>
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
                            <div>
                                <h1 class="text-2xl md:text-3xl font-bold text-slate-800 mb-2">
                                    Selamat Datang, Coach {{ explode(' ', Auth::user()->name)[0] }}
                                </h1>
                                <p class="text-slate-600">Pantau performa atlet CSC dan kelola jadwal latihan dengan mudah.</p>
                            </div>
                            <div class="mt-4 md:mt-0">
                                <p class="text-sm text-slate-500">Hari ini: {{ now()->isoFormat('dddd, D MMMM YYYY') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Alerts --}}
                @if (session('success') || session('error') || $errors->any())
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
                        
                        @if (session('error') || $errors->any())
                            <div class="rounded-xl bg-gradient-to-r from-red-50 to-pink-50 border border-red-200 p-4 flex items-start gap-3 mt-4">
                                <div class="bg-red-100 p-2 rounded-full text-red-500">
                                    <i data-feather="alert-triangle" class="w-5 h-5"></i>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-sm font-bold text-slate-800">Perhatian</h3>
                                    <p class="text-sm text-slate-600 mt-0.5">{{ session('error') ?? 'Terdapat kesalahan input pada form.' }}</p>
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
                    {{-- Total Atlet --}}
                    <div class="bg-white rounded-xl p-5 border border-slate-200 card-hover">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-slate-500 mb-1">Total Atlet</p>
                                <h3 class="text-2xl font-bold text-slate-800">{{ $totalMembers }}</h3>
                            </div>
                            <div class="w-12 h-12 bg-cyan-50 rounded-lg flex items-center justify-center">
                                <i data-feather="users" class="w-6 h-6 text-cyan-600"></i>
                            </div>
                        </div>
                        <div class="mt-3 pt-3 border-t border-slate-100">
                            <p class="text-xs text-slate-500">{{ $activeMembers }} atlet aktif</p>
                        </div>
                    </div>

                    {{-- Atlet Aktif --}}
                    <div class="bg-white rounded-xl p-5 border border-slate-200 card-hover">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-slate-500 mb-1">Atlet Aktif</p>
                                <h3 class="text-2xl font-bold text-slate-800">{{ $activeMembers }}</h3>
                            </div>
                            <div class="w-12 h-12 bg-green-50 rounded-lg flex items-center justify-center">
                                <i data-feather="activity" class="w-6 h-6 text-green-600"></i>
                            </div>
                        </div>
                        <div class="mt-3 pt-3 border-t border-slate-100">
                            <p class="text-xs text-slate-500">Sedang berlatih</p>
                        </div>
                    </div>

                    {{-- Jadwal --}}
                    <div class="bg-white rounded-xl p-5 border border-slate-200 card-hover">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-slate-500 mb-1">Jadwal</p>
                                <h3 class="text-2xl font-bold text-slate-800">{{ $totalSchedules }}</h3>
                            </div>
                            <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center">
                                <i data-feather="calendar" class="w-6 h-6 text-blue-600"></i>
                            </div>
                        </div>
                        <div class="mt-3 pt-3 border-t border-slate-100">
                            <p class="text-xs text-slate-500">Minggu ini</p>
                        </div>
                    </div>

                    {{-- Total Sesi --}}
                    <div class="bg-white rounded-xl p-5 border border-slate-200 card-hover">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-slate-500 mb-1">Total Sesi</p>
                                <h3 class="text-2xl font-bold text-slate-800">{{ count($attendances) }}</h3>
                            </div>
                            <div class="w-12 h-12 bg-purple-50 rounded-lg flex items-center justify-center">
                                <i data-feather="clipboard" class="w-6 h-6 text-purple-600"></i>
                            </div>
                        </div>
                        <div class="mt-3 pt-3 border-t border-slate-100">
                            <p class="text-xs text-slate-500">Riwayat latihan</p>
                        </div>
                    </div>
                </div>

                {{-- LAYOUT UTAMA --}}
                <div class="fade-in" style="animation-delay: 0.2s;">
                    
                    {{-- 1. Card Atlet Binaan (List View ke Bawah) --}}
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
                            {{-- PERUBAHAN: Hapus Grid, gunakan div biasa dengan divide-y --}}
                            <div class="divide-y divide-slate-100">
                                
                                {{-- Logika Sorting A-Z --}}
                                @php
                                    $sortedMembers = $coach->members->sortBy(function($member) {
                                        return $member->user->name;
                                    })->take(6); // Ambil 6 saja untuk preview
                                @endphp

                                @forelse($sortedMembers as $member)
                                {{-- Row Item --}}
                                <div class="px-5 py-4 hover:bg-slate-50 transition-colors">
                                    <div class="flex items-center justify-between">
                                        
                                        {{-- Sisi Kiri: No, Foto, Nama --}}
                                        <div class="flex items-center gap-4">
                                            {{-- Nomor Urut --}}
                                            <span class="text-slate-400 font-bold text-sm min-w-[20px]">{{ $loop->iteration }}.</span>

                                            {{-- Foto Profil --}}
                                            <div class="w-10 h-10 rounded-full bg-slate-100 overflow-hidden flex-shrink-0 border border-slate-200">
                                                @if ($member->user->photo_url)
                                                    <img src="{{ $member->user->photo_url }}" class="w-full h-full object-cover" alt="Atlet Photo">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center">
                                                        <i data-feather="user" class="w-5 h-5 text-slate-400"></i>
                                                    </div>
                                                @endif
                                            </div>

                                            {{-- Nama & Email --}}
                                            <div>
                                                <h4 class="font-bold text-slate-800 text-sm">{{ $member->user->name }}</h4>
                                                {{-- Hapus truncate agar email terlihat penuh karena space lebar --}}
                                                <p class="text-xs text-slate-500">{{ $member->user->email }}</p> 
                                            </div>
                                        </div>

                                        {{-- Sisi Kanan: Status & Tombol --}}
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

                    {{-- 2. Grid Kanan-Kiri: Jadwal (Kiri) & Riwayat (Kanan) --}}
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                        
                        {{-- KIRI: Jadwal Minggu Ini --}}
                        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden card-hover h-fit">
                            <div class="px-5 py-4 border-b border-slate-100 bg-slate-50">
                                <div class="flex flex-col gap-3">
                                    <div class="flex items-center justify-between">
                                        <h3 class="font-bold text-slate-800 flex items-center gap-2">
                                            <i data-feather="calendar" class="w-5 h-5 text-blue-600"></i>
                                            Jadwal Minggu Ini
                                        </h3>
                                        <span class="text-xs text-slate-500 bg-slate-100 px-2 py-1 rounded">{{ $coach->trainingSchedules->count() }} sesi</span>
                                    </div>

                                    {{-- TOMBOL ABSEN UTAMA (PINDAH KE SINI) --}}
                                    <button @click="toggleModal()" 
                                            class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-sm flex items-center justify-center gap-2 shadow-lg shadow-blue-100 transition-all active:scale-95">
                                        <i data-feather="plus-circle" class="w-4 h-4"></i>
                                        Absen Sekarang
                                    </button>
                                </div>
                            </div>
                                            
                            <div class="overflow-hidden">
                                <div class="divide-y divide-slate-100 max-h-[350px] overflow-y-auto custom-scrollbar">
                                    @forelse($coach->trainingSchedules->take(5) as $schedule)
                                    <div class="px-5 py-4 hover:bg-slate-50 transition-colors">
                                        <div class="flex items-center justify-between group">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center group-hover:bg-blue-100 transition-colors">
                                                    <i data-feather="calendar" class="w-5 h-5 text-blue-600"></i>
                                                </div>
                                                <div>
                                                    <h4 class="font-bold text-slate-800">{{ ucfirst($schedule->day) }}</h4>
                                                    <div class="flex flex-col gap-0.5 mt-0.5">
                                                        <p class="text-xs font-medium text-slate-600 flex items-center gap-1">
                                                            <i data-feather="clock" class="w-3 h-3 text-slate-400"></i>
                                                            {{ $schedule->time ? \Carbon\Carbon::parse($schedule->time)->format('H:i') : '--:--' }} WIB
                                                        </p>
                                                        <p class="text-xs text-slate-500 flex items-center gap-1">
                                                            <i data-feather="map-pin" class="w-3 h-3 text-slate-400"></i>
                                                            {{ $schedule->place ?? 'Kolam Utama' }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            {{-- Opsional: Tambahkan indikator panah kecil agar list tetap terlihat interaktif --}}
                                            <i data-feather="chevron-right" class="w-4 h-4 text-slate-300 group-hover:text-blue-400 transition-colors"></i>
                                        </div>
                                    </div>
                                    @empty
                                    <div class="px-5 py-8 text-center text-slate-400">
                                        <i data-feather="calendar" class="w-8 h-8 mx-auto mb-2 opacity-50"></i>
                                        <p>Tidak ada jadwal latihan</p>
                                    </div>
                                    @endforelse
                                </div>
                            </div>
                            
                            <div class="px-5 py-3 border-t border-slate-100 bg-slate-50">
                                <button @click="showAllSchedules = true" class="w-full text-sm font-bold text-blue-600 hover:text-blue-700 flex items-center justify-center gap-1 transition-colors">
                                    <span>Lihat Semua Jadwal</span>
                                    <i data-feather="chevron-right" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </div>

                        {{-- KANAN: Riwayat Kehadiran Terakhir --}}
                        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden card-hover flex flex-col h-full">
                            {{-- Header Card dengan Filter Bulan --}}
                            <div class="px-5 py-4 border-b border-slate-100 bg-slate-50 shrink-0">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                    <h3 class="font-bold text-slate-800 flex items-center gap-2">
                                        <i data-feather="clock" class="w-5 h-5 text-purple-600"></i>
                                        Riwayat Absensi
                                    </h3>
                                    {{-- Filter Bulan --}}
                                    <div class="flex items-center gap-2">
                                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">Bulan:</label>
                                        <input type="month" x-model="filterMonth" 
                                            class="text-xs border-slate-200 rounded-lg px-2 py-1 focus:ring-purple-500 focus:border-purple-500 bg-white shadow-sm">
                                    </div>
                                </div>
                            </div>
                                                            
                            <div class="overflow-x-auto flex-1 w-full custom-scrollbar">
                                <table class="w-full min-w-[600px]">
                                    <thead>
                                        <tr class="text-left text-[10px] text-slate-400 font-bold uppercase tracking-widest border-b border-slate-50">
                                            <th class="px-4 py-4">Waktu & Lokasi</th>
                                            <th class="px-4 py-4 text-center">Kehadiran</th>
                                            <th class="px-4 py-4">Catatan & Foto</th>
                                            <th class="px-4 py-4 text-right">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-50">
                                        @forelse($attendances as $attendance)
                                        <tr x-show="filterMonth === '' || '{{ \Carbon\Carbon::parse($attendance->date)->format('Y-m') }}' === filterMonth"
                                            x-transition.opacity
                                            class="hover:bg-slate-50/50 transition-colors">
                                            
                                            {{-- 1. Waktu --}}
                                            <td class="px-4 py-4">
                                                <div class="font-bold text-slate-800 text-sm">
                                                    {{ \Carbon\Carbon::parse($attendance->date)->isoFormat('D MMM YYYY') }}
                                                </div>
                                                <div class="flex flex-col gap-1 mt-1">
                                                    <span class="text-[11px] text-blue-600 font-medium flex items-center gap-1">
                                                        <i data-feather="clock" class="w-3 h-3 text-slate-400"></i>
                                                        {{ $attendance->time ? \Carbon\Carbon::parse($attendance->time)->format('H:i') : '--:--' }} WIB
                                                    </span>
                                                    <span class="text-[11px] text-slate-500 flex items-center gap-1">
                                                        <i data-feather="map-pin" class="w-3 h-3 text-slate-400"></i>
                                                        {{ Str::limit($attendance->place ?? '-', 20) }}
                                                    </span>
                                                </div>
                                            </td>

                                            {{-- 2. Kehadiran (Klik untuk Detail) --}}
                                            <td class="px-4 py-4 text-center">
                                                @php
                                                    $binaanIds = $coach->members->pluck('id')->toArray();
                                                    $countBinaan = $attendance->members->whereIn('id', $binaanIds)->count();
                                                    $countLain = $attendance->members->whereNotIn('id', $binaanIds)->count();
                                                    
                                                    $detailData = $attendance->members->map(function($m) use ($binaanIds) {
                                                        return [
                                                            'name' => addslashes($m->user->name),
                                                            'is_binaan' => in_array($m->id, $binaanIds),
                                                            'photo' => $m->user->photo_url ?? null,
                                                            'category' => $m->category ?? 'Umum'
                                                        ];
                                                    });
                                                @endphp
                                                
                                                <button type="button" 
                                                        @click="openDetail({{ json_encode($detailData) }}, '{{ \Carbon\Carbon::parse($attendance->date)->isoFormat('D MMM YYYY') }}')"
                                                        class="flex items-center justify-center gap-2 hover:scale-105 transition-transform cursor-pointer group mx-auto">
                                                    <div class="text-center px-2 py-1 bg-blue-50 rounded-lg border border-blue-100 group-hover:bg-blue-100 transition-colors">
                                                        <p class="text-[9px] text-blue-500 font-bold uppercase tracking-tighter">Binaan</p>
                                                        <p class="text-sm font-black text-blue-700">{{ $countBinaan }}</p>
                                                    </div>
                                                    <div class="text-center px-2 py-1 bg-slate-50 rounded-lg border border-slate-100 group-hover:bg-slate-200 transition-colors">
                                                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-tighter">Lainnya</p>
                                                        <p class="text-sm font-black text-slate-600">{{ $countLain }}</p>
                                                    </div>
                                                </button>
                                            </td>

                                            {{-- 3. Catatan & Foto --}}
                                            <td class="px-4 py-4">
                                                <div class="max-w-[150px]">
                                                    <p class="text-xs text-slate-600 italic line-clamp-2 mb-2" title="{{ $attendance->notes }}">
                                                        {{ $attendance->notes ? '"'.$attendance->notes.'"' : '-' }}
                                                    </p>
                                                    
                                                    @if($attendance->photo_path)
                                                        <a href="{{ asset('storage/' . $attendance->photo_path) }}" target="_blank" 
                                                        class="inline-flex items-center gap-1.5 text-[10px] font-bold text-cyan-600 hover:text-cyan-700 bg-cyan-50 px-2 py-1 rounded-md transition-colors">
                                                            <i data-feather="image" class="w-3 h-3"></i>
                                                            Lihat Foto
                                                        </a>
                                                    @else
                                                        <span class="text-[10px] text-slate-300 italic">Tanpa Foto</span>
                                                    @endif
                                                </div>
                                            </td>

                                            {{-- 4. Status --}}
                                            <td class="px-4 py-4 text-right">
                                                <span class="inline-flex items-center justify-center w-7 h-7 bg-green-50 text-green-600 rounded-full border border-green-100 shadow-sm">
                                                    <i data-feather="check" class="w-3.5 h-3.5"></i>
                                                </span>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="px-5 py-20 text-center text-slate-400 font-medium">
                                                Belum ada riwayat absensi.
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
        </main>

        {{-- ========================================== --}}
        {{-- MODAL ABSENSI (FIX LAYOUT SCROLLABLE) --}}
        {{-- ========================================== --}}
        <div x-show="showModal" x-cloak class="relative z-[9999]">
            {{-- Overlay --}}
            <div x-show="showModal" 
                x-transition.opacity 
                class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"
                @click="showModal = false"></div>

            {{-- Modal Container --}}
            <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
                <div x-show="showModal" 
                    x-transition.scale.origin.center
                    {{-- FIX: Tambahkan flex flex-col dan h-auto max-h-[90vh] --}}
                    class="relative w-full max-w-md bg-white rounded-[2.5rem] shadow-2xl flex flex-col max-h-[90vh] overflow-hidden">
                    
                    {{-- Header (Tetap di atas / Sticky) --}}
                    <div class="bg-gradient-to-r from-cyan-600 to-blue-700 px-6 py-5 flex items-center justify-between shrink-0 shadow-sm">
                        <div>
                            <h3 class="text-xl font-bold text-white leading-tight">Form Absensi</h3>
                            <p class="text-xs text-cyan-100 mt-0.5">Catat kehadiran latihan</p>
                        </div>
                        <button @click="showModal = false" class="w-9 h-9 rounded-full bg-white/10 flex items-center justify-center text-white hover:bg-white/20 transition-colors">
                            <i data-feather="x" class="w-5 h-5"></i>
                        </button>
                    </div>

                    {{-- Body (Area yang bisa di-scroll) --}}
                    {{-- FIX: Tambahkan overflow-y-auto dan custom-scrollbar --}}
                    <div class="p-6 overflow-y-auto custom-scrollbar flex-1 bg-white">
                        <form method="POST" action="{{ route('attendance.store') }}" enctype="multipart/form-data" id="form-attendance">
                            @csrf
                            
                            <div class="space-y-6">
                                {{-- 1. Pilih Jadwal --}}
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Jadwal (Opsional)</label>
                                    <select name="schedule_id" x-model="selectedSchedule" @change="autoFill()" 
                                            class="input-field w-full border-slate-200 focus:border-cyan-500 rounded-2xl">
                                        <option value="">-- Luar Jadwal / Tambahan --</option>
                                        <template x-for="s in schedules" :key="s.id">
                                            <option :value="s.id" x-text="s.label"></option>
                                        </template>
                                    </select>
                                </div>

                                {{-- 2. Tanggal & Jam --}}
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Tanggal</label>
                                        <input type="date" name="date" value="{{ date('Y-m-d') }}" required 
                                            class="input-field w-full rounded-2xl border-slate-200">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Jam</label>
                                        <input type="time" name="time" x-ref="timeInput" required 
                                            class="input-field w-full rounded-2xl border-slate-200">
                                    </div>
                                </div>

                                {{-- 3. Lokasi --}}
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Lokasi</label>
                                    <input type="text" name="place" x-ref="placeInput" required 
                                        placeholder="Lokasi kolam..." 
                                        class="input-field w-full rounded-2xl border-slate-200">
                                </div>

                                {{-- 4. Daftar Atlet (Area Scroll Kedua di dalam Form) --}}
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-widest mb-3">Daftar Atlet</label>
                                    
                                    {{-- Search --}}
                                    <div class="relative mb-3">
                                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400">
                                            <i data-feather="search" class="w-4 h-4"></i>
                                        </span>
                                        <input type="text" x-model="searchTerm" placeholder="Cari nama atlet..." 
                                            class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-sm focus:ring-2 focus:ring-cyan-500 focus:bg-white transition-all">
                                    </div>

                                    {{-- Container List Murid dengan Scroll Internal --}}
                                    <div class="bg-slate-50 rounded-[1.5rem] p-2 max-h-60 overflow-y-auto border border-slate-100 custom-scrollbar">
                                        @php
                                            $mergedMembers = $activeRegularMembers->concat($allOtherMembers);
                                        @endphp

                                        @forelse($mergedMembers as $member)
                                            @php $safeName = addslashes(strtolower($member->user->name)); @endphp

                                            <label x-show="'{{ $safeName }}'.includes(searchTerm.toLowerCase())"
                                                class="flex items-center p-3 rounded-2xl hover:bg-white cursor-pointer transition-all border border-transparent hover:border-slate-100 mb-1 last:mb-0 group shadow-sm hover:shadow-md">
                                                <input type="checkbox" name="members[]" value="{{ $member->id }}" 
                                                    class="w-5 h-5 rounded-lg border-slate-300 text-cyan-600 focus:ring-cyan-500">
                                                
                                                <div class="ml-3 flex items-center gap-3 truncate">
                                                    <div class="w-8 h-8 rounded-full bg-slate-200 overflow-hidden flex-shrink-0">
                                                        @if ($member->user->photo_url)
                                                            <img src="{{ $member->user->photo_url }}" class="w-full h-full object-cover">
                                                        @else
                                                            <div class="w-full h-full flex items-center justify-center text-slate-400 bg-slate-200">
                                                                <i data-feather="user" class="w-4 h-4"></i>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="truncate">
                                                        <span class="text-sm font-bold text-slate-700 block truncate group-hover:text-cyan-600 transition-colors">{{ $member->user->name }}</span>
                                                        <span class="text-[10px] {{ $activeRegularMembers->contains($member->id) ? 'text-blue-600 bg-blue-50' : 'text-slate-400 bg-slate-100' }} px-2 py-0.5 rounded-full font-bold uppercase tracking-tighter">
                                                            {{ $activeRegularMembers->contains($member->id) ? 'Binaan Saya' : 'Lainnya' }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </label>
                                        @empty
                                            <p class="text-xs text-slate-400 text-center py-6">Tidak ada atlet aktif.</p>
                                        @endforelse
                                    </div>
                                </div>

                                {{-- 5. Catatan & Foto --}}
                                <div class="grid grid-cols-1 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Catatan</label>
                                        <textarea name="notes" rows="2" class="input-field w-full rounded-2xl text-sm" placeholder="Opsional..."></textarea>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Dokumentasi</label>
                                        <input type="file" name="photo" id="photo-upload" class="hidden" accept="image/*">
                                        <label for="photo-upload" class="flex items-center justify-center gap-3 border-2 border-dashed border-slate-200 rounded-2xl p-4 text-center hover:bg-slate-50 transition-colors cursor-pointer group">
                                            <i data-feather="camera" class="w-5 h-5 text-slate-400 group-hover:text-cyan-600"></i>
                                            <span class="text-xs text-slate-500 font-medium">Upload Foto</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    {{-- Footer (Sticky di bawah) --}}
                    <div class="p-6 border-t border-slate-50 bg-white shrink-0">
                        <div class="flex gap-3">
                            <button type="button" @click="showModal = false" 
                                    class="flex-1 px-4 py-3.5 bg-slate-100 text-slate-600 font-bold rounded-2xl hover:bg-slate-200 transition-colors text-sm">
                                Batal
                            </button>
                            <button type="submit" form="form-attendance"
                                    class="flex-[2] px-4 py-3.5 bg-gradient-to-r from-cyan-600 to-blue-600 text-white font-bold rounded-2xl flex items-center justify-center gap-2 shadow-lg shadow-cyan-200 hover:brightness-110 transition-all text-sm">
                                <i data-feather="save" class="w-4 h-4"></i>
                                Simpan Absensi
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ========================================== --}}
        {{-- MODAL DETAIL MEMBER HADIR --}}
        {{-- ========================================== --}}
        <div x-show="showDetailModal" x-cloak class="relative z-[99999]">
            <div x-show="showDetailModal" x-transition.opacity class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showDetailModal = false"></div>

            <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
                <div x-show="showDetailModal" x-transition.scale 
                    class="relative w-full max-w-sm bg-white rounded-[2rem] shadow-2xl flex flex-col max-h-[80vh] overflow-hidden">
                    
                    <div class="p-5 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                        <h3 class="font-bold text-slate-800 text-sm" x-text="detailTitle"></h3>
                        <button @click="showDetailModal = false" class="text-slate-400 hover:text-slate-600">
                            <i data-feather="x" class="w-5 h-5"></i>
                        </button>
                    </div>

                    <div class="p-4 overflow-y-auto custom-scrollbar flex-1">
                        <div class="space-y-3">
                            <template x-for="(m, index) in detailMembers" :key="index">
                                <div class="flex items-center justify-between p-3 rounded-2xl border border-slate-50 bg-slate-50/50">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-white border border-slate-200 overflow-hidden shrink-0">
                                            <template x-if="m.photo">
                                                <img :src="m.photo" class="w-full h-full object-cover">
                                            </template>
                                            <template x-if="!m.photo">
                                                <div class="w-full h-full flex items-center justify-center text-slate-300">
                                                    <i data-feather="user" class="w-4 h-4"></i>
                                                </div>
                                            </template>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-slate-700" x-text="m.name"></p>
                                            <p class="text-[10px] text-slate-400" x-text="m.category"></p>
                                        </div>
                                    </div>
                                    <span :class="m.is_binaan ? 'bg-blue-100 text-blue-600' : 'bg-slate-200 text-slate-600'" 
                                        class="text-[9px] font-bold px-2 py-1 rounded-full uppercase"
                                        x-text="m.is_binaan ? 'Binaan' : 'Lainnya'">
                                    </span>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div class="p-4 bg-slate-50 text-center">
                        <button @click="showDetailModal = false" class="text-xs font-bold text-blue-600">Tutup</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- ========================================== --}}
        {{-- MODAL 1: SEMUA ATLET --}}
        {{-- ========================================== --}}
        <div x-show="showAllMembers" x-cloak class="relative z-50">
            <div x-show="showAllMembers" x-transition.opacity class="fixed inset-0 modal-overlay"></div>
            <div class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6">
                
                {{-- PERUBAHAN: 'rounded-3xl' --}}
                <div x-show="showAllMembers" @click.away="showAllMembers = false" x-transition.scale 
                     class="relative w-full max-w-4xl bg-white rounded-3xl shadow-2xl slide-up max-h-[85vh] flex flex-col overflow-hidden">
                    
                    {{-- Header --}}
                    <div class="bg-gradient-to-r from-cyan-500 to-blue-600 px-6 py-5 flex justify-between items-center shrink-0">
                        <div>
                            <h3 class="text-xl font-bold text-white">Daftar Seluruh Atlet</h3>
                            <p class="text-sm text-cyan-100 mt-0.5">Total: {{ $coach->members->count() }} Atlet</p>
                        </div>
                        <button @click="showAllMembers = false" class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center text-white hover:bg-white/20 transition-colors">
                            <i data-feather="x" class="w-5 h-5"></i>
                        </button>
                    </div>

                    {{-- Content --}}
                    <div class="overflow-y-auto">
                        <table class="w-full">
                            <thead class="bg-slate-50 text-xs text-slate-500 font-bold uppercase sticky top-0 z-10 shadow-sm">
                                <tr>
                                    <th class="px-6 py-4 text-center w-16">No</th>
                                    <th class="px-6 py-4 text-left">Atlet</th>
                                    <th class="px-6 py-4 text-left">Email</th>
                                    <th class="px-6 py-4 text-left">Status</th>
                                    <th class="px-6 py-4 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @php
                                    $allSortedMembers = $coach->members->sortBy(function($member) {
                                        return $member->user->name;
                                    });
                                @endphp
                                @foreach($allSortedMembers as $member) 
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4 text-center font-bold text-slate-500">{{ $loop->iteration }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full bg-slate-100 overflow-hidden border border-slate-200">
                                                @if ($member->user->photo_url)
                                                    <img src="{{ $member->user->photo_url }}" class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center text-slate-400">
                                                        <i data-feather="user" class="w-4 h-4"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <div class="font-bold text-slate-800">{{ $member->user->name }}</div>
                                                <div class="text-xs text-slate-500">ID: {{ $member->id }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-slate-600">{{ $member->user->email }}</td>
                                    <td class="px-6 py-4">
                                        <span class="status-badge {{ $member->status == 'AKTIF' ? 'status-active' : 'status-inactive' }}">
                                            {{ $member->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <button onclick="openRaportModal({{ $member->id }}, '{{ $member->user->name }}'); showAllMembers = false;" 
                                                class="px-4 py-2 bg-cyan-50 text-cyan-600 hover:bg-cyan-100 rounded-lg font-bold text-xs transition-colors flex items-center gap-2 mx-auto">
                                            <i data-feather="file-text" class="w-3 h-3"></i>
                                            Lihat Raport
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Footer --}}
                    <div class="px-6 py-4 border-t border-slate-100 bg-slate-50 text-center">
                        <p class="text-sm text-slate-500">Klik nama atlet untuk melihat detail lengkap</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ========================================== --}}
        {{-- MODAL 2: SEMUA JADWAL --}}
        {{-- ========================================== --}}
        <div x-show="showAllSchedules" x-cloak class="relative z-50">
            <div x-show="showAllSchedules" x-transition.opacity class="fixed inset-0 modal-overlay"></div>
            <div class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6">
                
                {{-- PERUBAHAN: 'rounded-3xl' --}}
                <div x-show="showAllSchedules" @click.away="showAllSchedules = false" x-transition.scale 
                     class="relative w-full max-w-4xl bg-white rounded-3xl shadow-2xl slide-up max-h-[85vh] flex flex-col overflow-hidden">
                    
                    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-5 flex justify-between items-center shrink-0">
                        <div>
                            <h3 class="text-xl font-bold text-white">Jadwal Latihan Lengkap</h3>
                            <p class="text-sm text-blue-100 mt-0.5">{{ $coach->trainingSchedules->count() }} sesi latihan</p>
                        </div>
                        <button @click="showAllSchedules = false" class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center text-white hover:bg-white/20 transition-colors">
                            <i data-feather="x" class="w-5 h-5"></i>
                        </button>
                    </div>

                    <div class="p-6 overflow-y-auto">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($coach->trainingSchedules as $schedule)
                            <div class="bg-white p-5 rounded-xl border border-slate-200 hover:shadow-lg transition-all card-hover">
                                <div class="flex items-start justify-between mb-4">
                                    <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center">
                                        <i data-feather="calendar" class="w-6 h-6 text-blue-600"></i>
                                    </div>
                                    <span class="bg-blue-100 text-blue-700 text-xs font-bold px-3 py-1 rounded-full uppercase">
                                        {{ ucfirst($schedule->day) }}
                                    </span>
                                </div>
                                
                                <h4 class="font-bold text-slate-800 mb-2 text-lg">{{ $schedule->place ?? 'Kolam Utama' }}</h4>
                                
                                <div class="space-y-3">
                                    <div class="flex items-center text-sm text-slate-600">
                                        <i data-feather="clock" class="w-4 h-4 mr-2 text-slate-400"></i>
                                        <span>{{ $schedule->time ? \Carbon\Carbon::parse($schedule->time)->format('H:i') : '--:--' }} WIB</span>
                                    </div>
                                    
                                    <div class="pt-4 border-t border-slate-100">
                                        <button @click="toggleModal({{ $schedule->id }}, '{{ $schedule->place ?? '' }}'); showAllSchedules = false;"
                                                class="w-full py-3 btn-primary flex items-center justify-center gap-2 text-sm font-bold">
                                            <i data-feather="check-square" class="w-4 h-4"></i>
                                            Absen Jadwal Ini
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- ========================================== --}}
        {{-- MODAL 3: SEMUA RIWAYAT (DETAIL LENGKAP) --}}
        {{-- ========================================== --}}
        <div x-show="showAllHistory" x-cloak class="relative z-50">
            <div x-show="showAllHistory" x-transition.opacity class="fixed inset-0 modal-overlay"></div>
            <div class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6">
                
                {{-- PERUBAHAN: 'rounded-3xl' --}}
                <div x-show="showAllHistory" @click.away="showAllHistory = false" x-transition.scale 
                     class="relative w-full max-w-4xl bg-white rounded-3xl shadow-2xl slide-up max-h-[85vh] flex flex-col overflow-hidden">
                    
                    <div class="bg-gradient-to-r from-cyan-500 to-blue-600 px-6 py-5 flex justify-between items-center shrink-0">
                        <div>
                            <h3 class="text-xl font-bold text-white">Riwayat Kehadiran Lengkap</h3>
                            <p class="text-sm text-cyan-100 mt-0.5">Arsip seluruh sesi latihan</p>
                        </div>
                        <button @click="showAllHistory = false" class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center text-white hover:bg-white/20 transition-colors">
                            <i data-feather="x" class="w-5 h-5"></i>
                        </button>
                    </div>

                    <div class="overflow-y-auto">
                        <table class="w-full">
                            <thead class="bg-slate-50 text-xs uppercase text-slate-500 font-bold sticky top-0 z-10 shadow-sm">
                                <tr>
                                    <th class="px-6 py-4 text-left">Tanggal & Waktu</th>
                                    <th class="px-6 py-4 text-left">Lokasi</th>
                                    <th class="px-6 py-4 text-left">Total Hadir</th>
                                    <th class="px-6 py-4 text-left">Dokumentasi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($attendances as $attendance)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-slate-800">
                                            {{ \Carbon\Carbon::parse($attendance->date)->isoFormat('dddd, D MMMM Y') }}
                                        </div>
                                        <div class="text-sm text-slate-500 mt-1 flex items-center gap-1">
                                            <i data-feather="clock" class="w-3 h-3"></i>
                                            {{ $attendance->schedule ? \Carbon\Carbon::parse($attendance->schedule->time)->format('H:i') : '-' }} WIB
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2 text-slate-600">
                                            <i data-feather="map-pin" class="w-4 h-4 text-slate-400"></i>
                                            {{ $attendance->place ?? '-' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-cyan-100 text-cyan-700 font-bold">
                                                {{ $attendance->members_count }}
                                            </span>
                                            <span class="text-sm text-slate-600">atlet</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($attendance->photo_path)
                                            <a href="{{ asset('storage/'.$attendance->photo_path) }}" target="_blank" 
                                               class="inline-flex items-center gap-2 px-4 py-2 bg-cyan-50 text-cyan-600 hover:bg-cyan-100 rounded-lg font-bold text-xs transition-colors">
                                                <i data-feather="image" class="w-4 h-4"></i>
                                                Lihat Foto
                                            </a>
                                        @else
                                            <span class="text-slate-400 text-sm italic">Tidak ada foto</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Footer --}}
                    <div class="px-6 py-4 border-t border-slate-100 bg-slate-50">
                        <div class="flex items-center justify-between">
                            <p class="text-sm text-slate-500">Total {{ count($attendances) }} sesi latihan</p>
                            <div class="flex items-center gap-2 text-sm text-slate-500">
                                <i data-feather="info" class="w-4 h-4"></i>
                                <span>Klik "Lihat Foto" untuk melihat dokumentasi</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Modal Raport --}}
    <div id="raportModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
        {{-- Backdrop --}}
        <div class="fixed inset-0 modal-overlay transition-opacity" onclick="closeRaportModal()"></div>
        
        <div class="flex min-h-screen items-center justify-center px-4 py-10 text-center sm:px-6">
            
            {{-- Modal Content --}}
            {{-- PERUBAHAN: 'rounded-3xl' --}}
            <div class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-xl transition-all sm:w-full sm:max-w-6xl flex flex-col max-h-[85vh]">
                
                {{-- Modal Header --}}
                <div class="bg-gradient-to-r from-cyan-500 to-blue-600 px-5 py-4 flex justify-between items-center shrink-0">
                    <div>
                        <h3 class="text-lg font-bold text-white">Raport Performa Atlet</h3>
                        <p class="text-xs text-cyan-100 mt-0.5">Atlet: <span id="memberName" class="font-bold"></span></p>
                    </div>
                    <button onclick="closeRaportModal()" class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center text-white hover:bg-white/20 transition-colors focus:outline-none">
                        <i data-feather="x" class="w-5 h-5"></i>
                    </button>
                </div>

                {{-- Modal Body --}}
                <div class="p-5 space-y-6 overflow-y-auto custom-scrollbar">
                    
                    {{-- Filter Section --}}
                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Kategori Gaya</label>
                            <select id="gaya" class="input-field w-full py-2 text-sm bg-white">
                                {{-- Tambahkan baris ini sebagai default --}}
                                <option value="" selected disabled>-- Pilih Gaya Renang --</option>

                                @forelse($existingStyles as $style)
                                    <option value="{{ $style }}">
                                        {{ ucwords(str_replace('_', ' ', $style)) }}
                                    </option>
                                @empty
                                    <option value="" disabled>Belum ada data gaya</option>
                                @endforelse
                            </select>
                            </div>
                            
                            {{-- Input Tahun --}}
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Tahun</label>
                                <input type="number" id="year" value="{{ now()->year }}" class="input-field w-full py-2 text-sm bg-white">
                            </div>
                            
                            {{-- Tombol Muat Data --}}
                            <div class="flex items-end">
                                <button onclick="loadRaportData()" class="w-full btn-primary py-2 flex items-center justify-center gap-2 text-sm shadow-sm hover:shadow-md">
                                    <i data-feather="refresh-cw" class="w-4 h-4"></i>
                                    Muat Data
                                </button>
                            </div>
                        </div>

                        {{-- TOMBOL TAMBAH DATA BARU --}}
                        <div class="border-t border-slate-500 pt-4 flex justify-end">
                            <button onclick="openCreateForm()" class="px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-black rounded-lg text-sm font-bold transition-colors flex items-center gap-2 shadow-sm">
                                <i data-feather="plus-circle" class="w-4 h-4"></i>
                                Tambah Data Baru
                            </button>
                        </div>
                    </div>

                    {{-- Charts Grid --}}
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        {{-- Chart Waktu --}}
                        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
                            <h4 class="font-bold text-slate-800 mb-4 text-xs uppercase tracking-wide flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-cyan-500"></span> Grafik Waktu (Detik)
                            </h4>
                            <div class="h-56 relative w-full">
                                <canvas id="chartValue"></canvas>
                            </div>
                        </div>
                        
                        {{-- Chart Volume --}}
                        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
                            <h4 class="font-bold text-slate-800 mb-4 text-xs uppercase tracking-wide flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Volume & Intensitas
                            </h4>
                            <div class="h-56 relative w-full">
                                <canvas id="chartVolume"></canvas>
                            </div>
                        </div>
                    </div>

                    {{-- Tabel Data --}}
                    <div class="flex flex-col pb-4">
                        <div class="flex justify-between items-center mb-3">
                            <h4 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                                <i data-feather="list" class="w-4 h-4 text-slate-400"></i> Detail Data Bulanan
                            </h4>
                        </div>
                        
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
                                            <th class="px-5 py-3 text-center bg-slate-50">Aksi</th>
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
    </div>

    {{-- MODAL DISPLAY FISIK --}}
    <div id="physicalModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
        <div class="fixed inset-0 modal-overlay transition-opacity" onclick="closePhysicalModal()"></div>
        <div class="flex min-h-screen items-center justify-center px-4 py-10">
            <div class="relative w-full max-w-5xl bg-white dark:bg-slate-900 rounded-3xl shadow-2xl flex flex-col max-h-[90vh] overflow-hidden border dark:border-slate-700">
                {{-- Header --}}
                <div class="bg-gradient-to-r from-pink-500 to-rose-600 px-6 py-5 flex justify-between items-center shrink-0">
                    <div>
                        <h3 class="text-xl font-bold text-white uppercase tracking-wider">Analisis Kondisi Fisik</h3>
                        <p class="text-sm text-pink-100 mt-0.5">Atlet: <span id="physMemberName" class="font-black"></span></p>
                    </div>
                    <button onclick="closePhysicalModal()" class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center text-white hover:rotate-90 transition-all">
                        <i data-feather="x"></i>
                    </button>
                </div>

                <div class="p-6 overflow-y-auto custom-scrollbar space-y-6">
                    {{-- Filter & Tombol Tambah --}}
                    <div class="flex flex-wrap gap-4 bg-slate-50 dark:bg-slate-800/50 p-4 rounded-2xl border border-slate-200 dark:border-slate-700 items-end">
                        <div class="flex-1 min-w-[200px]">
                            <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Tahun Evaluasi</label>
                            <input type="number" id="phys_year" value="{{ now()->year }}" class="input-field w-full py-2 bg-white dark:bg-slate-900 border-slate-200 dark:border-slate-700 rounded-xl">
                        </div>
                        <button onclick="loadPhysicalData()" class="px-6 py-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 rounded-xl font-bold hover:bg-slate-100 transition-all flex items-center gap-2">
                            <i data-feather="refresh-cw" class="w-4 h-4"></i> Muat
                        </button>
                        <button onclick="openPhysForm()" class="px-6 py-2 bg-rose-600 text-white rounded-xl font-bold hover:bg-rose-700 shadow-lg shadow-rose-200 dark:shadow-none transition-all flex items-center gap-2">
                            <i data-feather="plus" class="w-4 h-4"></i> Tambah Data
                        </button>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        {{-- Radar Chart --}}
                        <div class="bg-white dark:bg-slate-800 p-5 rounded-3xl border border-slate-200 dark:border-slate-700 shadow-sm flex flex-col items-center">
                            <h4 class="font-black text-slate-400 text-[10px] uppercase mb-4 tracking-[0.2em]">Spider Chart Analysis</h4>
                            <div class="w-full aspect-square"><canvas id="chartRadar"></canvas></div>
                        </div>

                        {{-- Riwayat Tabel --}}
                        <div class="lg:col-span-2 bg-white dark:bg-slate-800 rounded-3xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
                            <div class="overflow-x-auto">
                                <table id="phys-table" class="w-full text-sm text-left">
                                    <thead class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 font-bold uppercase text-[10px]">
                                        <tr>
                                            <th class="px-6 py-4">Bulan</th>
                                            <th class="px-6 py-4 text-rose-600">VO2 Max</th>
                                            <th class="px-6 py-4">Sprint</th>
                                            <th class="px-6 py-4">P.Up/S.Up</th>
                                            <th class="px-6 py-4">Agility</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                                        {{-- Data diisi via JS --}}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL FORM INPUT FISIK (Level 2) --}}
    <div id="physFormModal" class="hidden fixed inset-0 overflow-y-auto" style="z-index: 100;">
        <div class="fixed inset-0 modal-overlay transition-opacity" onclick="closePhysFormModal()"></div>
        <div class="flex min-h-screen items-center justify-center p-4">
            <div class="relative w-full max-w-md bg-white dark:bg-slate-900 rounded-[2rem] shadow-2xl flex flex-col overflow-hidden border dark:border-slate-700">
                {{-- Header --}}
                <div class="bg-rose-600 px-6 py-5 flex justify-between items-center text-white">
                    <div>
                        <h3 class="text-xl font-bold uppercase tracking-wide">Input Data Fisik</h3>
                        <p class="text-[10px] text-rose-100 uppercase opacity-70">Parameter Kebugaran Atlet</p>
                    </div>
                    <button onclick="closePhysFormModal()" class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center hover:bg-white/30"><i data-feather="x" class="w-4 h-4"></i></button>
                </div>

                {{-- Form Body --}}
                <form id="physForm" class="p-6 space-y-4 max-h-[75vh] overflow-y-auto custom-scrollbar">
                    @csrf
                    <input type="hidden" name="member_id" id="phys_form_member_id">
                    <input type="hidden" name="year" id="phys_form_year">
                    
                    {{-- Pemilihan Bulan --}}
                    <div>
                        <label class="block text-[10px] font-black text-slate-500 uppercase mb-1">Pilih Bulan Tes</label>
                        <select name="month" id="phys_month" class="input-field w-full py-2.5 bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-xl" required>
                            @foreach(['januari','februari','maret','april','mei','juni','juli','agustus','september','oktober','november','desember'] as $m)
                                <option value="{{ $m }}">{{ ucfirst($m) }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Bleep Test Calculator Section --}}
                    <div class="bg-slate-50 dark:bg-slate-800/50 p-4 rounded-2xl border border-slate-200 dark:border-slate-700 space-y-3">
                        <div class="text-[10px] font-black text-rose-600 uppercase mb-1 flex items-center gap-2">
                            <i data-feather="zap" class="w-3 h-3"></i> Bleep Test (VO2 Max)
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="text-[10px] text-slate-400 font-bold uppercase">Level</label>
                                <input type="number" name="bleep_level" id="bleep_level" oninput="calculateBleep()" placeholder="8" class="input-field w-full py-2 bg-white dark:bg-slate-900 border-slate-200 dark:border-slate-700 rounded-xl text-sm">
                            </div>
                            <div>
                                <label class="text-[10px] text-slate-400 font-bold uppercase">Shuttle</label>
                                <input type="number" name="bleep_shuttle" id="bleep_shuttle" oninput="calculateBleep()" placeholder="5" class="input-field w-full py-2 bg-white dark:bg-slate-900 border-slate-200 dark:border-slate-700 rounded-xl text-sm">
                            </div>
                        </div>
                        <div>
                            <label class="text-[10px] text-slate-400 font-bold uppercase">Hasil Estimasi VO2 Max</label>
                            <input type="text" id="vo2max" readonly class="input-field w-full py-2 bg-rose-50 dark:bg-rose-900/20 border-none font-black text-rose-600 text-center rounded-xl">
                        </div>
                    </div>

                    {{-- Komponen Fisik Lain --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div class="col-span-1">
                            <label class="text-[10px] text-slate-500 font-bold uppercase">Sprint 20m (s)</label>
                            <input type="number" step="0.01" name="sprint_20m" class="input-field w-full py-2 bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-xl">
                        </div>
                        <div class="col-span-1">
                            <label class="text-[10px] text-slate-500 font-bold uppercase">Agility (s)</label>
                            <input type="number" step="0.01" name="shuttle_run" class="input-field w-full py-2 bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-xl">
                        </div>
                        <div class="col-span-1">
                            <label class="text-[10px] text-slate-500 font-bold uppercase">Push Up (x)</label>
                            <input type="number" name="push_up" class="input-field w-full py-2 bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-xl">
                        </div>
                        <div class="col-span-1">
                            <label class="text-[10px] text-slate-500 font-bold uppercase">Sit Up (x)</label>
                            <input type="number" name="sit_up" class="input-field w-full py-2 bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-xl">
                        </div>
                    </div>

                    <button type="submit" class="w-full py-3.5 bg-rose-600 text-white rounded-[1.25rem] font-black text-sm uppercase tracking-widest shadow-xl shadow-rose-200 dark:shadow-none hover:bg-rose-700 hover:-translate-y-0.5 transition-all flex items-center justify-center gap-3 mt-4">
                        <i data-feather="save" class="w-4 h-4"></i> Simpan Hasil Tes
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- ========================================== --}}
    {{-- MODAL FORM TAMBAH/EDIT RAPORT (Level 2 - z-index: 100) --}}
    {{-- ========================================== --}}
    {{-- PERBAIKAN: z-index ditingkatkan jadi 100 agar muncul di atas modal raport --}}
    <div id="raportFormModal" class="hidden fixed inset-0 overflow-y-auto" style="z-index: 100;">
        {{-- Backdrop --}}
        <div class="fixed inset-0 modal-overlay transition-opacity" id="closeFormModalBtn"></div>
        
        <div class="flex min-h-screen items-center justify-center px-4 py-10 text-center sm:px-6">
            {{-- Modal Content: Ukuran diperkecil ke max-w-md --}}
            <div class="relative w-full max-w-md transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all flex flex-col">
                
                {{-- Header --}}
                <div class="bg-gradient-to-r from-cyan-500 to-blue-600 px-6 py-5 flex justify-between items-center shrink-0">
                    <div>
                        <h3 class="text-xl font-bold text-white" id="formModalTitle">Tambah Data Raport</h3>
                        <p class="text-sm text-cyan-100 mt-0.5">Input data performa atlet</p>
                    </div>
                    <button type="button" id="cancelFormBtn" class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center text-white hover:bg-white/20 transition-colors">
                        <i data-feather="x" class="w-5 h-5"></i>
                    </button>
                </div>

                {{-- Body Form --}}
                <div class="p-6 overflow-y-auto custom-scrollbar max-h-[80vh]">
                    <form id="raportForm" class="space-y-5">
                        <input type="hidden" name="id" id="raport_id">
                        <input type="hidden" name="member_id" id="form_member_id">
                        <input type="hidden" name="gaya" id="form_gaya">
                        <input type="hidden" name="year" id="form_year">

                        {{-- Bulan --}}
                        <div id="monthFieldWrapper">
                            <label class="block text-sm font-bold text-slate-700 mb-2">Bulan</label>
                            <select name="month" id="month" class="input-field w-full" required>
                                <option value="">-- Pilih Bulan --</option>
                                {{-- Option diisi via JS --}}
                            </select>
                        </div>

                        {{-- Waktu --}}
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Waktu (Detik)</label>
                            <div class="relative">
                                <input type="number" step="0.01" name="value" id="value" class="input-field w-full pl-10" placeholder="Contoh: 30.50" required>
                                <div class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            {{-- Volume --}}
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Volume (m)</label>
                                <input type="number" name="volume" id="volume" class="input-field w-full" placeholder="Total meter" required>
                            </div>
                            {{-- Intensitas --}}
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Intensitas (%)</label>
                                <input type="number" name="intensity" id="intensity" class="input-field w-full" placeholder="0-100" required>
                            </div>
                        </div>

                        {{-- Peaking --}}
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Peaking</label>
                            <input type="number" name="peaking" id="peaking" class="input-field w-full" placeholder="Nilai Peaking">
                        </div>

                         {{-- Coach --}}
                         <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Coach Penilai</label>
                            <select name="coach_id" id="coach_id" class="input-field w-full" required>
                                <option value="">-- Pilih Coach --</option>
                            </select>
                        </div>

                        {{-- Catatan --}}
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Catatan (Opsional)</label>
                            <textarea name="note" id="note" rows="3" class="input-field w-full" placeholder="Catatan tambahan..."></textarea>
                        </div>

                        {{-- Tombol Simpan --}}
                        <div class="pt-4">
                            <button type="submit" class="w-full btn-primary py-3 flex items-center justify-center gap-2 font-bold shadow-lg hover:shadow-cyan-500/30">
                                <i data-feather="save" class="w-4 h-4"></i>
                                Simpan Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Custom Scripts --}}
    <script>
        // Initialize Feather Icons
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        });

        // Raport Modal Logic
        let currentMemberId = null;
        let chartValue = null;
        let chartVolume = null;
        let chartRadar = null;
        let isEditMode = false;
        let coaches = [];

        // Event Listeners
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof feather !== 'undefined') feather.replace(); 
            
            const closeFormBtn = document.getElementById('closeFormModalBtn');
            if(closeFormBtn) closeFormBtn.addEventListener('click', closeFormModal);
            
            const cancelFormBtn = document.getElementById('cancelFormBtn');
            if(cancelFormBtn) cancelFormBtn.addEventListener('click', closeFormModal);

            const gayaSelect = document.getElementById('gaya');
            if(gayaSelect) gayaSelect.addEventListener('change', loadRaportData);
            
            const yearInput = document.getElementById('year');
            if(yearInput) yearInput.addEventListener('input', loadRaportData);

            const formRaport = document.getElementById('raportForm');
            if(formRaport) formRaport.addEventListener('submit', handleFormSubmit);
        });

        // Modal Functions
        function openRaportModal(memberId, memberName) {
            currentMemberId = memberId;
            document.getElementById('memberName').textContent = memberName;
            document.getElementById('raportModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            loadCoachesList();
            loadRaportData();
            
            // Refresh icons in modal
            if (typeof feather !== 'undefined') {
                setTimeout(() => feather.replace(), 100);
            }
        }

        function closeRaportModal() {
            document.getElementById('raportModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
            if (chartValue) { chartValue.destroy(); chartValue = null; }
            if (chartVolume) { chartVolume.destroy(); chartVolume = null; }
        }

        function openCreateForm() {
            isEditMode = false;
            document.getElementById('formModalTitle').textContent = 'Tambah Data Raport';
            document.getElementById('raportForm').reset();
            document.getElementById('raport_id').value = '';
            document.getElementById('form_member_id').value = currentMemberId;
            document.getElementById('form_gaya').value = document.getElementById('gaya').value;
            document.getElementById('form_year').value = document.getElementById('year').value;
            document.getElementById('monthFieldWrapper').style.display = 'block';
            loadAvailableMonths();
            document.getElementById('raportFormModal').classList.remove('hidden');
            
            // Refresh icons
            if (typeof feather !== 'undefined') {
                setTimeout(() => feather.replace(), 100);
            }
        }

        function openEditForm(id, month, value, volume, intensity, peaking, coachId, note) {
            isEditMode = true;
            document.getElementById('formModalTitle').textContent = 'Edit Data Raport';
            document.getElementById('raport_id').value = id;
            document.getElementById('value').value = parseFloat(value).toFixed(2);
            document.getElementById('volume').value = volume;
            document.getElementById('intensity').value = intensity;
            document.getElementById('peaking').value = peaking;
            document.getElementById('coach_id').value = coachId;
            document.getElementById('note').value = note;
            document.getElementById('form_member_id').value = currentMemberId;
            document.getElementById('form_gaya').value = document.getElementById('gaya').value;
            document.getElementById('form_year').value = document.getElementById('year').value;
            document.getElementById('monthFieldWrapper').style.display = 'none';
            document.getElementById('raportFormModal').classList.remove('hidden');
            
            // Refresh icons
            if (typeof feather !== 'undefined') {
                setTimeout(() => feather.replace(), 100);
            }
        }

        function closeFormModal() {
            document.getElementById('raportFormModal').classList.add('hidden');
            document.getElementById('raportForm').reset();
        }

        // Form Handler
        function handleFormSubmit(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const data = Object.fromEntries(formData.entries());
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            let url = isEditMode ? `/api/raport/update/${document.getElementById('raport_id').value}` : '/api/raport/create';
            let method = isEditMode ? 'PUT' : 'POST';

            fetch(url, {
                method: method,
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert(isEditMode ? 'Berhasil diupdate!' : 'Berhasil ditambahkan!', 'success');
                    closeFormModal();
                    loadRaportData();
                } else {
                    showAlert(data.message || 'Gagal menyimpan data', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Terjadi kesalahan sistem', 'error');
            });
        }

        // Data Functions
        function loadRaportData() {
            const gayaSelect = document.getElementById('gaya');
            const gaya = gayaSelect.value; // Ambil nilai gaya
            const year = document.getElementById('year').value;
            
            // === [MULAI PERUBAHAN] ===
            // Cek apakah gaya masih kosong?
            if (!gaya) {
                // Jika kosong, jangan lakukan apa-apa (Stop)
                // Opsional: Kamu bisa reset tabel biar bersih
                const tbody = document.querySelector('#raport-table tbody');
                if(tbody) {
                    tbody.innerHTML = '<tr><td colspan="6" class="px-6 py-8 text-center text-slate-400">Silakan pilih Kategori Gaya terlebih dahulu.</td></tr>';
                }
                return; // <--- INI KUNCI UTAMANYA (Hentikan proses)
            }
            // === [AKHIR PERUBAHAN] ===

            // Kode lama kamu di bawah ini tetap sama...
            fetch(`/api/raport/chart-data?member_id=${currentMemberId}&gaya=${gaya}&year=${year}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateDetailInfo(data.raports);
                        updateTable(data.raports);
                        updateCharts(data.chartValue, data.chartVolume);
                    } else {
                        showAlert('Gagal memuat data: ' + data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    // Hilangkan alert error ini agar tidak mengganggu jika data belum lengkap
                    // showAlert('Gagal memuat data raport', 'error'); 
                });
        }

        function updateTable(raports) {
            const tbody = document.querySelector('#raport-table tbody');
            tbody.innerHTML = '';
            
            if (raports.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" class="px-6 py-8 text-center text-slate-400">Tidak ada data.</td></tr>';
                return;
            }
            
            raports.forEach(r => {
                // Format waktu (00:00.00)
                const formattedTime = `${String(Math.floor(r.value / 60)).padStart(2, '0')}:${(r.value % 60).toFixed(2).padStart(5, '0')}`;
                
                // Format Peaking
                const peakingValue = r.peaking ? r.peaking : '-';

                const row = `
                    <tr class="hover:bg-slate-50 transition-colors border-b border-slate-100">
                        <td class="px-5 py-3 font-bold text-slate-800 capitalize">${r.month}</td>
                        <td class="px-5 py-3 text-cyan-600 font-mono font-bold">${formattedTime}</td>
                        <td class="px-5 py-3 text-slate-600">${r.volume}m</td>
                        <td class="px-5 py-3"><span class="px-2 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-600">${r.intensity}%</span></td>
                        <td class="px-5 py-3 font-medium text-slate-700">${peakingValue}</td>
                        
                        <td class="px-5 py-3 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button onclick="openEditForm(${r.id}, '${r.month}', '${r.value}', '${r.volume}', '${r.intensity}', '${r.peaking || ''}', '${r.coach_id}', '${r.note || ''}')" 
                                        class="btn-action btn-edit" 
                                        title="Edit Data">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                                    </svg>
                                </button>
                                
                                <button onclick="confirmDelete(${r.id}, '${r.month}')" 
                                        class="btn-action btn-delete" 
                                        title="Hapus Data">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="3 6 5 6 21 6"></polyline>
                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                        <line x1="10" y1="11" x2="10" y2="17"></line>
                                        <line x1="14" y1="11" x2="14" y2="17"></line>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
                tbody.insertAdjacentHTML('beforeend', row);
            });
            
            // Refresh icons feather setelah menambahkan elemen baru
            if (typeof feather !== 'undefined') {
                setTimeout(() => feather.replace(), 100);
            }
        }

        function updateCharts(valueData, volumeData) {
            if (chartValue) chartValue.destroy();
            if (chartVolume) chartVolume.destroy();

            if (typeof Chart === 'undefined') {
                console.error('Chart.js belum dimuat.');
                return;
            }

            const colorText = '#64748b';
            const colorGrid = '#e2e8f0';
            const primaryColor = '#0891b2';
            
            const barColors = [
                '#0891b2',
                '#10b981',
                '#8b5cf6',
                '#f59e0b',
                '#ef4444',
                '#06b6d4',
                '#84cc16',
                '#8b5cf6',
            ];

            // Chart Line untuk Waktu
            const ctx1 = document.getElementById('chartValue').getContext('2d');
            chartValue = new Chart(ctx1, {
                type: 'line',
                data: valueData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    elements: {
                        line: { 
                            borderColor: primaryColor, 
                            borderWidth: 3, 
                            tension: 0.4 
                        }, 
                        point: { 
                            backgroundColor: '#ffffff', 
                            borderColor: primaryColor, 
                            borderWidth: 2, 
                            radius: 6,
                            hoverRadius: 8
                        }
                    },
                    scales: {
                        y: { 
                            reverse: true, 
                            ticks: { 
                                color: colorText,
                                font: { size: 12, weight: '600' }
                            }, 
                            grid: { 
                                color: colorGrid,
                                drawBorder: false
                            },
                            title: {
                                display: true,
                                text: 'Waktu (detik)',
                                color: colorText,
                                font: { size: 12, weight: '700' }
                            }
                        },
                        x: { 
                            ticks: { 
                                color: colorText,
                                font: { size: 12, weight: '600' }
                            }, 
                            grid: { 
                                display: false 
                            },
                            title: {
                                display: true,
                                text: 'Bulan',
                                color: colorText,
                                font: { size: 12, weight: '700' }
                            }
                        }
                    },
                    plugins: { 
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: 'rgba(15, 23, 42, 0.9)',
                            titleColor: '#f1f5f9',
                            bodyColor: '#f1f5f9',
                            borderColor: '#0891b2',
                            borderWidth: 1,
                            cornerRadius: 8,
                            displayColors: false,
                            callbacks: {
                                label: function(context) {
                                    return `Waktu: ${context.parsed.y.toFixed(2)} detik`;
                                }
                            }
                        }
                    }
                }
            });

            // Chart Bar untuk Volume & Intensitas
            const ctx2 = document.getElementById('chartVolume').getContext('2d');
            
            if (volumeData && volumeData.datasets) {
                volumeData.datasets.forEach((dataset, index) => {
                    if (index === 0) {
                        dataset.backgroundColor = barColors[0];
                        dataset.borderColor = barColors[0];
                        dataset.borderWidth = 2;
                        dataset.borderRadius = 6;
                        dataset.barPercentage = 0.7;
                    }
                    if (index === 1) {
                        dataset.backgroundColor = barColors[1];
                        dataset.borderColor = barColors[1];
                        dataset.borderWidth = 2;
                        dataset.borderRadius = 6;
                        dataset.barPercentage = 0.7;
                    }
                });
            }

            chartVolume = new Chart(ctx2, {
                type: 'bar',
                data: volumeData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: { 
                            beginAtZero: true,
                            ticks: { 
                                color: colorText,
                                font: { size: 12, weight: '600' }
                            }, 
                            grid: { 
                                color: colorGrid,
                                drawBorder: false
                            },
                            title: {
                                display: true,
                                text: 'Nilai',
                                color: colorText,
                                font: { size: 12, weight: '700' }
                            }
                        },
                        x: { 
                            ticks: { 
                                color: colorText,
                                font: { size: 12, weight: '600' }
                            }, 
                            grid: { 
                                display: false 
                            },
                            title: {
                                display: true,
                                text: 'Bulan',
                                color: colorText,
                                font: { size: 12, weight: '700' }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                            labels: {
                                color: colorText,
                                font: { size: 12, weight: '600' },
                                padding: 20,
                                usePointStyle: true,
                                pointStyle: 'rect'
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(15, 23, 42, 0.9)',
                            titleColor: '#f1f5f9',
                            bodyColor: '#f1f5f9',
                            borderColor: '#0891b2',
                            borderWidth: 1,
                            cornerRadius: 8,
                            callbacks: {
                                label: function(context) {
                                    const label = context.dataset.label || '';
                                    const value = context.parsed.y;
                                    return `${label}: ${value}`;
                                }
                            }
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    }
                }
            });
        }

        function updateDetailInfo(raports) {
            const detailDiv = document.getElementById('raport-detail');
            if (!detailDiv) return;

            if (raports.length === 0) {
                detailDiv.innerHTML = '<p class="text-center italic mb-4">Belum ada data untuk periode ini.</p>';
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

        function loadCoachesList() {
            fetch('/api/raport/coaches').then(r => r.json()).then(d => {
                if(d.success) {
                    const s = document.getElementById('coach_id');
                    s.innerHTML = '<option value="">-- Pilih Coach --</option>';
                    d.coaches.forEach(c => s.innerHTML += `<option value="${c.id}">${c.name}</option>`);
                }
            });
        }

        function loadAvailableMonths() {
            const gaya = document.getElementById('gaya').value;
            const year = document.getElementById('year').value;
            fetch(`/api/raport/available-months?member_id=${currentMemberId}&gaya=${gaya}&year=${year}`)
                .then(r => r.json()).then(d => {
                    if(d.success) {
                        const s = document.getElementById('month');
                        s.innerHTML = '<option value="">-- Pilih Bulan --</option>';
                        Object.entries(d.months).forEach(([k, v]) => s.innerHTML += `<option value="${k}">${v}</option>`);
                    }
                });
        }

        function confirmDelete(id, month) {
            if(confirm(`Hapus data bulan ${month}?`)) {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                fetch(`/api/raport/delete/${id}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': csrfToken } })
                    .then(r => r.json()).then(d => {
                        if(d.success) { showAlert('Dihapus!', 'success'); loadRaportData(); }
                        else { showAlert('Gagal hapus', 'error'); }
                    });
            }
        }

        function showAlert(message, type = 'success') {
            const div = document.createElement('div');
            div.className = `fixed top-4 right-4 px-6 py-3 rounded-xl shadow-xl z-[100] text-white font-bold transition-all transform duration-500 translate-y-0 ${type === 'success' ? 'bg-cyan-600' : 'bg-red-500'}`;
            div.textContent = message;
            document.body.appendChild(div);
            setTimeout(() => { div.style.opacity = '0'; setTimeout(() => div.remove(), 500); }, 3000);
        }


        // --- FUNCTION KHUSUS FISIK ---
        function openPhysicalModal(memberId, memberName) {
            currentMemberId = memberId;
            document.getElementById('physMemberName').textContent = memberName;
            document.getElementById('physicalModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            loadPhysicalData(); // Panggil loader fisik
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
            if (chartRadar) chartRadar.destroy();
            const ctx = document.getElementById('chartRadar').getContext('2d');
            chartRadar = new Chart(ctx, {
                type: 'radar',
                data: {
                    labels: ['Speed', 'Strength', 'Endurance', 'Flexibility', 'Agility'],
                    datasets: [{
                        label: 'Profil Atlet',
                        data: radarData,
                        backgroundColor: 'rgba(244, 63, 94, 0.2)',
                        borderColor: 'rgb(244, 63, 94)',
                        pointBackgroundColor: 'rgb(244, 63, 94)',
                    }]
                },
                options: { scales: { r: { min: 0, max: 5, ticks: { display: false } } }, plugins: { legend: { display: false } } }
            });
        }

        function handlePhysSubmit(e) {
            e.preventDefault();
            const data = Object.fromEntries(new FormData(e.target).entries());
            fetch('/api/physical/store', {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content 
                },
                body: JSON.stringify(data)
            }).then(res => res.json()).then(res => {
                if (res.success) {
                    showAlert('Data Fisik Tersimpan!', 'success');
                    document.getElementById('physFormModal').classList.add('hidden');
                    loadPhysicalData();
                }
            });
        }

        // ==========================================
        // TAMBAHAN LOGIKA UNTUK JALUR FISIK
        // ==========================================

        // 1. Tambahkan listener submit untuk Form Fisik saat DOM Ready
        document.addEventListener('DOMContentLoaded', function() {
            const physForm = document.getElementById('physForm');
            if (physForm) {
                physForm.addEventListener('submit', handlePhysSubmit);
            }
        });

        // 2. Fungsi untuk membuka modal form fisik (Level 2)
        function openPhysForm() {
            const form = document.getElementById('physForm');
            if (form) form.reset();

            // Reset tampilan kalkulator vo2max
            const vo2Field = document.getElementById('vo2max');
            if (vo2Field) vo2Field.value = '';

            // Injeksi ID Member dan Tahun dari konteks modal fisik yang aktif
            const memberField = document.getElementById('phys_form_member_id');
            const yearField = document.getElementById('phys_form_year');
            const physYearInput = document.getElementById('phys_year');

            if (memberField) memberField.value = currentMemberId;
            if (yearField && physYearInput) yearField.value = physYearInput.value;

            // Buka Modal Form
            const modal = document.getElementById('physFormModal');
            if (modal) {
                modal.classList.remove('hidden');
            }
            
            if (typeof feather !== 'undefined') feather.replace();
        }

        // 3. Fungsi tutup modal form fisik
        function closePhysFormModal() {
            const modal = document.getElementById('physFormModal');
            if (modal) modal.classList.add('hidden');
        }

        // 4. Kalkulator Bleep Test (VO2Max) Otomatis
        function calculateBleep() {
            const lvlInput = document.getElementById('bleep_level');
            const shtInput = document.getElementById('bleep_shuttle');
            const vo2Field = document.getElementById('vo2max');

            if (!lvlInput || !shtInput || !vo2Field) return;

            const lvl = parseInt(lvlInput.value) || 0;
            const sht = parseInt(shtInput.value) || 0;
            
            if (lvl > 0) {
                // Standar MSFT Table mapping
                const shuttleTable = { 1: 9, 2: 8, 3: 8, 4: 9, 5: 9, 6: 10, 7: 10, 8: 11, 9: 11, 10: 11, 11: 12, 12: 12, 13: 13 };
                const tsl = shuttleTable[lvl] || 10;
                
                // Rumus kalkulasi VO2Max
                const vo2 = 3.46 * (lvl + (sht / tsl)) + 12.2;
                vo2Field.value = vo2.toFixed(2);
            } else {
                vo2Field.value = '';
            }
        }

        // 5. Perbaikan Render Radar (Memastikan Context Canvas Terbaca)
        // (Fungsi ini akan melengkapi fungsi renderRadarChart yang sudah kamu punya agar lebih stabil)
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
                        pointBorderColor: '#fff',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        r: {
                            min: 0,
                            max: 5,
                            beginAtZero: true,
                            ticks: { display: false, stepSize: 1 },
                            grid: { color: 'rgba(0,0,0,0.05)' },
                            angleLines: { color: 'rgba(0,0,0,0.05)' },
                            pointLabels: { font: { size: 10, weight: 'bold' } }
                        }
                    },
                    plugins: { legend: { display: false } }
                }
            });
        }
    </script>
</body>
</html>