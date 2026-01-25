@extends('layouts.coach')

@section('title', 'Dashboard Pelatih')

@section('content')
<div 
    x-data="{ 
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
        
        schedules: {{ $coach->trainingSchedules->map(fn($s) => [
            'id' => $s->id, 
            'time' => $s->time, 
            'place' => $s->place, 
            'label' => ucfirst($s->day).' ('.$s->time.')'
        ]) }},

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
    }"
    class="min-h-screen bg-slate-50"
>

    <main class="px-6 lg:px-10 py-8 space-y-8">

        {{-- HEADER --}}
        <section class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-2xl lg:text-3xl font-bold text-slate-800">
                        Dashboard Pelatih
                    </h1>
                    <p class="mt-1 text-slate-600">
                        Selamat datang, Coach {{ explode(' ', Auth::user()->name)[0] }}.
                        Kelola atlet, jadwal, dan kehadiran dengan mudah.
                    </p>
                </div>
                <div class="text-sm text-slate-500">
                    {{ now()->isoFormat('dddd, D MMMM YYYY') }}
                </div>
            </div>
        </section>

        {{-- ALERT --}}
        @include('coach.partials.alerts')

        {{-- STATISTIK --}}
        <section>
            @include('coach.partials.stats')
        </section>

        {{-- OPERASIONAL --}}
        <section class="grid grid-cols-1 xl:grid-cols-3 gap-6">

            {{-- ATLET --}}
            <div class="xl:col-span-2">
                @include('coach.partials.members-card')
            </div>

            {{-- JADWAL & RIWAYAT --}}
            <div class="space-y-6">
                @include('coach.partials.schedules-card')
                @include('coach.partials.history-card')
            </div>

        </section>

    </main>

    {{-- MODALS --}}
    @include('coach.partials.modals.attendance-form')
    @include('coach.partials.modals.attendance-detail')
    @include('coach.partials.modals.attendance-edit')
    @include('coach.partials.modals.all-lists')
    @include('coach.partials.modals.raport-view')
    @include('coach.partials.modals.raport-form')
    @include('coach.partials.modals.physical-view')
    @include('coach.partials.modals.physical-form')

</div>
@endsection

@push('scripts')
    @include('coach.scripts.dashboard-js')
@endpush
