<!DOCTYPE html>
<html lang="id" class="">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coach Dashboard</title>
    
    {{-- Script Tailwind CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    
    {{-- Script Alpine.js (WAJIB untuk modal) --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    {{-- Font Inter --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    {{-- Script untuk Dark Mode Otomatis --}}
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark')
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        /* Sembunyikan elemen yang belum di-init Alpine */
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-100 dark:bg-gray-950">

{{-- 
  BUNGKUS SEMUA KONTEN DENGAN 'x-data' 
  untuk mengelola state modal
--}}
<div x-data="{ 
        showModal: {{ $errors->any() ? 'true' : 'false' }}, 
        selectedScheduleId: {{ old('schedule_id') ?? 'null' }}, 
        selectedSchedulePlace: '{{ old('place') ?? '' }}' 
     }">

    {{-- Navigasi Atas (Tidak Berubah) --}}
    <nav class="bg-white dark:bg-gray-900 shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                {{-- Logo atau Nama Aplikasi --}}
                <div class="flex-shrink-0">
                    <a href="#" class="text-xl font-bold text-indigo-600 dark:text-indigo-400">Swimming Club</a>
                </div>

                {{-- Nama User dan Tombol Logout --}}
                <div class="flex items-center">
                    @auth
                        <span class="text-gray-700 dark:text-gray-300 mr-4">
                            Halo, <span class="font-medium">{{ Auth::user()->name }}</span>
                        </span>
                        
                        {{-- Tombol Logout HARUS menggunakan form untuk keamanan (CSRF) --}}
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="px-3 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600">
                                Logout
                            </button>
                        </form>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- Konten Utama Dashboard (Gabungan) --}}
    <main>
        <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">

            {{-- Notifikasi Sukses/Error --}}
            @if (session('success'))
                <div class="mb-4 rounded-md bg-green-50 p-4 dark:bg-green-900/50">
                    <p class="text-sm font-medium text-green-800 dark:text-green-300">{{ session('success') }}</p>
                </div>
            @endif
            @if (session('error'))
                <div class="mb-4 rounded-md bg-red-50 p-4 dark:bg-red-900/50">
                    <p class="text-sm font-medium text-red-800 dark:text-red-300">{{ session('error') }}</p>
                </div>
            @endif
            @if($errors->any())
                <div class="mb-4 rounded-md bg-red-50 p-4 dark:bg-red-900/50">
                    <p class="text-sm font-medium text-red-800 dark:text-red-300">Form tidak valid, silakan periksa kembali input Anda di modal.</p>
                </div>
            @endif

            <div class="space-y-8">

                {{-- Profil + Statistik Atas --}}
                <div class="bg-white dark:bg-gray-900 shadow rounded-2xl p-6">
                    <div class="flex flex-col sm:flex-row gap-6">

                        {{-- Foto --}}
                        @if ($coach->user->photo_url)
                            <img src="{{ $coach->user->photo_url }}" alt="Foto"
                                 class="w-24 h-24 rounded-full object-cover ring-2 ring-indigo-500">
                        @else
                            <div class="w-24 h-24 rounded-full flex items-center justify-center
                                        bg-gray-200 dark:bg-gray-700
                                        text-gray-500 dark:text-gray-400">
                                No Photo
                            </div>
                        @endif

                        {{-- Info & Statistik mini --}}
                        <div class="flex-1">
                            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                                {{ $coach->user->name }}
                            </h2>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $coach->user->email }}</p>
                            <p class="text-sm italic mt-1 text-gray-500 dark:text-gray-400">
                                {{ $coach->bio ?? 'Belum ada bio.' }}
                            </p>

                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mt-4 text-center">
                                @php
                                    $stats = [
                                        ['label' => 'Total Member', 'value' => $totalMembers, 'color' => 'primary'],
                                        ['label' => 'Aktif', 'value' => $activeMembers, 'color' => 'success'],
                                        ['label' => 'Tidak Aktif', 'value' => $inactiveMembers, 'color' => 'danger'],
                                        ['label' => 'Jadwal Latihan', 'value' => $totalSchedules, 'color' => 'warning'],
                                    ];
                                    
                                    $colorClasses = [
                                        'primary' => 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900 dark:text-indigo-300',
                                        'success' => 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300',
                                        'danger'  => 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300',
                                        'warning' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300',
                                    ];
                                @endphp
                                @foreach ($stats as $s)
                                    <div class="rounded-xl py-3 {{ $colorClasses[$s['color']] ?? 'bg-gray-100 text-gray-700' }}">
                                        <p class="text-xs opacity-80">{{ $s['label'] }}</p>
                                        <p class="text-xl font-bold">{{ $s['value'] }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Dua kolom: Atlet & Jadwal --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                    {{-- Atlet yang Dilatih --}}
                    <div class="bg-white dark:bg-gray-900 shadow rounded-2xl p-6">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white mb-4">
                            Atlet yang Dilatih
                        </h3>

                        <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                            <table class="w-full min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-800">
                                    <tr>
                                        <th scope="col" class="px-4 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">Nama</th>
                                        <th scope="col" class="px-4 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">Status</th>
                                        <th scope="col" class="px-4 py-3.5 text-right text-sm font-semibold text-gray-900 dark:text-white">Raport</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-900">
                                    @forelse($coach->members as $member)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                            <td class="px-4 py-4 text-sm text-gray-700 dark:text-gray-300">{{ $member->user->name }}</td>
                                            <td class="px-4 py-4 text-sm text-gray-700 dark:text-gray-300">
                                                @php
                                                    $statusClasses = $member->status == 'AKTIF'
                                                        ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300'
                                                        : 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300';
                                                @endphp
                                                <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium {{ $statusClasses }}">
                                                    {{ $member->status }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-4 text-sm text-right">
                                                <a href="#" class="inline-flex items-center rounded-md bg-indigo-600 px-2.5 py-1.5 text-xs font-medium text-white shadow-sm hover:bg-indigo-700">
                                                    Raport
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="px-4 py-4 text-sm text-center text-gray-500 italic">
                                                Belum ada member yang dilatih
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Jadwal Latihan (TYPO SUDAH DIPERBAIKI) --}}
                    <div class="bg-white dark:bg-gray-900 shadow rounded-2xl p-6">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white mb-4">
                            Jadwal Latihan
                        </h3>
                        
                        <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                            <table class="w-full min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-800">
                                    <tr>
                                        <th scope="col" class="px-4 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">Hari</th>
                                        <th scope="col" class="px-4 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">Waktu</th>
                                        <th scope="col" class="px-4 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">Lokasi</th>
                                        <th scope="col" class="px-4 py-3.5 text-right text-sm font-semibold text-gray-900 dark:text-white">Absen</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-900">
                                    @forelse($coach->trainingSchedules as $schedule)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                            <td class="px-4 py-4 text-sm text-gray-700 dark:text-gray-300">{{ ucfirst(strtolower($schedule->day)) }}</td>
                                            {{-- Format Waktu --}}
                                            <td class="px-4 py-4 text-sm text-gray-700 dark:text-gray-300">{{ $schedule->time ? \Carbon\Carbon::parse($schedule->time)->format('H:i') : '-' }}</td>
                                            <td class="px-4 py-4 text-sm text-gray-700 dark:text-gray-300">{{ $schedule->place ?? '-' }}</td>
                                            <td class="px-4 py-4 text-sm text-right">
                                                
                                                {{-- Tombol Pemicu Modal --}}
                                                <button 
                                                    type="button"
                                                    @click="
                                                        showModal = true; 
                                                        selectedScheduleId = {{ $schedule->id }}; 
                                                        selectedSchedulePlace = '{{ $schedule->place ?? '' }}'
                                                    "
                                                    class="inline-flex items-center rounded-md bg-green-600 px-2.5 py-1.5 text-xs font-medium text-white shadow-sm hover:bg-green-700 dark:bg-green-500 dark:hover:bg-green-600">
                                                    Absen
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-4 py-4 text-sm text-center text-gray-500 italic">
                                                Belum ada jadwal latihan.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div> {{-- <-- Penutup 'div' yang sebelumnya typo --}}
                    {{-- KARTU BARU: RIWAYAT ABSENSI --}}
                    <div class="bg-white dark:bg-gray-900 shadow rounded-2xl p-6">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white mb-4">
                            Riwayat Absensi
                        </h3>
                        
                        <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                            <table class="w-full min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-800">
                                    <tr>
                                        <th scope="col" class="px-4 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">Tanggal</th>
                                        <th scope="col" class="px-4 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">Jadwal</th>
                                        <th scope="col" class="px-4 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">Lokasi</th>
                                        <th scope="col" class="px-4 py-3.5 text-right text-sm font-semibold text-gray-900 dark:text-white">Total Hadir</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-900">
                                    @forelse($attendances as $attendance)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                            {{-- Format Tanggal --}}
                                            <td class="px-4 py-4 text-sm text-gray-700 dark:text-gray-300">
                                                {{ \Carbon\Carbon::parse($attendance->date)->isoFormat('DD MMM YYYY') }}
                                            </td>
                                            {{-- Jadwal (dari relasi) --}}
                                            <td class="px-4 py-4 text-sm text-gray-700 dark:text-gray-300">
                                                {{ $attendance->schedule ? ucfirst(strtolower($attendance->schedule->day)) : 'N/A' }}
                                                ({{ $attendance->schedule && $attendance->schedule->time ? \Carbon\Carbon::parse($attendance->schedule->time)->format('H:i') : '-' }})
                                            </td>
                                            {{-- Lokasi (dari absensi) --}}
                                            <td class="px-4 py-4 text-sm text-gray-700 dark:text-gray-300">
                                                {{ $attendance->place ?? '-' }}
                                            </td>
                                            {{-- Total Hadir (dari withCount) --}}
                                            <td class="px-4 py-4 text-sm text-right text-gray-700 dark:text-gray-300 font-medium">
                                                {{ $attendance->members_count }} Orang
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-4 py-4 text-sm text-center text-gray-500 italic">
                                                Belum ada riwayat absensi.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div> {{-- Penutup 'grid' --}}
            </div> {{-- Penutup 'space-y-8' --}}
        </div> {{-- Penutup 'max-w-7xl' --}}
    </main>


    {{-- Modal Absensi --}}
    <div 
        x-show="showModal"
        x-cloak 
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-10 overflow-y-auto bg-gray-500 bg-opacity-75 transition-opacity"
        aria-labelledby="modal-title" role="dialog" aria-modal="true"
    >
        <div class="flex min-h-screen items-end justify-center px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            {{-- Latar belakang overlay (klik untuk menutup) --}}
            <div @click="showModal = false" class="fixed inset-0 transition-opacity" aria-hidden="true"></div>

            {{-- Centering trick --}}
            <span class="hidden sm:inline-block sm:h-screen sm:align-middle" aria-hidden="true">&#8203;</span>

            {{-- Panel Modal --}}
            <div 
                x-show="showModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="inline-block transform overflow-hidden rounded-lg bg-white dark:bg-gray-800 text-left align-bottom shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:align-middle"
            >
                <form 
                    method="POST" 
                    action="{{ route('attendance.store') }}" 
                    enctype="multipart/form-data"
                >
                    @csrf
                    
                    {{-- Konten Form di dalam Modal --}}
                    <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white" id="modal-title">
                            Form Absensi
                        </h3>
                        
                        {{-- Input tersembunyi untuk data dari baris tabel --}}
                        <input type="hidden" name="schedule_id" x-model="selectedScheduleId">
                        <input type="hidden" name="place" x-model="selectedSchedulePlace">

                        <div class="mt-6 space-y-4">
                            {{-- Tanggal --}}
                            <div>
                                <label for="date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Latihan</label>
                                <input type="date" name="date" id="date"
                                       value="{{ old('date', now()->format('Y-m-d')) }}" required
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm">
                                @error('date')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Foto (Opsional) --}}
                            <div>
                                <label for="photo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Foto Sesi (Opsional)</label>
                                <input type="file" name="photo" id="photo"
                                       class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-300
                                              file:mr-4 file:rounded-md file:border-0
                                              file:bg-indigo-50 file:py-2 file:px-4
                                              file:text-sm file:font-semibold file:text-indigo-700
                                              dark:file:bg-indigo-900/50 dark:file:text-indigo-300
                                              hover:file:bg-indigo-100">
                                @error('photo')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Member Reguler (Checkbox) --}}
                            <div>
                                <span class="block text-sm font-medium text-gray-700 dark:text-gray-300">Member Reguler Hadir</span>
                                <div class="mt-2 space-y-2 max-h-40 overflow-y-auto rounded-md border p-2 dark:border-gray-600">
                                    
                                    {{-- UBAH INI: dari $coach->members menjadi $activeRegularMembers --}}
                                    @forelse($activeRegularMembers as $member)
                                        <label class="flex items-center">
                                            <input type="checkbox" name="members[]" value="{{ $member->id }}"
                                                {{ (is_array(old('members')) && in_array($member->id, old('members'))) ? 'checked' : '' }}
                                                class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:focus:ring-indigo-600">
                                            <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ $member->user->name }}</span>
                                        </label>
                                    @empty
                                        {{-- Ubah juga pesan empty-nya --}}
                                        <p class="text-sm text-gray-500 italic">Tidak ada member reguler (yang aktif).</p>
                                    @endforelse
                                </div>
                                @error('members')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Member Tambahan (Checkbox) --}}
                            {{-- BLOK INI SUDAH BENAR, TIDAK PERLU DIUBAH --}}
                            <div>
                                <span class="block text-sm font-medium text-gray-700 dark:text-gray-300">Member Tambahan (Opsional)</span>
                                <div class="mt-2 space-y-2 max-h-40 overflow-y-auto rounded-md border p-2 dark:border-gray-600">
                                    @forelse($allOtherMembers as $member)
                                        <label class="flex items-center">
                                            <input type="checkbox" name="extra_members[]" value="{{ $member->id }}"
                                                {{ (is_array(old('extra_members')) && in_array($member->id, old('extra_members'))) ? 'checked' : '' }}
                                                class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:focus:ring-indigo-600">
                                            <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ $member->user->name }}</span>
                                        </label>
                                    @empty
                                        <p class="text-sm text-gray-500 italic">Tidak ada member lain.</p>
                                    @endforelse
                                </div>
                                @error('extra_members')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Tombol Aksi Modal --}}
                    <div class="bg-gray-50 dark:bg-gray-800 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <button type="submit"
                                class="inline-flex w-full justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm">
                            Simpan Absensi
                        </button>
                        <button type="button" 
                                @click="showModal = false"
                                class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600 sm:mt-0 sm:w-auto sm:text-sm">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
</div> {{-- Penutup 'x-data' --}}

</body>
</html>