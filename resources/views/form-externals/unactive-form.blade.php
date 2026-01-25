<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $form->title }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.tailwindcss.com">
</head>
<body class="bg-slate-100">

<div class="min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-xl shadow max-w-md text-center">

        <h1 class="text-2xl font-bold mb-3">
            {{ $form->title }}
        </h1>

        <p class="text-slate-600 mb-6">
            Form pendaftaran ini sedang
            <span class="font-semibold text-red-500">ditutup</span>.
        </p>

        <p class="text-sm text-slate-500">
            Silakan hubungi admin atau cek kembali di lain waktu.
        </p>

    </div>
</div>

</body>
</html>
