@extends('layouts.member')

@section('title', 'Dashboard Saya')

@section('content')

@if(!isset($member))
    {{-- Tampilan Non-Member --}}
    <div class="min-h-screen flex items-center justify-center bg-slate-50">
        <div class="max-w-md w-full bg-white rounded-xl shadow-lg p-8 text-center border border-slate-200">
            <h2 class="text-2xl font-bold text-slate-800 mb-2">Data Tidak Ditemukan</h2>
            <p class="text-slate-500 mb-6">Akun Anda belum terhubung dengan data member.</p>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-primary w-full">Keluar</button>
            </form>
        </div>
    </div>
@else
    {{-- Tampilan Member Aktif --}}
    <main class="py-6">
        <div class="mx-auto px-4 sm:px-6 lg:px-8 max-w-full">
            
            {{-- 1. Header Profile Section --}}
            <div class="mb-8 fade-in">
                <div class="bg-gradient-to-r from-cyan-50 to-blue-50 rounded-2xl p-6 border border-cyan-100">
                    <div class="flex flex-col md:flex-row md:items-center justify-between">
                        <div class="flex items-center gap-6">
                            <div class="hidden md:block w-20 h-20 rounded-full bg-white p-1 shadow-md">
                                @if ($member->user->photo_url)
                                    <img src="{{ $member->user->photo_url }}" class="w-full h-full rounded-full object-cover">
                                @else
                                    <div class="w-full h-full rounded-full bg-slate-100 flex items-center justify-center text-slate-400">
                                        <i data-feather="user" class="w-8 h-8"></i>
                                    </div>
                                @endif
                            </div>
                            <div>
                                <h1 class="text-2xl md:text-3xl font-bold text-slate-800 mb-1">
                                    Halo, {{ explode(' ', Auth::user()->name)[0] }}
                                </h1>
                                <div class="flex items-center gap-3 text-slate-600 text-sm">
                                    <span class="flex items-center gap-1">
                                        <i data-feather="mail" class="w-3 h-3"></i> {{ $member->user->email }}
                                    </span>
                                    <span class="status-badge {{ $member->status == 'AKTIF' ? 'status-active' : 'status-inactive' }}">
                                        {{ $member->status }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 md:mt-0 text-right">
                            <p class="text-sm text-slate-500">Paket Latihan</p>
                            <p class="text-lg font-bold text-cyan-600">{{ $member->trainingPackage->name ?? 'Tidak ada' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. Stats Cards --}}
            @include('member.partials.stats-cards')

            {{-- 3. Konten Utama (Action Buttons & Jadwal) --}}
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-6">
                
                {{-- Kolom Kiri: Action Buttons --}}
                <div class="space-y-6">
                    {{-- Tombol Raport --}}
                    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden card-hover p-6 text-center">
                        <div class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i data-feather="bar-chart-2" class="w-8 h-8 text-blue-600"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800 mb-2">Raport Performa</h3>
                        <p class="text-sm text-slate-500 mb-6">Lihat grafik perkembangan waktu, volume, dan intensitas latihan Anda.</p>
                        <button onclick="openRaportModal()" class="w-full btn-primary flex justify-center items-center gap-2">
                            <i data-feather="eye" class="w-4 h-4"></i> Buka Raport Lengkap
                        </button>
                    </div>

                    {{-- Tombol Fisik --}}
                    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden card-hover p-6 text-center">
                        <div class="w-16 h-16 bg-pink-50 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i data-feather="activity" class="w-8 h-8 text-pink-600"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800 mb-2">Tes Fisik</h3>
                        <p class="text-sm text-slate-500 mb-6">Lihat hasil analisis kondisi fisik (VO2 Max, Sprint, Agility).</p>
                        <button onclick="openPhysicalModal()" class="w-full py-2.5 bg-pink-600 hover:bg-pink-700 text-white rounded-xl font-bold transition-colors flex justify-center items-center gap-2">
                            <i data-feather="eye" class="w-4 h-4"></i> Lihat Hasil Tes
                        </button>
                    </div>
                </div>

                {{-- Kolom Tengah & Kanan: Jadwal & Riwayat --}}
                <div class="xl:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
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