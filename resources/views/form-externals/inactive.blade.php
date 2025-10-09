@extends('layouts.app')

@section('content')
<div class="min-h-screen flex flex-col items-center justify-center bg-gradient-to-br from-purple-600 via-purple-700 to-indigo-800 text-white px-4">
    <div class="bg-white/10 backdrop-blur-md rounded-xl shadow-lg p-8 max-w-md text-center border border-purple-400/30">
        <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-16 w-16 text-yellow-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M12 4.5c-4.694 0-8.5 3.806-8.5 8.5s3.806 8.5 8.5 8.5 8.5-3.806 8.5-8.5S16.694 4.5 12 4.5z" />
        </svg>
        <h1 class="text-2xl font-bold mb-2">Form Tidak Aktif</h1>
        <p class="text-purple-200">Form <span class="font-semibold text-white">"{{ $form->title }}"</span> saat ini belum dapat diakses.  
        Silakan coba lagi nanti.</p>
        <a href="/" class="mt-6 inline-block bg-white text-purple-700 font-semibold py-2 px-4 rounded-lg hover:bg-purple-100 transition">
            Kembali ke Beranda
        </a>
    </div>
</div>
@endsection
