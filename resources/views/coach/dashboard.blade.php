@extends('layouts.coach')

@section('title', 'Dashboard Pelatih')

@section('content')
<div x-data="{ 
    showModal: {{ $errors->any() ? 'true' : 'false' }}, 
    showAllMembers: false,
    showAllSchedules: false,
    showAllHistory: false,
    showDetailModal: false,
    filterMonth: '{{ date('Y-m') }}',
    detailMembers: [], 
    detailTitle: '',
    selectedScheduleId: {{ old('schedule_id') ?? 'null' }}, 
    selectedSchedulePlace: '{{ old('place') ?? '' }}',
    searchTerm: '',
    selectedSchedule: '', 
    
    schedules: {{ $coach->trainingSchedules->map(fn($s) => ['id' => $s->id, 'time' => $s->time, 'place' => $s->place, 'label' => ucfirst($s->day).' ('.$s->time.')']) }},

    openDetail(members, date) {
        this.detailMembers = members;
        this.detailTitle = 'Detail Kehadiran - ' + date;
        this.showDetailModal = true;
    },

    autoFill() {
        let found = this.schedules.find(s => s.id == this.selectedSchedule);
        if (found) {
            this.$refs.timeInput.value = found.time;
            this.$refs.placeInput.value = found.place;
            this.selectedScheduleId = found.id;
            this.selectedSchedulePlace = found.place;
        }
    },

    toggleModal(id = null, place = '') {
        this.selectedScheduleId = id;
        this.selectedSchedule = id ? id : '';
        this.selectedSchedulePlace = place;
        this.showModal = true;
        
        this.$nextTick(() => {
            if(!id) {
                if(this.$refs.timeInput) this.$refs.timeInput.value = '';
                if(this.$refs.placeInput) this.$refs.placeInput.value = '';
            } else {
                this.autoFill();
            }
        });
    }
}" class="min-h-screen">

    <main class="py-6">
        <div class="mx-auto px-4 sm:px-6 lg:px-8 max-w-full">
            
            {{-- 1. Header & Welcome Message --}}
            <div class="mb-8 fade-in">
                <div class="bg-gradient-to-r from-cyan-50 to-blue-50 rounded-2xl p-6 border border-cyan-100">
                    <div class="flex flex-col md:flex-row md:items-center justify-between">
                        <div>
                            <h1 class="text-2xl md:text-3xl font-bold text-slate-800 mb-2">
                                Selamat Datang, Coach {{ explode(' ', Auth::user()->name)[0] }}
                            </h1>
                            <p class="text-slate-600">Pantau performa atlet CSC dan kelola jadwal latihan dengan mudah.</p>
                        </div>
                        <div class="mt-4 md:mt-0">
                            <p class="text-sm text-slate-500">Hari ini: {{ now()->isoFormat('dddd, D MMMM YYYY') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. Alerts System --}}
            @include('coach.partials.alerts')

            {{-- 3. Statistik Cards --}}
            @include('coach.partials.stats')

            {{-- 4. Layout Utama (Members, Jadwal, Riwayat) --}}
            <div class="fade-in" style="animation-delay: 0.2s;">
                
                {{-- Daftar Atlet --}}
                @include('coach.partials.members-card')

                {{-- Grid Jadwal & Riwayat --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                    @include('coach.partials.schedules-card')
                    @include('coach.partials.history-card')
                </div>
            </div>
        </div>
    </main>

    {{-- KUMPULAN MODAL --}}
    @include('coach.partials.modals.attendance-form')
    @include('coach.partials.modals.attendance-detail')
    @include('coach.partials.modals.attendance-edit')
    
    {{-- Modal "Lihat Semua" --}}
    @include('coach.partials.modals.all-lists')

    {{-- Modal Raport & Fisik --}}
    @include('coach.partials.modals.raport-view')
    @include('coach.partials.modals.raport-form')
    @include('coach.partials.modals.physical-view')
    @include('coach.partials.modals.physical-form')

</div>
@endsection

@push('scripts')
    @include('coach.scripts.dashboard-js')
@endpush