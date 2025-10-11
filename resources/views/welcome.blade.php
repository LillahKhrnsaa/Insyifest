@extends('layouts.app')

@section('content')

{{-- Hero --}}
<section class="relative h-[100vh] bg-cover bg-center flex items-center justify-center overflow-hidden"
         style="background-image: url('{{ asset('images/bgcsc.jpg') }}');">
    <div class="absolute inset-0 bg-black/50"></div>
    <div class="relative z-10 text-center px-4 fade-in">
        <h1 class="font-extrabold text-4xl sm:text-5xl md:text-6xl tracking-tight text-white drop-shadow-lg leading-tight">
            Ayo Berenang & Raih Mimpimu! <br> ASCA Inter & National Certified Swim Coach
        </h1>
        <p class="mt-6 text-lg md:text-xl max-w-3xl mx-auto text-gray-200 drop-shadow-md">
            Bergabunglah dengan klub renang paling seru di Cikampek. Belajar, bermain, dan menjadi juara bersama kami!
        </p>
        <a href="{{ route('member.register.store') }}"
           class="mt-10 inline-block bg-yellow-400 text-slate-800 font-extrabold py-4 px-12 rounded-full hover:bg-yellow-500 transition-all duration-300 shadow-xl transform hover:-translate-y-1 text-lg">
            Daftar Sekarang!
        </a>
    </div>
    <div class="absolute bottom-0 left-0 w-full h-24 bg-sky-50"></div>
</section>

{{-- Keunggulan --}}
<section id="keunggulan" class="py-20 sm:py-28 bg-sky-50">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center fade-in">
            <h2 class="font-extrabold text-3xl sm:text-4xl tracking-tight text-slate-900">Kenapa CSC Pilihan Terbaik?</h2>
            <p class="mt-4 max-w-2xl mx-auto text-lg text-slate-600">Karena di sini, belajar renang itu menyenangkan!</p>
        </div>
        <div class="mt-16 grid grid-cols-1 md:grid-cols-3 gap-10 text-center">
            {{-- Keunggulan 1 --}}
            <div class="fade-in transform hover:-translate-y-2 transition-transform duration-300">
                <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-2xl bg-cyan-400 text-white shadow-lg">
                    <svg class="h-10 w-10" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                    </svg>
                </div>
                <h3 class="mt-6 text-xl font-bold text-slate-900">Pelatih Hebat & Sabar</h3>
                <p class="mt-2 text-slate-600">Pelatih kami jago mengajar, bersertifikat, dan pastinya sabar menghadapi setiap anak.</p>
            </div>

            {{-- Keunggulan 2 --}}
            <div class="fade-in transform hover:-translate-y-2 transition-transform duration-300">
                <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-2xl bg-yellow-400 text-white shadow-lg">
                    <svg class="h-10 w-10" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                    </svg>
                </div>
                <h3 class="mt-6 text-xl font-bold text-slate-900">Fasilitas Aman & Nyaman</h3>
                <p class="mt-2 text-slate-600">Kolam renang bersih dan terawat, serta perlengkapan yang aman untuk semua usia.</p>
            </div>

            {{-- Keunggulan 3 --}}
            <div class="fade-in transform hover:-translate-y-2 transition-transform duration-300">
                <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-2xl bg-pink-400 text-white shadow-lg">
                    <svg class="h-10 w-10" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m-7.5-2.962A3.75 3.75 0 0115 9.75v.008c0 .023.002.046.005.069a3.75 3.75 0 01.8-2.583m-5.823 3.543a3.75 3.75 0 01-.8-2.583V9.75a3.75 3.75 0 013.75-3.75c.989 0 1.908.405 2.564 1.064M15 9.75a3.75 3.75 0 01-3.75 3.75M3 13.5a3 3 0 013-3V12m0 0v-1.5a3 3 0 013-3m-3 3a3 3 0 00-3 3m9-9.75h1.5a3 3 0 013 3v1.5a3 3 0 01-3 3h-1.5a3 3 0 01-3-3v-1.5a3 3 0 013-3z" />
                    </svg>
                </div>
                <h3 class="mt-6 text-xl font-bold text-slate-900">Banyak Teman Baru</h3>
                <p class="mt-2 text-slate-600">Anak akan bertemu banyak teman baru, belajar kerja sama, dan jadi lebih percaya diri.</p>
            </div>
        </div>
    </div>
</section>

{{-- Jadwal Latihan --}}
<section id="jadwal" class="py-20 sm:py-28 bg-sky-50">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center fade-in">
            <h2 class="font-extrabold text-3xl sm:text-4xl tracking-tight text-slate-900">Jadwal Latihan</h2>
            <p class="mt-4 max-w-2xl mx-auto text-lg text-slate-600">Pilih waktu yang paling pas untuk jagoan kecilmu!</p>
        </div>
        <div class="mt-16 overflow-x-auto shadow-xl rounded-2xl ring-1 ring-slate-200 bg-white fade-in">
            <table class="min-w-full">
                <thead class="bg-cyan-600 text-white">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-bold uppercase tracking-wider">Hari</th>
                        <th class="px-6 py-4 text-left text-sm font-bold uppercase tracking-wider">Waktu</th>
                        <th class="px-6 py-4 text-left text-sm font-bold uppercase tracking-wider">Tempat</th>
                        <th class="px-6 py-4 text-left text-sm font-bold uppercase tracking-wider">Pelatih</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($schedules as $schedule)
                        <tr class="hover:bg-slate-50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap text-base font-semibold text-slate-800">{{ $schedule->day }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-base text-slate-600">{{ $schedule->time }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-base text-slate-600">{{ $schedule->place }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-base font-semibold text-slate-800">{{ $schedule->coach->user->name ?? 'N/A' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center px-6 py-10 text-slate-500">Jadwal latihan akan segera diumumkan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>

{{-- Paket --}}
<section id="paket" class="py-20 sm:py-28 bg-sky-50">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center fade-in">
            <h2 class="font-extrabold text-3xl sm:text-4xl tracking-tight text-slate-900">Pilihan Paket Seru</h2>
            <p class="mt-4 max-w-2xl mx-auto text-lg text-slate-600">Investasi terbaik untuk prestasi dan keceriaan anak Anda.</p>
        </div>
        <div class="mt-16 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($packages as $package)
                <div class="fade-in bg-white rounded-2xl shadow-lg p-8 flex flex-col text-center transform hover:-translate-y-2 transition-transform duration-300 ring-1 ring-slate-100">
                    <h3 class="text-2xl font-bold text-slate-900 mb-4">{{ $package->name }}</h3>
                    <p class="text-5xl font-extrabold text-cyan-500 my-4">Rp {{ number_format($package->price, 0, ',', '.') }}</p>
                    <p class="text-slate-600 mb-8 flex-grow">{{ $package->description }}</p>
                    <a href="{{ route('member.register.store') }}" class="mt-auto w-full inline-block bg-slate-800 text-white px-6 py-3 rounded-full font-bold hover:bg-slate-900 transition-colors">
                        Pilih Paket Ini
                    </a>
                </div>
            @empty
                <p class="col-span-3 text-center text-slate-500">Informasi paket akan segera tersedia.</p>
            @endforelse
        </div>
    </div>
</section>

{{-- Berita --}}
<section id="berita" class="py-20 sm:py-28 bg-sky-50">
    <div class="container mx-auto">
        <div class="text-center fade-in px-4 sm:px-6 lg:px-8">
            <h2 class="font-extrabold text-3xl sm:text-4xl tracking-tight text-slate-900">Cerita dari Kolam Renang</h2>
            <p class="mt-4 max-w-2xl mx-auto text-lg text-slate-600">Lihat keseruan dan prestasi terbaru dari keluarga besar CSC!</p>
        </div>
        <div x-data="{
                cloneItems() {
                    const items = this.$refs.items;
                    const clone = items.cloneNode(true);
                    this.$refs.marquee.appendChild(clone);
                }
            }"
             x-init="cloneItems()"
             class="relative mt-16 fade-in w-full overflow-hidden">
            <div x-ref="marquee" class="flex w-max hover:[animation-play-state:paused]">
                <div x-ref="items" class="flex animate-marquee">
                    @forelse($posts as $post)
                        <div class="flex-shrink-0 w-[85vw] sm:w-[45vw] md:w-[30vw] lg:w-[25vw] mx-4">
                            <div class="bg-white rounded-2xl overflow-hidden shadow-lg flex flex-col h-full group">
                                <div class="overflow-hidden">
                                    <img class="w-full h-56 object-cover group-hover:scale-105 transition-transform duration-300" src="{{ $post->file_path ? Storage::url($post->file_path) : 'https://source.unsplash.com/400x300/?kids,swimming' }}" alt="{{ $post->title }}">
                                </div>
                                <div class="p-6 flex flex-col flex-grow">
                                    <span class="inline-block bg-yellow-400 text-slate-800 text-xs font-bold px-3 py-1 rounded-full self-start mb-3">{{ \Carbon\Carbon::parse($post->created_at)->translatedFormat('d F Y') }}</span>
                                    <h3 class="font-bold text-xl text-slate-900 mb-3">{{ $post->title }}</h3>
                                    <div class="text-slate-600 text-base line-clamp-3 mb-4 flex-grow">{!! strip_tags($post->description) !!}</div>
                                    <a href="#" class="mt-auto font-bold text-cyan-600 hover:text-cyan-800 self-start">Lihat Selengkapnya &rarr;</a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="w-full text-center text-slate-500 px-4">Belum ada cerita baru yang dipublikasikan.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
