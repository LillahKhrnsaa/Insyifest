@extends('layouts.member')

@section('title', 'Dashboard Member')

@section('content')

@if(!isset($member))

<div class="min-h-screen flex items-center justify-center bg-slate-50">
    <div class="w-full max-w-md bg-white border border-slate-200 rounded-xl shadow-sm p-8 text-center">
        <h2 class="text-xl font-semibold text-slate-800 mb-2">Data Tidak Ditemukan</h2>
        <p class="text-sm text-slate-500 mb-6">
            Akun Anda belum terhubung dengan data member.
        </p>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="w-full btn-primary">Keluar</button>
        </form>
    </div>
</div>

@else

<main class="min-h-screen bg-slate-50 py-6">

    <div class="w-full px-4 sm:px-6 lg:px-8 space-y-6">

        <div class="bg-white border border-slate-200 rounded-xl p-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">

                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-full overflow-hidden bg-slate-100 flex-shrink-0">
                        @if ($member->user->photo_url)
                            <img src="{{ $member->user->photo_url }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-slate-400">
                                <i data-feather="user" class="w-6 h-6"></i>
                            </div>
                        @endif
                    </div>

                    <div>
                        <h1 class="text-xl font-bold text-slate-800">
                            {{ Auth::user()->name }}
                        </h1>
                        <p class="text-sm text-slate-500">
                            Member Dashboard
                        </p>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-4 sm:items-center">

                    <div class="px-4 py-2 rounded-lg border bg-slate-50 text-sm">
                        <span class="text-slate-500">Paket Latihan</span><br>
                        <span class="font-semibold text-slate-800">
                            {{ $member->trainingPackage->name ?? 'Tidak ada paket' }}
                        </span>
                    </div>

                    <div class="flex items-center gap-3 px-4 py-2 rounded-lg border bg-slate-50">
                        <span class="text-sm text-slate-500">Status</span>

                        <span id="member-status"
                            class="px-3 py-1 rounded-full text-xs font-semibold
                            {{ $member->status === 'AKTIF'
                                ? 'bg-emerald-100 text-emerald-700'
                                : 'bg-rose-100 text-rose-700' }}">
                            {{ $member->status }}
                        </span>

                        <div class="flex items-center gap-4 px-4 py-2 rounded-lg border bg-slate-50">
                            <div>
                                <p class="text-xs text-slate-500">Status Akun</p>
                                <p class="text-sm font-semibold text-slate-800">
                                    {{ $member->status === 'AKTIF' ? 'Aktif' : 'Nonaktif' }}
                                </p>
                            </div>

                            <button
                                id="status-toggle"
                                onclick="toggleMemberStatus()"
                                data-status="{{ $member->status }}"
                                class="relative inline-flex h-6 w-11 items-center rounded-full transition
                                    {{ $member->status === 'AKTIF' ? 'bg-emerald-500' : 'bg-slate-300' }}"
                            >
                                <span
                                    id="status-knob"
                                    class="inline-block h-5 w-5 transform rounded-full bg-white transition
                                        {{ $member->status === 'AKTIF' ? 'translate-x-5' : 'translate-x-1' }}">
                                </span>
                            </button>
                        </div>

                    </div>

                </div>
            </div>
        </div>

        @include('member.partials.stats-cards')

        <div class="grid grid-cols-1 xl:grid-cols-4 gap-6">

            <div class="xl:col-span-1 space-y-4">

                <div class="bg-white border border-slate-200 rounded-xl p-5 hover:shadow-sm transition">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                            <i data-feather="bar-chart-2" class="w-5 h-5 text-blue-600"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-slate-800">Raport Performa</h3>
                            <p class="text-xs text-slate-500">
                                Grafik waktu & volume latihan
                            </p>
                        </div>
                    </div>

                    <button onclick="openRaportModal()"
                        class="w-full btn-primary text-sm flex items-center justify-center gap-2">
                        <i data-feather="eye" class="w-4 h-4"></i>
                        Lihat Raport
                    </button>
                </div>

                <div class="bg-white border border-slate-200 rounded-xl p-5 hover:shadow-sm transition">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-10 h-10 rounded-lg bg-pink-100 flex items-center justify-center">
                            <i data-feather="activity" class="w-5 h-5 text-pink-600"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-slate-800">Tes Fisik</h3>
                            <p class="text-xs text-slate-500">
                                VO2 Max, sprint, agility
                            </p>
                        </div>
                    </div>

                    <button onclick="openPhysicalModal()"
                        class="w-full py-2.5 bg-pink-600 hover:bg-pink-700 text-white rounded-lg font-semibold text-sm flex items-center justify-center gap-2 transition">
                        <i data-feather="eye" class="w-4 h-4"></i>
                        Lihat Hasil
                    </button>
                </div>

            </div>

            <div class="xl:col-span-3 grid grid-cols-1 md:grid-cols-2 gap-6">
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
