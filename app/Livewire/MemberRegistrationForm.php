<?php

namespace App\Livewire;

use App\Models\Coach;
use App\Models\CoachSchedule;
use App\Models\Member;
use App\Models\TrainingPackage;
use App\Models\TrainingSchedule;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Component;

class MemberRegistrationForm extends Component
{
    // Form fields
    public $namaLengkap;
    public $noTelepon;
    public $pekerjaanAyah;
    public $jenisKelamin;
    public $tanggalLahir;
    public $paketLatihan;
    public $namaCoach;
    public $password;
    public $password_confirmation;
    public $selectedSchedules = [];

    // Success Modal Data
    public $isSuccessModalOpen = false;
    public $registeredData = [];

    // Data for view
    public $coachesData = [];
    public $packagesData = [];
    public $schedulesByDay = [];

    public function mount()
    {
        $this->packagesData = TrainingPackage::all();
        $this->coachesData = Coach::with('user')->get();
        $this->resetSchedules();
    }

    public function resetSchedules()
    {
        $this->schedulesByDay = [
            'Senin' => [],
            'Selasa' => [],
            'Rabu' => [],
            'Kamis' => [],
            'Jumat' => [],
            'Sabtu' => [],
            'Minggu' => [],
        ];
        $this->selectedSchedules = [];
    }

    public function updatedNamaCoach($coachId)
    {
        $this->resetSchedules();

        if (!$coachId) return;

        $coach = Coach::find($coachId);
        if (!$coach) return;

        // Fetch schedules assigned to this coach through pivot
        $coachSchedules = DB::table('coach_training_schedule')
            ->join('training_schedules', 'coach_training_schedule.training_schedule_id', '=', 'training_schedules.id')
            ->where('coach_training_schedule.coach_id', $coachId)
            ->select('training_schedules.*', 'coach_training_schedule.quota')
            ->get();

        foreach ($coachSchedules as $schedule) {
            $translatedDay = $this->translateDay($schedule->day);
            
            // Calculate usage (all members assigned to this coach)
            $usage = Member::whereHas('coaches', function ($query) use ($coachId) {
                $query->where('coaches.id', $coachId);
            })->count();

            $this->schedulesByDay[$translatedDay][] = [
                'id' => $schedule->id,
                'time' => Carbon::parse($schedule->time)->format('H:i'),
                'place' => $schedule->place,
                'quota' => $schedule->quota,
                'usage' => $usage,
                'is_full' => $usage >= $schedule->quota,
            ];
        }
    }

    private function translateDay($day)
    {
        return match (strtoupper($day)) {
            'MONDAY', 'SENIN' => 'Senin',
            'TUESDAY', 'SELASA' => 'Selasa',
            'WEDNESDAY', 'RABU' => 'Rabu',
            'THURSDAY', 'KAMIS' => 'Kamis',
            'FRIDAY', 'JUMAT' => 'Jumat',
            'SATURDAY', 'SABTU' => 'Sabtu',
            'SUNDAY', 'MINGGU' => 'Minggu',
            default => $day,
        };
    }

    public function submit()
    {
        $this->validate([
            'namaLengkap' => 'required|string|max:255',
            'noTelepon' => 'required|string|max:20|unique:users,phone',
            'pekerjaanAyah' => 'required|string|max:255',
            'jenisKelamin' => 'required|in:Laki-laki,Perempuan',
            'tanggalLahir' => 'required|date',
            'paketLatihan' => 'required|exists:training_packages,id',
            'namaCoach' => 'required|exists:coaches,id',
            'password' => 'required|string|min:6|confirmed',
            // 'selectedSchedules' => 'required|array|min:1', // No longer required as per-coach
        ]);

        try {
            DB::beginTransaction();

            // 1. Generate Email & Account
            $email = Str::slug($this->namaLengkap) . rand(10, 99) . '@cikampekswimming.club';
            $user = User::create([
                'name' => $this->namaLengkap,
                'email' => $email,
                'phone' => $this->noTelepon,
                'father_job' => $this->pekerjaanAyah,
                'gender' => strtoupper($this->jenisKelamin == 'Laki-laki' ? 'MALE' : 'FEMALE'),
                'birth_date' => $this->tanggalLahir,
                'password' => Hash::make($this->password),
                'active' => true,
            ]);
            $user->assignRole('member');

            // 2. Create Member
            $member = Member::create([
                'user_id' => $user->id,
                'training_package_id' => $this->paketLatihan,
                'status' => 'AKTIF',
                'start_date' => now(),
                'end_date' => now()->addMonth(),
            ]);

            // 3. Assign Coach (Pivot member_training_assignments)
            $member->coaches()->attach($this->namaCoach);

            // 4. Schedules are inherited from coach, no need to create MemberSchedule records


            // ✅ Kirim notifikasi ke Admin/Staff
            $admins = User::role(['admin', 'staff'])->get();
            foreach ($admins as $admin) {
                \Filament\Notifications\Notification::make()
                    ->title('Member Baru Terdaftar')
                    ->body("Atlet {$this->namaLengkap} telah mendaftar melalui form publik dan akun sudah otomatis aktif.")
                    ->icon('heroicon-o-user-plus')
                    ->iconColor('success')
                    ->actions([
                        \Filament\Actions\Action::make('view')
                            ->button()
                            ->label('Lihat Member')
                            ->url(\App\Filament\Resources\Members\MemberResource::getUrl('index')),
                    ])
                    ->sendToDatabase($admin);
            }

            DB::commit();

            // Set data for modal
            $this->registeredData = [
                'namaLengkap' => $this->namaLengkap,
                'email' => $email,
                'noTelepon' => $this->noTelepon,
                'password' => $this->password, // Show plain password once for the user to print
                'jenisKelamin' => $this->jenisKelamin,
                'tanggalLahir' => $this->tanggalLahir,
                'paketLatihan' => TrainingPackage::find($this->paketLatihan)?->name,
                'namaCoach' => Coach::find($this->namaCoach)?->user->name,
                'waktuDaftar' => now()->format('d M Y H:i'),
            ];

            $this->isSuccessModalOpen = true;

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function downloadPdf()
    {
        if (empty($this->registeredData)) {
            return;
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.registration-success', ['data' => $this->registeredData]);
        
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'Bukti-Pendaftaran-' . Str::slug($this->registeredData['namaLengkap']) . '.pdf');
    }

    public function redirectToLogin()
    {
        return redirect()->route('login');
    }

    public function render()
    {
        return view('livewire.member-registration-form');
    }
}
