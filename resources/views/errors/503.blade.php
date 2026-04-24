<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance - Cikampek Swimming Club</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=nunito:400,500,600,700,800,900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Nunito', sans-serif; }
        .fade-in { animation: fadeIn 1s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased h-screen flex items-center justify-center p-6">
    <div class="max-w-2xl w-full text-center fade-in">
        <div class="mb-10 relative inline-block">
            <div class="absolute inset-0 bg-blue-500 blur-3xl opacity-20 rounded-full scale-150 animate-pulse"></div>
            <div class="relative w-32 h-32 bg-white rounded-[2.5rem] shadow-2xl flex items-center justify-center p-6 border border-slate-100 mx-auto">
                <img src="{{ asset('images/logocsc.png') }}" alt="CSC Logo" class="w-full h-auto object-contain">
            </div>
        </div>

        <h1 class="text-4xl md:text-5xl font-black text-slate-800 uppercase tracking-tighter leading-tight mb-4">
            Kami sedang <span class="text-blue-600">Melakukan Perawatan</span>
        </h1>
        
        <p class="text-lg text-slate-500 font-medium mb-12 max-w-lg mx-auto leading-relaxed">
            Mohon maaf atas ketidaknyamanannya. Saat ini kami sedang memperbarui sistem untuk memberikan pengalaman yang lebih baik bagi seluruh atlet dan pelatih.
        </p>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
                <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center mx-auto mb-4">
                    <i data-feather="tool" class="w-5 h-5 text-blue-600"></i>
                </div>
                <h3 class="font-black text-slate-800 text-xs uppercase tracking-widest">Update Sistem</h3>
            </div>
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
                <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center mx-auto mb-4">
                    <i data-feather="database" class="w-5 h-5 text-indigo-600"></i>
                </div>
                <h3 class="font-black text-slate-800 text-xs uppercase tracking-widest">Optimasi Data</h3>
            </div>
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
                <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center mx-auto mb-4">
                    <i data-feather="shield" class="w-5 h-5 text-emerald-600"></i>
                </div>
                <h3 class="font-black text-slate-800 text-xs uppercase tracking-widest">Keamanan</h3>
            </div>
        </div>

        <div class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em]">
            Cikampek Swimming Club Management © {{ date('Y') }}
        </div>
    </div>

    <script src="https://unpkg.com/feather-icons"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof feather !== 'undefined') feather.replace();
        });
    </script>
</body>
</html>
