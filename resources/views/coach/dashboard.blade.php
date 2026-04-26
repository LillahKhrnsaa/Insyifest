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
        },

        // MEMBER MANAGEMENT STATE
        showMemberModal: false,
        memberModalMode: 'create', // create or edit
        memberId: null,
        memberForm: {
            name: '',
            email: '',
            password: '',
            phone: '',
            gender: '',
            training_package_id: '',
            status: 'AKTIF'
        },

        async submitMemberForm() {
            const url = this.memberModalMode === 'create' 
                ? '{{ route("coach.member.store") }}' 
                : `/coach/member/update/${this.memberId}`;
            
            const method = this.memberModalMode === 'create' ? 'POST' : 'PUT';
            
            try {
                const response = await fetch(url, {
                    method: 'POST', // Use POST with _method for PUT
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        ...this.memberForm,
                        _method: method
                    })
                });

                const result = await response.json();
                
                if (result.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: result.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    let errorMessage = result.message || 'Terjadi kesalahan';
                    if (result.errors) {
                        errorMessage = Object.values(result.errors).flat().join('\n');
                    }
                    Swal.fire('Gagal', errorMessage, 'error');
                }
            } catch (error) {
                console.error(error);
                Swal.fire('Error', 'Terjadi kesalahan sistem', 'error');
            }
        },

        editMember(data) {
            this.memberId = data.id;
            this.memberModalMode = 'edit';
            this.memberForm = {
                name: data.name,
                email: data.email,
                password: '', // default empty for edit
                training_package_id: data.training_package_id,
                training_schedule_id: data.training_schedule_id,
                status: data.status
            };
            this.showMemberModal = true;
        }
    }"
    class="min-h-screen relative overflow-hidden" style="background: linear-gradient(135deg, #dbeafe 0%, #fae8ff 100%);"
>
    {{-- Floating Orbs --}}
    <div class="absolute inset-0 pointer-events-none overflow-hidden z-0">
        <div class="absolute top-[-10%] left-[-10%] w-[600px] h-[600px] rounded-full opacity-60" style="background: radial-gradient(circle, #93c5fd, transparent); filter: blur(80px);"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[500px] h-[500px] rounded-full opacity-60" style="background: radial-gradient(circle, #fbcfe8, transparent); filter: blur(80px);"></div>
        <div class="absolute top-[40%] left-[60%] w-[400px] h-[400px] rounded-full opacity-50" style="background: radial-gradient(circle, #c4b5fd, transparent); filter: blur(80px);"></div>
    </div>

    <div class="relative z-10">
        <main class="px-6 lg:px-10 py-8 space-y-8 max-w-7xl mx-auto">

            {{-- HEADER --}}
            <section class="rounded-[2.5rem] shadow-xl p-8 sm:p-10 relative overflow-hidden"
                style="background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 100%); border: 1px solid rgba(255,255,255,0.1);">
                {{-- Pattern overlay --}}
                <div class="absolute inset-0 opacity-10"
                    style="background: repeating-linear-gradient(45deg, transparent, transparent 25px, rgba(255,255,255,0.05) 25px, rgba(255,255,255,0.05) 50px);"></div>
                {{-- Glow --}}
                <div class="absolute -right-20 -top-20 w-64 h-64 rounded-full opacity-30"
                    style="background: radial-gradient(circle, #3b82f6, transparent);"></div>

                <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                    <div class="flex items-center gap-5">
                        <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-[1.5rem] overflow-hidden bg-white/10 border-[3px] border-white/20 shadow-2xl flex-shrink-0 flex items-center justify-center backdrop-blur-md text-white/80">
                            <i data-feather="user" class="w-8 h-8 sm:w-10 sm:h-10"></i>
                        </div>
                        <div>
                            <h1 class="text-2xl lg:text-3xl font-black text-white uppercase tracking-tight drop-shadow-md">
                                Dashboard Pelatih
                            </h1>
                            <p class="mt-2 text-blue-200 font-medium">
                                Selamat datang, <span class="font-bold text-white">Coach {{ explode(' ', Auth::user()->name)[0] }}</span>.
                                Kelola atlet, jadwal, dan kehadiran dengan mudah.
                            </p>
                        </div>
                    </div>
                    <div class="flex-shrink-0 text-sm font-bold text-white px-5 py-3 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 shadow-inner text-center">
                        <span class="block text-[10px] uppercase tracking-widest text-blue-300 mb-1">Tanggal Hari Ini</span>
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
        <section class="flex flex-col gap-6">
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                {{-- ATLET --}}
                <div class="xl:col-span-2">
                    @include('coach.partials.members-card')
                </div>

                {{-- JADWAL --}}
                <div class="xl:col-span-1">
                    @include('coach.partials.schedules-card')
                </div>
            </div>

            {{-- RIWAYAT ABSENSI --}}
            <div>
                @include('coach.partials.history-card')
            </div>
        </section>

    </main>
    </div>

    {{-- MODALS --}}
    @include('coach.partials.modals.attendance-form')
    @include('coach.partials.modals.attendance-detail')
    @include('coach.partials.modals.attendance-edit')
    @include('coach.partials.modals.all-lists')
    @include('coach.partials.modals.raport-view')
    @include('coach.partials.modals.raport-form')
    @include('coach.partials.modals.physical-view')
    @include('coach.partials.modals.physical-form')
    @include('coach.partials.modals.member-form')

</div>
@endsection

@push('scripts')
    @include('coach.scripts.dashboard-js')
@endpush
