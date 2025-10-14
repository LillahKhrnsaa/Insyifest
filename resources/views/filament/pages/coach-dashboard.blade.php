<x-filament::page>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <div class="space-y-8">

        {{-- Profil + Statistik Atas --}}
        <div class="bg-[color:var(--fi-color-surface)] shadow border border-[color:var(--fi-color-border)] rounded-2xl p-6 flex flex-col sm:flex-row gap-6 transition-colors">

            {{-- Foto --}}
            @if ($coach->user->photo_url)
                <img src="{{ $coach->user->photo_url }}" alt="Foto"
                    class="w-24 h-24 rounded-full object-cover ring-2 ring-[color:var(--fi-color-primary-500)]">
            @else
                <div class="w-24 h-24 rounded-full flex items-center justify-center
                            bg-[color:var(--fi-color-gray-200)] dark:bg-[color:var(--fi-color-gray-800)]
                            text-[color:var(--fi-color-gray-500)]">
                    No Photo
                </div>
            @endif

            {{-- Info & Statistik mini --}}
            <div class="flex-1">
                <h2 class="text-xl font-semibold text-[color:var(--fi-color-gray-900)] dark:text-[color:var(--fi-color-gray-50)]">
                    {{ $coach->user->name }}
                </h2>
                <p class="text-sm text-[color:var(--fi-color-gray-600)]">{{ $coach->user->email }}</p>
                <p class="text-sm italic mt-1 text-[color:var(--fi-color-gray-500)]">
                    {{ $coach->bio ?? 'Belum ada bio.' }}
                </p>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-4 text-center">
                    @php
                        $stats = [
                            ['label' => 'Total Member', 'value' => $totalMembers, 'color' => 'primary'],
                            ['label' => 'Aktif', 'value' => $activeMembers, 'color' => 'success'],
                            ['label' => 'Tidak Aktif', 'value' => $inactiveMembers, 'color' => 'danger'],
                            ['label' => 'Jadwal Latihan', 'value' => $totalSchedules, 'color' => 'warning'],
                        ];
                    @endphp
                    @foreach ($stats as $s)
                        <div class="rounded-xl py-3"
                             style="background-color: var(--fi-color-{{ $s['color'] }}-100);
                                    color: var(--fi-color-{{ $s['color'] }}-600)">
                            <p class="text-xs opacity-80">{{ $s['label'] }}</p>
                            <p class="text-xl font-bold">{{ $s['value'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Dua kolom: Atlet & Jadwal --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- Atlet yang Dilatih --}}
            <div class="bg-[color:var(--fi-color-surface)] border border-[color:var(--fi-color-border)] rounded-2xl p-6 shadow transition-colors">
                <h3 class="text-lg font-semibold mb-4 text-[color:var(--fi-color-gray-900)] dark:text-[color:var(--fi-color-gray-100)]">
                    Atlet yang Dilatih
                </h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left border-collapse">
                        <thead>
                            <tr class="border-b border-[color:var(--fi-color-border)] text-[color:var(--fi-color-gray-600)]">
                                <th class="p-2">Nama</th>
                                <th class="p-2">Status</th>
                                <th class="p-2 text-right">Raport</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($coach->members as $member)
                                <tr class="border-b border-[color:var(--fi_color-border)] hover:bg-[color:var(--fi-color-gray-50)] dark:hover:bg-[color:var(--fi-color-gray-800)] transition">
                                    <td class="p-2">{{ $member->user->name }}</td>
                                    <td class="p-2">
                                        <span class="px-2 py-1 rounded text-white text-xs"
                                            style="background-color: var(--fi-color-{{ $member->status == 'AKTIF' ? 'success' : 'danger' }}-500)">
                                            {{ $member->status }}
                                        </span>
                                    </td>
                                    <td class="p-2 text-right">
                                        <x-filament::button size="xs" color="primary">Raport</x-filament::button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-[color:var(--fi-color-gray-500)] italic">
                                        Belum ada member yang dilatih
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Jadwal Latihan --}}
            <div class="bg-[color:var(--fi-color-surface)] border border-[color:var(--fi-color-border)] rounded-2xl p-6 shadow transition-colors">
                <h3 class="text-lg font-semibold mb-4 text-[color:var(--fi-color_gray-900)] dark:text-[color:var(--fi-color_gray-100)]">
                    Jadwal Latihan
                </h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left border-collapse">
                        <thead>
                            <tr class="border-b border-[color:var(--fi-color-border)] text-[color:var(--fi-color-gray-600)]">
                                <th class="p-2">Hari</th>
                                <th class="p-2">Waktu</th>
                                <th class="p-2">Lokasi</th>
                                <th class="p-2 text-right">Absen</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($coach->trainingSchedules as $schedule)
                                <tr class="border-b border-[color:var(--fi-color-border)] hover:bg-[color:var(--fi-color-gray-50)] dark:hover:bg-[color:var(--fi-color-gray-800)] transition">
                                    <td class="p-2">{{ ucfirst(strtolower($schedule->day)) }}</td>
                                    <td class="p-2">{{ $schedule->time ?? '-' }}</td>
                                    <td class="p-2">{{ $schedule->place ?? '-' }}</td>
                                    <td class="p-2 text-right">
                                        <x-filament::button size="xs" color="success">Absen</x-filament::button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-[color:var(--fi-color-gray-500)] italic">
                                        Belum ada jadwal latihan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-filament::page>
