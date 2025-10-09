<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Form')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-purple-600 via-indigo-500 to-blue-500 text-gray-800 flex flex-col">

    {{-- Header Global --}}
    <header class="w-full py-6 flex justify-center items-center text-white space-x-4">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-10 h-10 rounded-full shadow-md">
        <div class="text-center">
            <h1 class="text-xl font-semibold">Nama Instansi Default</h1>
            <p class="text-sm text-purple-100">Formulir Resmi</p>
        </div>
    </header>

    {{-- Konten --}}
    <main class="flex-grow flex flex-col items-center px-4 pb-10">
        @yield('content')
    </main>

</body>
</html>
    