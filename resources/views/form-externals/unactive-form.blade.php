<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $form->title }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite('resources/css/app.css')
</head>
<body class="bg-slate-100">

<div class="min-h-screen flex items-center justify-center px-4 py-10">

    <div class="w-full max-w-3xl bg-white rounded-2xl shadow-xl border border-slate-200 p-8 space-y-8 text-center">

        <div class="space-y-3">
            <img
                src="{{ asset('images/logocsc.png') }}"
                alt="Logo"
                class="h-24 w-auto mx-auto mb-4">

            <h1 class="text-3xl font-extrabold text-slate-800">
                {{ $form->title }}
            </h1>

            <p class="text-slate-600 max-w-xl mx-auto">
                Terima kasih atas antusiasme anda.
            </p>
        </div>

        <div class="p-6 bg-red-50 border border-red-200 rounded-xl space-y-4">

            <div class="flex items-center justify-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 9v2m0 4h.01M12 3c4.97 0 9 4.03 9 9s-4.03 9-9 9-9-4.03-9-9 4.03-9 9-9z"/>
                </svg>

                <span class="text-xl font-bold text-red-600">
                    Pendaftaran Ditutup
                </span>
            </div>

            <p class="text-slate-700">
                Saat ini form pendaftaran sudah
                <span class="font-semibold text-red-600">tidak menerima pengisian</span>.
            </p>

            <p class="text-sm text-slate-500">
                Silakan hubungi admin terkait atau cek kembali halaman ini di lain waktu.
            </p>
        </div>

    </div>

</div>

</body>
</html>
