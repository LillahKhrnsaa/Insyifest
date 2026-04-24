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

<main class="min-h-screen bg-slate-50">
    @include('member.partials.navbar')

    <div class="px-6 lg:px-10 py-8 space-y-8">

        {{-- Member Profile Header --}}
        <section class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm p-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-8">

                <div class="flex items-center gap-6">
                    <div class="w-20 h-20 rounded-[1.5rem] overflow-hidden bg-slate-50 border-4 border-white shadow-xl flex-shrink-0 group relative">
                        @if ($member->user->photo_url)
                            <img src="{{ $member->user->photo_url }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-slate-300">
                                <i data-feather="user" class="w-10 h-10"></i>
                            </div>
                        @endif
                        <div class="absolute inset-0 bg-blue-600/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    </div>

                    <div>
                        <div class="flex items-center gap-3">
                            <h1 class="text-2xl lg:text-3xl font-black text-slate-800 uppercase tracking-tighter">
                                {{ Auth::user()->name }}
                            </h1>
                            <span class="px-3 py-1 bg-blue-50 text-blue-600 text-[10px] font-black rounded-lg tracking-widest uppercase border border-blue-100">PRO ATLET</span>
                        </div>
                        <p class="mt-1 text-slate-400 font-medium italic">
                            Selamat berlatih, tetap fokus pada tujuanmu hari ini!
                        </p>
                    </div>
                </div>

                <div class="flex flex-col md:flex-row gap-4 items-stretch md:items-center">

                    <div class="px-6 py-4 rounded-3xl border border-slate-50 bg-slate-50/50 flex flex-col justify-center">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Paket Latihan</span>
                        <span class="font-black text-slate-700 uppercase tracking-tight text-sm">
                            {{ $member->trainingPackage->name ?? 'N/A' }}
                        </span>
                    </div>

                    <div class="px-6 py-4 rounded-3xl border border-slate-50 bg-slate-50/50 flex items-center gap-6">
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Status Keanggotaan</p>
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full {{ $member->status === 'AKTIF' ? 'bg-emerald-500 shadow-sm shadow-emerald-200' : 'bg-rose-500' }}"></span>
                                <span class="text-sm font-black text-slate-700 uppercase tracking-widest">{{ $member->status }}</span>
                            </div>
                        </div>

                        <div class="h-10 w-[1px] bg-slate-200"></div>

                        <div class="flex items-center gap-3">
                            <button
                                id="status-toggle"
                                onclick="toggleMemberStatus()"
                                data-status="{{ $member->status }}"
                                class="relative inline-flex h-7 w-12 items-center rounded-full transition-all duration-300
                                    {{ $member->status === 'AKTIF' ? 'bg-blue-600 shadow-lg shadow-blue-200' : 'bg-slate-300' }}"
                            >
                                <span
                                    id="status-knob"
                                    class="inline-block h-5 w-5 transform rounded-full bg-white transition-all duration-300 shadow-md
                                        {{ $member->status === 'AKTIF' ? 'translate-x-6' : 'translate-x-1' }}">
                                </span>
                            </button>
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-tight">Toggle<br>Status</span>
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

                <div class="bg-white border border-slate-100 rounded-[2rem] p-8 shadow-sm hover:shadow-xl hover:shadow-blue-100/30 transition-all duration-500 group">
                    <div class="flex flex-col items-center text-center mb-8">
                        <div class="w-16 h-16 rounded-2xl bg-blue-50 flex items-center justify-center mb-4 group-hover:scale-110 group-hover:bg-blue-600 transition-all duration-500 shadow-sm">
                            <i data-feather="bar-chart-2" class="w-8 h-8 text-blue-600 group-hover:text-white transition-colors"></i>
                        </div>
                        <h3 class="font-black text-slate-800 uppercase tracking-tight text-lg">Raport Performa</h3>
                        <p class="text-xs font-medium text-slate-400 mt-2 leading-relaxed">
                            Analisis grafik perkembangan waktu & volume latihan bulanan Anda.
                        </p>
                    </div>

                    <button onclick="openRaportModal()"
                        class="w-full py-4 bg-blue-600 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-blue-100 hover:bg-blue-700 hover:-translate-y-1 transition-all flex items-center justify-center gap-3">
                        <i data-feather="eye" class="w-4 h-4"></i>
                        Lihat Raport
                    </button>
                </div>

                <div class="bg-white border border-slate-100 rounded-[2rem] p-8 shadow-sm hover:shadow-xl hover:shadow-indigo-100/30 transition-all duration-500 group">
                    <div class="flex flex-col items-center text-center mb-8">
                        <div class="w-16 h-16 rounded-2xl bg-indigo-50 flex items-center justify-center mb-4 group-hover:scale-110 group-hover:bg-indigo-600 transition-all duration-500 shadow-sm">
                            <i data-feather="zap" class="w-8 h-8 text-indigo-600 group-hover:text-white transition-colors"></i>
                        </div>
                        <h3 class="font-black text-slate-800 uppercase tracking-tight text-lg">Hasil Tes Fisik</h3>
                        <p class="text-xs font-medium text-slate-400 mt-2 leading-relaxed">
                            Monitor hasil VO2 Max, sprint, dan agility untuk mengukur kebugaran.
                        </p>
                    </div>

                    <button onclick="openPhysicalModal()"
                        class="w-full py-4 bg-indigo-600 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-indigo-100 hover:bg-indigo-700 hover:-translate-y-1 transition-all flex items-center justify-center gap-3">
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
