@extends('layouts.member')

@section('title', 'Dashboard Member')

@section('content')

@if(!isset($member))

<div class="min-h-screen flex items-center justify-center bg-slate-50">
    <div class="w-full max-w-md bg-white border border-slate-100 rounded-[2.5rem] shadow-2xl p-10 text-center">
        <div class="w-20 h-20 bg-blue-50 rounded-3xl flex items-center justify-center mx-auto mb-6">
            <i data-feather="user-x" class="w-10 h-10 text-blue-600"></i>
        </div>
        <h2 class="text-2xl font-black text-slate-800 uppercase tracking-tight mb-2">Data Tidak Ditemukan</h2>
        <p class="text-sm font-medium text-slate-400 mb-8 leading-relaxed">
            Maaf, akun Anda belum terhubung dengan data member aktif di sistem kami.
        </p>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="w-full py-4 bg-blue-600 text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl shadow-blue-100 hover:bg-blue-700 transition-all">Keluar Sekarang</button>
        </form>
    </div>
</div>

@else

<main class="min-h-screen relative overflow-hidden" style="background: linear-gradient(135deg, #e0e7ff 0%, #f3e8ff 100%);">
    {{-- Floating Orbs --}}
    <div class="absolute inset-0 pointer-events-none overflow-hidden z-0">
        <div class="absolute top-[-10%] left-[-10%] w-[600px] h-[600px] rounded-full opacity-60" style="background: radial-gradient(circle, #bfdbfe, transparent); filter: blur(80px);"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[500px] h-[500px] rounded-full opacity-60" style="background: radial-gradient(circle, #e9d5ff, transparent); filter: blur(80px);"></div>
        <div class="absolute top-[40%] left-[60%] w-[400px] h-[400px] rounded-full opacity-50" style="background: radial-gradient(circle, #fbcfe8, transparent); filter: blur(80px);"></div>
    </div>

    <div class="relative z-10">
        @include('member.partials.navbar')

        <div class="px-6 lg:px-10 py-8 space-y-8 max-w-7xl mx-auto">

            {{-- Member Profile Header --}}
            <section class="rounded-[2.5rem] shadow-xl p-8 sm:p-10 relative overflow-hidden"
                style="background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%);">
                {{-- Pattern overlay --}}
                <div class="absolute inset-0 opacity-10"
                    style="background: repeating-linear-gradient(45deg, transparent, transparent 25px, rgba(255,255,255,0.1) 25px, rgba(255,255,255,0.1) 50px);"></div>
                {{-- Glow --}}
                <div class="absolute -right-20 -top-20 w-64 h-64 rounded-full opacity-20"
                    style="background: radial-gradient(circle, #60a5fa, transparent);"></div>

                <div class="relative z-10 flex flex-col xl:flex-row xl:items-center xl:justify-between gap-8">

                    <div class="flex flex-col sm:flex-row items-center sm:items-start xl:items-center gap-6 text-center sm:text-left">
                        <div class="w-24 h-24 sm:w-20 sm:h-20 rounded-[1.5rem] overflow-hidden bg-white/10 border-[3px] border-white/30 shadow-2xl flex-shrink-0 group relative backdrop-blur-md">
                            @if ($member->user->photo_url)
                                <img src="{{ $member->user->photo_url }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-white/70">
                                    <i data-feather="user" class="w-10 h-10"></i>
                                </div>
                            @endif
                        </div>

                        <div>
                            <div class="flex flex-col sm:flex-row items-center gap-3">
                                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-black text-white uppercase tracking-tight drop-shadow-md">
                                    {{ Auth::user()->name }}
                                </h1>
                                <span class="px-3 py-1 bg-white/20 text-white text-[10px] font-black rounded-lg tracking-widest uppercase border border-white/30 backdrop-blur-sm shadow-sm">PRO ATLET</span>
                            </div>
                            <p class="mt-2 text-blue-100 font-medium text-sm sm:text-base">
                                Selamat berlatih, tetap fokus pada tujuanmu hari ini!
                            </p>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4 items-stretch sm:items-center justify-center">

                        <div class="px-6 py-4 rounded-3xl border border-white/20 bg-black/10 backdrop-blur-sm flex flex-col justify-center text-center sm:text-left shadow-inner">
                            <span class="text-[10px] font-bold text-blue-200 uppercase tracking-[0.2em] mb-1">Paket Latihan</span>
                            <span class="font-black text-white uppercase tracking-tight text-sm drop-shadow-sm">
                                {{ $member->trainingPackage->name ?? 'N/A' }}
                            </span>
                        </div>

                        <div class="px-6 py-4 rounded-3xl border border-white/20 bg-black/10 backdrop-blur-sm flex flex-col justify-center text-center sm:text-left shadow-inner">
                            <p class="text-[10px] font-bold text-blue-200 uppercase tracking-[0.2em] mb-1">Status Keanggotaan</p>
                            <div class="flex items-center justify-center sm:justify-start gap-2">
                                <span class="w-2.5 h-2.5 rounded-full {{ $member->status === 'AKTIF' ? 'bg-emerald-400 shadow-[0_0_8px_rgba(52,211,153,0.8)]' : 'bg-rose-400 shadow-[0_0_8px_rgba(251,113,133,0.8)]' }}"></span>
                                <span class="text-sm font-black text-white uppercase tracking-widest drop-shadow-sm">{{ $member->status }}</span>
                            </div>
                        </div>

                    </div>
                </div>
            </section>

        {{-- Statistics --}}
        @include('member.partials.stats-cards')

        <div class="grid grid-cols-1 xl:grid-cols-4 gap-8">

            {{-- Quick Actions --}}
            <div class="xl:col-span-1 space-y-6">

                {{-- Raport Performa Card --}}
                <div class="rounded-[2rem] p-8 shadow-lg hover:shadow-2xl hover:shadow-blue-500/30 transition-all duration-500 group relative overflow-hidden"
                     style="background: linear-gradient(135deg, #3b82f6 0%, #4f46e5 100%);">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full blur-2xl -mr-10 -mt-10"></div>
                    
                    <div class="flex flex-col items-center text-center mb-8 relative z-10">
                        <div class="w-16 h-16 rounded-2xl bg-white/20 border border-white/30 flex items-center justify-center mb-4 group-hover:scale-110 transition-all duration-500 shadow-inner backdrop-blur-sm">
                            <i data-feather="bar-chart-2" class="w-8 h-8 text-white"></i>
                        </div>
                        <h3 class="font-black text-white uppercase tracking-tight text-lg drop-shadow-sm">Raport Performa</h3>
                        <p class="text-xs font-medium text-blue-100 mt-2 leading-relaxed">
                            Analisis grafik perkembangan waktu & volume latihan bulanan Anda.
                        </p>
                    </div>

                    <button onclick="openRaportModal()"
                        class="relative z-10 w-full py-4 bg-white/20 hover:bg-white/30 text-white border border-white/30 rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-lg backdrop-blur-sm transition-all flex items-center justify-center gap-3">
                        <i data-feather="eye" class="w-4 h-4"></i>
                        Lihat Raport
                    </button>
                </div>

                {{-- Hasil Tes Fisik Card --}}
                <div class="rounded-[2rem] p-8 shadow-lg hover:shadow-2xl hover:shadow-purple-500/30 transition-all duration-500 group relative overflow-hidden"
                     style="background: linear-gradient(135deg, #8b5cf6 0%, #c026d3 100%);">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full blur-2xl -mr-10 -mt-10"></div>

                    <div class="flex flex-col items-center text-center mb-8 relative z-10">
                        <div class="w-16 h-16 rounded-2xl bg-white/20 border border-white/30 flex items-center justify-center mb-4 group-hover:scale-110 transition-all duration-500 shadow-inner backdrop-blur-sm">
                            <i data-feather="zap" class="w-8 h-8 text-white"></i>
                        </div>
                        <h3 class="font-black text-white uppercase tracking-tight text-lg drop-shadow-sm">Hasil Tes Fisik</h3>
                        <p class="text-xs font-medium text-purple-100 mt-2 leading-relaxed">
                            Monitor hasil VO2 Max, sprint, dan agility untuk mengukur kebugaran.
                        </p>
                    </div>

                    <button onclick="openPhysicalModal()"
                        class="relative z-10 w-full py-4 bg-white/20 hover:bg-white/30 text-white border border-white/30 rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-lg backdrop-blur-sm transition-all flex items-center justify-center gap-3">
                        <i data-feather="eye" class="w-4 h-4"></i>
                        Lihat Analisis
                    </button>
                </div>

            </div>

            {{-- Main Content --}}
            <div class="xl:col-span-3 grid grid-cols-1 md:grid-cols-2 gap-8">
                @include('member.partials.schedule-list')
                @include('member.partials.attendance-history')
            </div>

        </div>
    </div>
</main>

@include('member.partials.modals.raport')
@include('member.partials.modals.physical')

@endif
@endsection

@push('scripts')
    @include('member.scripts.dashboard-js')
@endpush
