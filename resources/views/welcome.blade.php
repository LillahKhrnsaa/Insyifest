@extends('layouts.app')

@section('content')

{{-- Hero --}}
<section class="relative min-h-screen bg-cover bg-center flex items-center justify-center overflow-hidden"
         style="background-image: url('{{ asset('images/bgcsc.jpg') }}');">
    <!-- Overlay dengan gradien yang lebih menarik -->
    <div class="absolute inset-0 bg-gradient-to-br from-blue-900/70 via-teal-800/50 to-cyan-600/60"></div>
    
    <!-- Efek gelembung air di background -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute -top-10 -left-10 w-72 h-72 bg-blue-400/20 rounded-full filter blur-xl animate-pulse-slow"></div>
        <div class="absolute top-1/3 -right-10 w-96 h-96 bg-cyan-300/15 rounded-full filter blur-xl animate-pulse-slower"></div>
        <div class="absolute -bottom-20 left-1/4 w-80 h-80 bg-teal-400/25 rounded-full filter blur-xl animate-pulse-slow"></div>
    </div>

    <!-- Konten utama -->
    <div class="relative z-10 text-center px-4 sm:px-6 md:px-8 lg:px-12 fade-in">
        <h1 class="font-extrabold text-3xl sm:text-4xl md:text-5xl lg:text-6xl xl:text-7xl tracking-tight text-white drop-shadow-lg leading-tight max-w-4xl mx-auto">
            Cikampek Swimming Club <br class="hidden sm:block"> 
            <span class="bg-gradient-to-r from-cyan-300 to-blue-200 bg-clip-text text-transparent">Long Term Atlet Development</span>
        </h1>
        <a href="{{ route('member.register.store') }}"
           class="mt-8 sm:mt-10 inline-block bg-gradient-to-r from-cyan-500 to-blue-600 text-white font-extrabold py-3 sm:py-4 px-8 sm:px-12 rounded-full hover:from-cyan-400 hover:to-blue-500 transition-all duration-300 shadow-xl transform hover:-translate-y-1 hover:shadow-2xl text-base sm:text-lg md:text-xl">
            Daftar Sekarang!
        </a>
    </div>
</section>


{{-- Keunggulan --}}
<section id="keunggulan" class="py-12 sm:py-20 lg:py-28 bg-gradient-to-b from-sky-50 to-white relative overflow-hidden">
    <!-- Background pattern -->
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-10 left-10 w-40 h-40 bg-cyan-400 rounded-full mix-blend-multiply filter blur-xl animate-pulse-slow"></div>
        <div class="absolute top-1/3 right-20 w-60 h-60 bg-blue-300 rounded-full mix-blend-multiply filter blur-xl animate-pulse-slower"></div>
        <div class="absolute bottom-20 left-1/3 w-52 h-52 bg-teal-300 rounded-full mix-blend-multiply filter blur-xl animate-pulse-slow"></div>
    </div>
    
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <!-- Heading -->
        <div class="text-center fade-in">
            <h2 class="font-extrabold text-2xl sm:text-3xl md:text-4xl tracking-tight text-slate-900">
                Kenapa CSC Pilihan Terbaik?
            </h2>
            <p class="mt-3 sm:mt-4 max-w-2xl mx-auto text-base sm:text-lg text-slate-600">
                Karena di sini, belajar renang itu menyenangkan!
            </p>
        </div>

        <!-- Grid Keunggulan -->
        <div class="mt-12 sm:mt-16 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 sm:gap-10 text-center">
            
            {{-- Keunggulan 1 --}}
            <div class="fade-in transform hover:-translate-y-2 transition-all duration-300 p-6 sm:p-8 bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg hover:shadow-xl border border-white/50">
                <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-2xl bg-gradient-to-br from-cyan-400 to-blue-500 text-white shadow-lg">
                    <svg class="h-10 w-10" xmlns="http://www.w3.org/2000/svg" fill="none"
                         viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                    </svg>
                </div>
                <h3 class="mt-6 text-lg sm:text-xl font-bold text-slate-900">
                    Pelatih Hebat & Ramah
                </h3>
                <p class="mt-3 text-sm sm:text-base text-slate-600">
                    Pelatih kami jago mengajar, bersertifikat, dan pastinya sabar serta friendly dalam membimbing setiap anak.
                </p>
            </div>

            {{-- Keunggulan 2 --}}
            <div class="fade-in transform hover:-translate-y-2 transition-all duration-300 p-6 sm:p-8 bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg hover:shadow-xl border border-white/50">
                <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-2xl bg-gradient-to-br from-yellow-400 to-orange-500 text-white shadow-lg">
                    <svg class="h-10 w-10" xmlns="http://www.w3.org/2000/svg" fill="none"
                         viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                    </svg>
                </div>
                <h3 class="mt-6 text-lg sm:text-xl font-bold text-slate-900">
                    Fasilitas Aman & Nyaman
                </h3>
                <p class="mt-3 text-sm sm:text-base text-slate-600">
                    Kolam renang bersih dan terawat, serta perlengkapan yang aman dan lengkap untuk semua usia.
                </p>
            </div>

            {{-- Keunggulan 3 --}}
            <div class="fade-in transform hover:-translate-y-2 transition-all duration-300 p-6 sm:p-8 bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg hover:shadow-xl border border-white/50">
                <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-2xl bg-gradient-to-br from-pink-400 to-rose-500 text-white shadow-lg">
                    <svg class="h-10 w-10" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                    </svg>
                </div>
                <h3 class="mt-6 text-lg sm:text-xl font-bold text-slate-900">
                    Banyak Teman Baru
                </h3>
                <p class="mt-3 text-sm sm:text-base text-slate-600">
                    Anak akan bertemu banyak teman baru, belajar bertahap, dan jadi lebih percaya diri.
                </p>
            </div>
        </div>
    </div>
</section>

{{-- Jadwal Latihan --}}
<section id="jadwal" class="py-10 sm:py-24 bg-gradient-to-b from-white to-sky-50 relative overflow-hidden">
    <!-- Background pattern -->
    <div class="absolute inset-0 opacity-5">
        <div class="absolute -top-20 -right-20 w-80 h-80 bg-cyan-400 rounded-full mix-blend-multiply filter blur-3xl"></div>
        <div class="absolute -bottom-20 -left-20 w-80 h-80 bg-blue-300 rounded-full mix-blend-multiply filter blur-3xl"></div>
    </div>
    
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center fade-in">
            <h2 class="font-extrabold text-3xl sm:text-4xl tracking-tight text-slate-900">Jadwal Latihan</h2>
            <p class="mt-4 max-w-2xl mx-auto text-lg text-slate-600">Pilih waktu yang paling pas untuk jagoan kecilmu!</p>
        </div>
        <div class="mt-16 overflow-x-auto shadow-xl rounded-2xl ring-1 ring-slate-200 bg-white/80 backdrop-blur-sm fade-in">
            <table class="min-w-full">
                <thead class="bg-gradient-to-r from-cyan-500 to-blue-600 text-white">
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
                            <td class="px-6 py-4 whitespace-nowrap text-base font-semibold text-slate-800">
                                {{-- KETERANGAN: Mengubah cara mengambil nama pelatih.
                                     Kita ambil pelatih PERTAMA dari relasi 'coaches' (jamak).
                                     Tanda tanya (?) adalah optional chaining untuk mencegah error jika tidak ada user. --}}
                                {{ $schedule->coaches->first()?->user?->name ?? 'N/A' }}
                            </td>
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
<section id="paket" class="py-10 sm:py-24 bg-gradient-to-b from-sky-50 to-cyan-50 relative overflow-hidden">
    <!-- Background pattern -->
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-0 left-0 w-full h-full bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxnIGZpbGw9IiMwMDk0YjciIGZpbGwtb3BhY2l0eT0iMC4xIj48Y2lyY2xlIGN4PSIzMCIgY3k9IjMwIiByPSIyIi8+PC9nPjwvZz48L3N2Zz4=')]"></div>
    </div>
    
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center fade-in">
            <h2 class="font-extrabold text-3xl sm:text-4xl tracking-tight text-slate-900">Pilihan Paket Seru</h2>
            <p class="mt-4 max-w-2xl mx-auto text-lg text-slate-600">Investasi terbaik untuk prestasi dan keceriaan anak Anda.</p>
        </div>
        <div class="mt-16 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($packages as $package)
                <div class="fade-in bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg p-8 flex flex-col text-center transform hover:-translate-y-2 transition-all duration-300 ring-1 ring-white/50 hover:shadow-xl relative overflow-hidden">
                    <!-- Background accent -->
                    <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-cyan-400 to-blue-500"></div>
                    <h3 class="text-2xl font-bold text-slate-900 mb-4">{{ $package->name }}</h3>
                    <p class="text-5xl font-extrabold bg-gradient-to-r from-cyan-500 to-blue-600 bg-clip-text text-transparent my-4">Rp {{ number_format($package->price, 0, ',', '.') }}</p>
                    <p class="text-slate-600 mb-8 flex-grow">{{ $package->description }}</p>
                    <a href="{{ route('member.register.store') }}" class="mt-auto w-full inline-block bg-gradient-to-r from-slate-800 to-slate-900 text-white px-6 py-3 rounded-full font-bold hover:from-slate-700 hover:to-slate-800 transition-all shadow-md">
                        Pilih Paket Ini
                    </a>
                </div>
            @empty
                <p class="col-span-3 text-center text-slate-500">Informasi paket akan segera tersedia.</p>
            @endforelse
        </div>
    </div>
</section>

{{-- ======================================================= --}}
{{--     SECTION GALERI DIMODIFIKASI MENJADI CAROUSEL      --}}
{{-- ======================================================= --}}
<section id="galeri" class="py-10 sm:py-24 bg-gradient-to-b from-white to-sky-50 relative overflow-hidden">
    <!-- Background pattern -->
    <div class="absolute inset-0 opacity-5">
        <div class="absolute top-0 right-0 w-1/2 h-full bg-cyan-400/20 rounded-full mix-blend-multiply filter blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-1/2 h-full bg-blue-300/20 rounded-full mix-blend-multiply filter blur-3xl"></div>
    </div>
    
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center fade-in">
            <h2 class="font-extrabold text-3xl sm:text-4xl tracking-tight text-slate-900">Galeri Kegiatan Kami</h2>
            <p class="mt-4 max-w-2xl mx-auto text-lg text-slate-600">Intip keseruan dan momen tak terlupakan di Cikampek Swimming Club.</p>
        </div>

        <div x-data="gallery()" x-init="init()" @mouseenter="stopAutoplay()" @mouseleave="startAutoplay()" class="relative mt-16 max-w-4xl mx-auto fade-in">
            
            {{-- Main Image Display --}}
            <div class="relative overflow-hidden rounded-2xl shadow-2xl h-64 sm:h-80 md:h-96 ring-4 ring-white/50">
                <template x-for="(image, index) in images" :key="index">
                    <div x-show="activeIndex === index"
                         x-transition:enter="transition ease-in-out duration-500"
                         x-transition:enter-start="opacity-0 transform scale-95"
                         x-transition:enter-end="opacity-100 transform scale-100"
                         x-transition:leave="transition ease-in-out duration-300"
                         x-transition:leave-start="opacity-100 transform scale-100"
                         x-transition:leave-end="opacity-0 transform scale-95"
                         class="absolute inset-0 w-full h-full">
                        <img :src="image.src" :alt="image.alt" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                        <h3 class="absolute bottom-4 left-4 text-white text-xl font-bold drop-shadow-lg" x-text="image.alt"></h3>
                    </div>
                </template>
            </div>

            {{-- Navigation Buttons (Prev/Next) --}}
            <button @click="prev()" class="absolute top-1/2 left-2 sm:-left-4 transform -translate-y-1/2 bg-white/90 hover:bg-white text-slate-700 p-3 rounded-full shadow-lg hover:shadow-xl focus:outline-none transition-all duration-300 z-10">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </button>
            <button @click="next()" class="absolute top-1/2 right-2 sm:-right-4 transform -translate-y-1/2 bg-white/90 hover:bg-white text-slate-700 p-3 rounded-full shadow-lg hover:shadow-xl focus:outline-none transition-all duration-300 z-10">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </button>

            {{-- Dot Indicators --}}
            <div class="absolute bottom-4 right-4 flex space-x-2 z-10">
                <template x-for="(image, index) in images" :key="index">
                    <button @click="activeIndex = index" 
                            :class="{'bg-white scale-110': activeIndex === index, 'bg-white/50': activeIndex !== index}"
                            class="h-2.5 w-2.5 rounded-full hover:bg-white transition-all duration-300 focus:outline-none"></button>
                </template>
            </div>
        </div>
    </div>
</section>

<script>
    function gallery() {
        return {
            activeIndex: 0,
            autoplayInterval: null,
            // PENTING: Ganti array ini dengan data dari Controller Anda
            // Anda bisa passing variabel dari controller ke view, lalu JSON encode di sini
            images: [
                { src: '{{ asset("images/bgcsc.jpg") }}', alt: 'Latihan Bersama Timboi' },
                { src: '{{ asset("images/IMG-20251010-WA0056.jpg") }}', alt: 'Tengkorak Cup 2 TA 2025' },
                { src: '{{ asset("images/IMG-20251010-WA0076.jpg") }}', alt: 'Rutinitas Latihan' },
                { src: '{{ asset("images/IMG-20251010-WA0079.jpg") }}', alt: 'Tim CSC 2025' },
            ],
            
            init() {
                this.startAutoplay();
            },

            next() {
                this.activeIndex = (this.activeIndex + 1) % this.images.length;
            },

            prev() {
                // Formula ini memastikan carousel bisa loop ke belakang dari slide pertama
                this.activeIndex = (this.activeIndex - 1 + this.images.length) % this.images.length;
            },
            
            startAutoplay() {
                this.autoplayInterval = setInterval(() => {
                    this.next();
                }, 5000); // Ganti slide setiap 5 detik
            },
            
            stopAutoplay() {
                clearInterval(this.autoplayInterval);
            }
        }
    }
</script>


{{-- Tim Pelatih (Carousel Geser/Swipe) --}}
<section id="pelatih" class="py-12 sm:py-20 lg:py-28 bg-gradient-to-b from-white to-sky-50 relative overflow-hidden">
    <!-- Background pattern -->
    <div class="absolute inset-0 opacity-5">
        <div class="absolute top-1/4 left-10 w-60 h-60 bg-cyan-400 rounded-full mix-blend-multiply filter blur-3xl"></div>
        <div class="absolute bottom-1/4 right-10 w-60 h-60 bg-blue-300 rounded-full mix-blend-multiply filter blur-3xl"></div>
    </div>
    
    <div class="container mx-auto relative z-10">
        {{-- Heading --}}
        <div class="text-center fade-in px-4 sm:px-6 lg:px-8">
            <h2 class="font-extrabold text-2xl sm:text-3xl md:text-4xl tracking-tight text-slate-900">
                Tim Pelatih Profesional Kami
            </h2>
            <p class="mt-3 sm:mt-4 max-w-2xl mx-auto text-base sm:text-lg text-slate-600">
                Dipandu oleh para ahli yang berdedikasi dan berpengalaman dalam membina calon atlet.
            </p>
        </div>

        {{-- Container Carousel yang Bisa di-Swipe --}}
        <div class="relative mt-16 fade-in overflow-x-auto hide-scrollbar">
            <div class="flex gap-6 px-4 sm:px-6 lg:px-8 pb-4">
                
                {{-- Pelatih 1 --}}
                <div class="flex-shrink-0 w-[70vw] sm:w-[40vw] md:w-[28vw] lg:w-[22vw]">
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 text-center p-8 h-full flex flex-col items-center justify-center border border-white/50">
                        <img class="w-32 h-32 mx-auto rounded-full object-cover ring-4 ring-white shadow-md" 
                             src="{{ asset('images/luthfi.jpg') }}" 
                             alt="Luthfi">
                        <h3 class="mt-6 text-lg sm:text-xl font-bold text-slate-900">
                            Moh Lutfi Adistira Wirawan
                        </h3>
                        <p class="mt-1 text-sm sm:text-base font-semibold text-blue-600">
                            Owner
                        </p>
                    </div>
                </div>

                {{-- Pelatih 2 --}}
                <div class="flex-shrink-0 w-[70vw] sm:w-[40vw] md:w-[28vw] lg:w-[22vw]">
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 text-center p-8 h-full flex flex-col items-center justify-center border border-white/50">
                        <img class="w-32 h-32 mx-auto rounded-full object-cover ring-4 ring-white shadow-md" 
                             src="{{ asset('images/alif.jpg') }}" 
                             alt="Alif">
                        <h3 class="mt-6 text-lg sm:text-xl font-bold text-slate-900">
                            Alif Ikrar Prabu
                        </h3>
                        <p class="mt-1 text-sm sm:text-base font-semibold text-blue-600">
                            Coach
                        </p>
                    </div>
                </div>

                {{-- Pelatih 3 --}}
                <div class="flex-shrink-0 w-[70vw] sm:w-[40vw] md:w-[28vw] lg:w-[22vw]">
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 text-center p-8 h-full flex flex-col items-center justify-center border border-white/50">
                        <img class="w-32 h-32 mx-auto rounded-full object-cover ring-4 ring-white shadow-md" 
                             src="{{ asset('images/fauzan.jpg') }}" 
                             alt="Fauzan">
                        <h3 class="mt-6 text-lg sm:text-xl font-bold text-slate-900">
                            Fauzan Noer Afrizal
                        </h3>
                        <p class="mt-1 text-sm sm:text-base font-semibold text-blue-600">
                            Coach
                        </p>
                    </div>
                </div>

                {{-- Pelatih 4 --}}
                <div class="flex-shrink-0 w-[70vw] sm:w-[40vw] md:w-[28vw] lg:w-[22vw]">
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 text-center p-8 h-full flex flex-col items-center justify-center border border-white/50">
                        <img class="w-32 h-32 mx-auto rounded-full object-cover ring-4 ring-white shadow-md" 
                             src="{{ asset('images/doni.jpg') }}" 
                             alt="Doni">
                        <h3 class="mt-6 text-lg sm:text-xl font-bold text-slate-900">
                            Dony Adhi Nugroho Hidayat
                        </h3>
                        <p class="mt-1 text-sm sm:text-base font-semibold text-blue-600">
                            Coach
                        </p>
                    </div>
                </div>

                {{-- Pelatih 5 --}}
                <div class="flex-shrink-0 w-[70vw] sm:w-[40vw] md:w-[28vw] lg:w-[22vw]">
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 text-center p-8 h-full flex flex-col items-center justify-center border border-white/50">
                        <img class="w-32 h-32 mx-auto rounded-full object-cover ring-4 ring-white shadow-md" 
                             src="{{ asset('images/asri.jpg') }}" 
                             alt="Asri">
                        <h3 class="mt-6 text-lg sm:text-xl font-bold text-slate-900">
                            Asri Suci Afiani
                        </h3>
                        <p class="mt-1 text-sm sm:text-base font-semibold text-blue-600">
                            Coach
                        </p>
                    </div>
                </div>

                {{-- Pelatih 6 --}}
                <div class="flex-shrink-0 w-[70vw] sm:w-[40vw] md:w-[28vw] lg:w-[22vw]">
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 text-center p-8 h-full flex flex-col items-center justify-center border border-white/50">
                        <img class="w-32 h-32 mx-auto rounded-full object-cover ring-4 ring-white shadow-md" 
                             src="{{ asset('images/endah.jpg') }}" 
                             alt="Endah">
                        <h3 class="mt-6 text-lg sm:text-xl font-bold text-slate-900">
                            Endah Khairun Nissa
                        </h3>
                        <p class="mt-1 text-sm sm:text-base font-semibold text-blue-600">
                            Coach
                        </p>
                    </div>
                </div>

                {{-- Pelatih 7 --}}
                <div class="flex-shrink-0 w-[70vw] sm:w-[40vw] md:w-[28vw] lg:w-[22vw]">
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 text-center p-8 h-full flex flex-col items-center justify-center border border-white/50">
                        <img class="w-32 h-32 mx-auto rounded-full object-cover ring-4 ring-white shadow-md" 
                             src="{{ asset('images/fabiyan.jpg') }}" 
                             alt="Fabiyan">
                        <h3 class="mt-6 text-lg sm:text-xl font-bold text-slate-900">
                            Fabiyan Fahliyansyah
                        </h3>
                        <p class="mt-1 text-sm sm:text-base font-semibold text-blue-600">
                            Coach
                        </p>
                    </div>
                </div>
                
                {{-- Pelatih 8 --}}
                <div class="flex-shrink-0 w-[70vw] sm:w-[40vw] md:w-[28vw] lg:w-[22vw]">
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 text-center p-8 h-full flex flex-col items-center justify-center border border-white/50">
                        <img class="w-32 h-32 mx-auto rounded-full object-cover ring-4 ring-white shadow-md" 
                             src="{{ asset('images/juan.jpg') }}" 
                             alt="Juan">
                        <h3 class="mt-6 text-lg sm:text-xl font-bold text-slate-900">
                            Juan Djawi Wandhira
                        </h3>
                        <p class="mt-1 text-sm sm:text-base font-semibold text-blue-600">
                            Coach
                        </p>
                    </div>
                </div>
                
                {{-- Pelatih 9 --}}
                <div class="flex-shrink-0 w-[70vw] sm:w-[40vw] md:w-[28vw] lg:w-[22vw]">
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 text-center p-8 h-full flex flex-col items-center justify-center border border-white/50">
                        <img class="w-32 h-32 mx-auto rounded-full object-cover ring-4 ring-white shadow-md" 
                             src="{{ asset('images/iman.jpg') }}" 
                             alt="Iman">
                        <h3 class="mt-6 text-lg sm:text-xl font-bold text-slate-900">
                            Iman Fala Handoko
                        </h3>
                        <p class="mt-1 text-sm sm:text-base font-semibold text-blue-600">
                            Coach
                        </p>
                    </div>
                </div>
                
                {{-- Pelatih 10 --}}
                <div class="flex-shrink-0 w-[70vw] sm:w-[40vw] md:w-[28vw] lg:w-[22vw]">
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 text-center p-8 h-full flex flex-col items-center justify-center border border-white/50">
                        <img class="w-32 h-32 mx-auto rounded-full object-cover ring-4 ring-white shadow-md" 
                             src="{{ asset('images/salsa.jpg') }}" 
                             alt="Salsa">
                        <h3 class="mt-6 text-lg sm:text-xl font-bold text-slate-900">
                            Salsa Ramdiyani Eki Putri
                        </h3>
                        <p class="mt-1 text-sm sm:text-base font-semibold text-blue-600">
                            Coach
                        </p>
                    </div>
                </div>
                
                {{-- Pelatih 11 --}}
                <div class="flex-shrink-0 w-[70vw] sm:w-[40vw] md:w-[28vw] lg:w-[22vw]">
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 text-center p-8 h-full flex flex-col items-center justify-center border border-white/50">
                        <img class="w-32 h-32 mx-auto rounded-full object-cover ring-4 ring-white shadow-md" 
                             src="{{ asset('images/jangkung.jpg') }}" 
                             alt="Hafeed">
                        <h3 class="mt-6 text-lg sm:text-xl font-bold text-slate-900">
                             Mohammad Hafid Siddik
                        </h3>
                        <p class="mt-1 text-sm sm:text-base font-semibold text-blue-600">
                            Coach
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

{{-- SECTION BERITA DIMODIFIKASI --}}
<section id="berita" class="py-10 sm:py-24 bg-gradient-to-b from-sky-50 to-cyan-50 relative overflow-hidden">
    <!-- Background pattern -->
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-0 left-0 w-1/3 h-full bg-cyan-400/20 rounded-full mix-blend-multiply filter blur-3xl"></div>
        <div class="absolute bottom-0 right-0 w-1/3 h-full bg-blue-300/20 rounded-full mix-blend-multiply filter blur-3xl"></div>
    </div>
    
    <div class="container mx-auto relative z-10">
        <div class="text-center fade-in px-4 sm:px-6 lg:px-8">
            <h2 class="font-extrabold text-3xl sm:text-4xl tracking-tight text-slate-900">Cerita dari Kolam Renang</h2>
            <p class="mt-4 max-w-2xl mx-auto text-lg text-slate-600">Lihat keseruan dan prestasi terbaru dari keluarga besar CSC!</p>
        </div>

        <div class="relative mt-16 fade-in overflow-x-auto hide-scrollbar">
            <div class="flex gap-6 px-4 sm:px-6 lg:px-8 pb-4">
                @forelse($posts as $post)
                    <div class="flex-shrink-0 w-[80vw] sm:w-[45vw] md:w-[30vw] lg:w-[25vw]">
                        <div class="bg-white/80 backdrop-blur-sm rounded-2xl overflow-hidden shadow-lg flex flex-col h-full group border border-white/50 hover:shadow-xl transition-all duration-300">
                            <div class="overflow-hidden">
                                <img class="w-full h-56 object-cover group-hover:scale-105 transition-transform duration-300" src="{{ $post->file_path ? Storage::url($post->file_path) : 'https://source.unsplash.com/400x300/?kids,swimming' }}" alt="{{ $post->title }}">
                            </div>
                            <div class="p-6 flex flex-col flex-grow">
                                <span class="inline-block bg-gradient-to-r from-cyan-500 to-blue-600 text-white text-xs font-bold px-3 py-1 rounded-full self-start mb-3">{{ \Carbon\Carbon::parse($post->created_at)->translatedFormat('d F Y') }}</span>
                                <h3 class="font-bold text-xl text-slate-900 mb-3">{{ $post->title }}</h3>
                                <div class="text-slate-600 text-base line-clamp-3 mb-4 flex-grow">{!! strip_tags($post->description) !!}</div>
                                <a href="#" class="mt-auto font-bold text-cyan-600 hover:text-cyan-800 self-start transition-colors">Lihat Selengkapnya &rarr;</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="w-full text-center text-slate-500 px-4">
                        <p>Belum ada cerita baru yang dipublikasikan.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</section>

@endsection