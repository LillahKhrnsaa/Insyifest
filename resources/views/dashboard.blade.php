<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100">

    <!-- Navigasi Atas -->
    <nav class="bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo atau Nama Aplikasi -->
                <div class="flex-shrink-0">
                    <a href="#" class="text-xl font-bold text-indigo-600">Swimming Club</a>
                </div>

                <!-- Nama User dan Tombol Logout -->
                <div class="flex items-center">
                    @auth
                        <span class="text-gray-700 mr-4">
                            Halo, <span class="font-medium">{{ Auth::user()->name }}</span>
                        </span>
                        
                        <!-- Tombol Logout HARUS menggunakan form untuk keamanan (CSRF) -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="px-3 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700">
                                Logout
                            </button>
                        </form>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Konten Utama Dashboard -->
    <main>
        <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <div class="px-4 py-6 sm:px-0">
                <div class="bg-white p-8 rounded-lg shadow">
                    <h1 class="text-2xl font-bold text-gray-900">Selamat Datang di Dashboard!</h1>
                    <p class="mt-2 text-gray-600">
                        Anda telah berhasil login. Dari sini Anda bisa mengelola profil dan aktivitas Anda.
                    </p>
                </div>
            </div>
        </div>
    </main>

</body>
</html>