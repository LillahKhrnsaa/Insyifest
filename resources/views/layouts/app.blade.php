<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cikampek Swimming Club</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=nunito:400,500,600,700,800,900&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Nunito', sans-serif; }
        .fade-in {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.6s ease-out, transform 0.6s ease-out;
        }
        .fade-in.is-visible {
            opacity: 1;
            transform: translateY(0);
        }
        /* Style untuk menyembunyikan scrollbar horizontal */
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .hide-scrollbar {
            -ms-overflow-style: none;  /* IE and Edge */
            scrollbar-width: none;  /* Firefox */
        }

        /* CSS UNTUK EFEK GELEMBUNG KURSOR */
        .bubble {
            position: absolute;
            background: rgba(135, 206, 250, 0.6); /* Warna biru langit transparan */
            width: 20px;
            height: 20px;
            border-radius: 50%;
            pointer-events: none; /* Agar tidak menghalangi klik */
            transform: translate(-50%, -50%);
            animation: bubble-animate 0.7s ease-out forwards;
            box-shadow: 0 0 5px rgba(135, 206, 250, 0.8);
        }

        @keyframes bubble-animate {
            0% {
                transform: translate(-50%, -50%) scale(0);
                opacity: 1;
            }
            100% {
                transform: translate(-50%, -50%) scale(1.5);
                opacity: 0;
            }
        }
        
        /* Animasi tambahan */
        @keyframes pulse-slow {
            0%, 100% { opacity: 0.5; }
            50% { opacity: 0.8; }
        }
        
        @keyframes pulse-slower {
            0%, 100% { opacity: 0.3; }
            50% { opacity: 0.6; }
        }
        
        .animate-pulse-slow {
            animation: pulse-slow 6s ease-in-out infinite;
        }
        
        .animate-pulse-slower {
            animation: pulse-slower 8s ease-in-out infinite;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased"
    x-data
    x-init="() => {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                }
            });
        }, { threshold: 0.1 });
        document.querySelectorAll('.fade-in').forEach(el => observer.observe(el));
    }">
    
<header x-data="{ open: false }" class="bg-white/80 backdrop-blur-md shadow-sm sticky top-0 z-50">
    <nav class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">
            <a href="{{ route('landing') }}" class="flex-shrink-0">
                <img src="{{ asset('images/logocsc.png') }}" alt="Logo Cikampek Swimming Club" class="h-14 w-auto">
            </a>
            <div class="hidden md:flex md:items-center md:space-x-8">
                <!-- Link Home ditambahkan di sini -->
                <a href="{{ route('landing') }}" class="text-slate-600 hover:text-blue-500 font-semibold transition-colors flex items-center gap-1">Home</a>
                <a href="{{ route('landing') }}#keunggulan" class="text-slate-600 hover:text-blue-500 font-semibold transition-colors">Keunggulan</a>
                <a href="{{ route('landing') }}#jadwal" class="text-slate-600 hover:text-blue-500 font-semibold transition-colors">Jadwal</a>
                <a href="{{ route('landing') }}#paket" class="text-slate-600 hover:text-blue-500 font-semibold transition-colors">Paket</a>
                <a href="{{ route('landing') }}#galeri" class="text-slate-600 hover:text-blue-500 font-semibold transition-colors">Galeri</a>
                <a href="{{ route('landing') }}#berita" class="text-slate-600 hover:text-blue-500 font-semibold transition-colors">Berita</a>
            </div>
            <div class="flex items-center space-x-4">
                <a href="{{ route('login') }}" class="hidden md:inline-block bg-gradient-to-r from-cyan-500 to-blue-600 text-white px-6 py-2.5 rounded-full font-bold hover:from-cyan-400 hover:to-blue-500 transition-all shadow-md">Login</a>
                <div class="md:hidden">
                    <button @click="open = !open" class="p-2 rounded-md text-slate-700 focus:outline-none hover:bg-slate-100 transition-colors">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Mobile Menu -->
        <div x-show="open" @click.away="open = false" x-transition class="md:hidden bg-white rounded-b-lg py-2 shadow-lg" style="display: none;">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <!-- Link Home untuk mobile -->
                <a href="{{ route('landing') }}" @click="open = false" class="flex items-center gap-2 px-3 py-2 rounded-md text-base font-medium text-slate-700 hover:bg-slate-100 transition-colors">Home</a>
                <a href="{{ route('landing') }}#keunggulan" @click="open = false" class="block px-3 py-2 rounded-md text-base font-medium text-slate-700 hover:bg-slate-100 transition-colors">Keunggulan</a>
                <a href="{{ route('landing') }}#jadwal" @click="open = false" class="block px-3 py-2 rounded-md text-base font-medium text-slate-700 hover:bg-slate-100 transition-colors">Jadwal</a>
                <a href="{{ route('landing') }}#paket" @click="open = false" class="block px-3 py-2 rounded-md text-base font-medium text-slate-700 hover:bg-slate-100 transition-colors">Paket</a>
                <a href="{{ route('landing') }}#galeri" @click="open = false" class="block px-3 py-2 rounded-md text-base font-medium text-slate-700 hover:bg-slate-100 transition-colors">Galeri</a>
                <a href="{{ route('landing') }}#berita" @click="open = false" class="block px-3 py-2 rounded-md text-base font-medium text-slate-700 hover:bg-slate-100 transition-colors">Berita</a>
                <div class="pt-2 border-t border-slate-200">
                    <a href="/admin" class="block text-center bg-gradient-to-r from-cyan-500 to-blue-600 text-white px-5 py-2.5 rounded-lg font-semibold hover:from-cyan-400 hover:to-blue-500 transition-all mt-2 mx-2">Login</a>
                </div>
            </div>
        </div>
    </nav>
</header>

    <main>@yield('content')</main>

    <footer id="footer" class="bg-gradient-to-br from-blue-600 to-cyan-700 text-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12">
                <div class="md:col-span-2 lg:col-span-1"><img src="{{ asset('images/logocsc.png') }}" alt="Logo CSC" class="h-16 w-auto mb-4"><p class="text-blue-100">Membentuk perenang berprestasi dengan dedikasi dan program latihan terbaik di Cikampek.</p></div>
                <div><h3 class="font-extrabold tracking-wider text-white mb-4">NAVIGASI</h3><ul class="space-y-2"><li><a href="#keunggulan" class="text-blue-100 hover:text-white transition-colors">Keunggulan</a></li><li><a href="#jadwal" class="text-blue-100 hover:text-white transition-colors">Jadwal Latihan</a></li><li><a href="#paket" class="text-blue-100 hover:text-white transition-colors">Paket & Biaya</a></li><li><a href="#galeri" class="text-blue-100 hover:text-white transition-colors">Galeri</a></li></ul></div>
                <div><h3 class="font-extrabold tracking-wider text-white mb-4">HUBUNGI KAMI</h3><ul class="space-y-2 text-blue-100"><li>(+62) 858-9496-1449</li><li>C4, Jl. Mashudi No.30, Pucung, Kec. Kota Baru, Karawang, Jawa Barat 41374</li></ul></div>
                <div><h3 class="font-extrabold tracking-wider text-white mb-4">IKUTI KAMI</h3><div class="flex mt-4 space-x-4"><a href="#" class="text-blue-200 hover:text-white transition-colors"><svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" /></svg></a><a href="#" class="text-blue-200 hover:text-white transition-colors"><svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.024.06 1.378.06 3.808s-.012 2.784-.06 3.808c-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.024.048-1.378.06-3.808.06s-2.784-.013-3.808-.06c-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.048-1.024-.06-1.378-.06-3.808s.012-2.784.06-3.808c.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 016.08 2.525c.636-.247 1.363-.416 2.427.465C9.53 2.013 9.884 2 12.315 2zm-1.003 3.703a.75.75 0 01.75-.75h1.875a.75.75 0 010 1.5h-1.875a.75.75 0 01-.75-.75zM12 6.875a5.125 5.125 0 100 10.25 5.125 5.125 0 000-10.25zm0 1.5a3.625 3.625 0 110 7.25 3.625 3.625 0 010-7.25z" clip-rule="evenodd" /></svg></a></div></div>
            </div>
            <div class="mt-12 border-t border-blue-500/30 pt-8 text-center text-blue-200"><p>&copy; {{ date('Y') }} Cikampek Swimming Club. All Rights Reserved.</p></div>
        </div>
    </footer>

    <script>
    // SCRIPT UNTUK EFEK GELEMBUNG KURSOR
    document.addEventListener('mousemove', function(e) {
        // Buat elemen div baru untuk gelembung
        let bubble = document.createElement('div');
        bubble.className = 'bubble';
        document.body.appendChild(bubble);

        // Atur posisi gelembung sesuai posisi kursor
        // pageX dan pageY agar posisi tetap benar saat halaman di-scroll
        bubble.style.left = e.pageX + 'px';
        bubble.style.top = e.pageY + 'px';

        // Hapus elemen gelembung dari halaman setelah animasinya selesai
        // Durasi di sini (700ms) harus sama dengan durasi animasi di CSS
        setTimeout(() => {
            bubble.remove();
        }, 700);
    });
</script>
</body>
</html>