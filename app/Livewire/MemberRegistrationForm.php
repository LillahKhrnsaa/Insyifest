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
    public $kelas; // UI-only field
    public $coach1, $coach2, $coach3;
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
    public $maxCoaches = 1;

    public function mount()
    {
        $this->packagesData = TrainingPackage::all();
        $this->coachesData = Coach::with('user')->get();
        $this->resetSchedules();
    }

    public function updatedPaketLatihan($value)
    {
        $this->kelas = null;
        $this->resetCoaches();
        $this->resetSchedules();
        
        $package = TrainingPackage::find($value);
        if ($package) {
            // Logic max coaches based on package name
            if (str_contains($package->name, '4x')) {
                $this->maxCoaches = 1;
            } else {
                $this->maxCoaches = 3;
            }
        }
    }

    public function updatedKelas()
    {
        $this->resetCoaches();
        $this->resetSchedules();
    }

    public function resetCoaches()
    {
        $this->coach1 = null;
        $this->coach2 = null;
        $this->coach3 = null;
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
        // Kita gunakan array flat untuk menyimpan pivot_id yang dipilih
        // agar lebih fleksibel dalam validasi dan penyimpanan
        $this->selectedSchedules = []; 
    }

    public function updatedCoach1() { $this->refreshSchedules(); }
    public function updatedCoach2() { $this->refreshSchedules(); }
    public function updatedCoach3() { $this->refreshSchedules(); }

    public function refreshSchedules()
    {
        $this->resetSchedules();

        $coachIds = array_filter([$this->coach1, $this->coach2, $this->coach3]);
        if (empty($coachIds)) return;

        // Fetch schedules assigned to these coaches through pivot
        // Kita gunakan groupBy untuk menghindari duplikasi jadwal yang sama untuk coach yang sama
        $coachSchedules = DB::table('coach_training_schedule')
            ->join('training_schedules', 'coach_training_schedule.training_schedule_id', '=', 'training_schedules.id')
            ->whereIn('coach_training_schedule.coach_id', $coachIds)
            ->select(
                'training_schedules.*', 
                'coach_training_schedule.quota', 
                'coach_training_schedule.coach_id',
                'coach_training_schedule.id as pivot_id'
            )
            ->orderBy('coach_training_schedule.quota', 'desc') // Ambil yang ada kuotanya jika duplikat
            ->get()
            ->unique(function ($item) {
                return $item->id . '-' . $item->coach_id;
            });

        foreach ($coachSchedules as $schedule) {
            $translatedDay = $this->translateDay($schedule->day);
            
            // Calculate usage for this specific coach & schedule
            $usage = DB::table('member_schedules')
                ->where('training_schedule_id', $schedule->id)
                ->where('coach_id', $schedule->coach_id)
                ->count();

            $coachName = Coach::find($schedule->coach_id)?->user->name;

            $this->schedulesByDay[$translatedDay][] = [
                'id' => $schedule->id,
                'pivot_id' => $schedule->pivot_id,
                'coach_id' => $schedule->coach_id,
                'coach_name' => $coachName,
                'time' => Carbon::parse($schedule->time)->format('H:i'),
                'place' => $schedule->place,
                'quota' => $schedule->quota,
                'usage' => $usage,
                'is_full' => $usage >= $schedule->quota,
            ];
        }
    }

    public function toggleSchedule($day, $coachId, $scheduleId)
    {
        // Jika sudah terpilih, maka kita hapus (unselect)
        if (isset($this->selectedSchedules[$day][$coachId]) && $this->selectedSchedules[$day][$coachId] == $scheduleId) {
            unset($this->selectedSchedules[$day][$coachId]);
            
            // Bersihkan array day jika kosong agar tidak mengganggu validasi min:1
            if (empty($this->selectedSchedules[$day])) {
                unset($this->selectedSchedules[$day]);
            }
        } else {
            // Jika belum terpilih, kita set
            $this->selectedSchedules[$day][$coachId] = $scheduleId;
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
        $rules = [
            'namaLengkap' => 'required|string|max:255',
            'noTelepon' => 'required|string|max:20|unique:users,phone',
            'pekerjaanAyah' => 'required|string|max:255',
            'jenisKelamin' => 'required|in:Laki-laki,Perempuan',
            'tanggalLahir' => 'required|date',
            'paketLatihan' => 'required|exists:training_packages,id',
            'kelas' => 'required',
            'coach1' => 'required|exists:coaches,id',
            'password' => 'required|string|min:6|confirmed',
            'selectedSchedules' => 'required|array|min:1', 
        ];

        if ($this->maxCoaches > 1) {
            $rules['coach2'] = 'required|exists:coaches,id';
            $rules['coach3'] = 'required|exists:coaches,id';
        }

        $this->validate($rules, [
            'namaLengkap.required' => 'Mohon ketikkan nama lengkap calon atlet.',
            'noTelepon.required' => 'Mohon ketikkan nomor telepon/WhatsApp yang bisa dihubungi.',
            'noTelepon.unique' => 'Nomor telepon ini sudah terdaftar. Silakan gunakan nomor lain.',
            'pekerjaanAyah.required' => 'Mohon isi kolom pekerjaan ayah.',
            'jenisKelamin.required' => 'Mohon pilih jenis kelamin calon atlet.',
            'tanggalLahir.required' => 'Mohon isi tanggal lahir calon atlet dengan benar.',
            'paketLatihan.required' => 'Mohon pilih salah satu paket latihan.',
            'kelas.required' => 'Mohon pilih kelas (Pemula/Mahir/Pro/Prestasi).',
            'coach1.required' => 'Mohon pilih pelatih (coach) utama.',
            'coach2.required' => 'Untuk paket ini, mohon pilih pelatih ke-2.',
            'coach3.required' => 'Untuk paket ini, mohon pilih pelatih ke-3.',
            'password.required' => 'Mohon buat password untuk login nanti.',
            'password.min' => 'Password terlalu pendek, mohon buat minimal 6 huruf/angka.',
            'password.confirmed' => 'Konfirmasi password tidak cocok dengan password di atas, mohon ketik ulang.',
            'selectedSchedules.required' => 'Mohon pilih minimal 1 (satu) hari dan jam latihan yang tersedia di bawah.',
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

            // 3. Assign Coaches
            $coachIds = array_filter([$this->coach1, $this->coach2, $this->coach3]);
            $member->coaches()->attach($coachIds);

            // 4. Save Selected Schedules
            foreach ($this->selectedSchedules as $day => $coaches) {
                foreach ($coaches as $coachId => $scheduleId) {
                    if ($scheduleId) {
                        DB::table('member_schedules')->insert([
                            'member_id' => $member->id,
                            'coach_id' => $coachId,
                            'training_schedule_id' => $scheduleId,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }

            // ✅ Kirim notifikasi ke Admin/Staff
            $admins = User::role(['admin', 'staff'])->get();
            foreach ($admins as $admin) {
                \Filament\Notifications\Notification::make()
                    ->title('Member Baru Terdaftar')
                    ->body("Atlet {$this->namaLengkap} telah mendaftar melalui form publik.")
                    ->icon('heroicon-o-user-plus')
                    ->iconColor('success')
                    ->sendToDatabase($admin);
            }

            DB::commit();

            // Set data for modal
            $allCoachNames = Coach::whereIn('id', $coachIds)->get()->map(fn($c) => $c->user->name)->implode(', ');
            $this->registeredData = [
                'namaLengkap' => $this->namaLengkap,
                'email' => $email,
                'noTelepon' => $this->noTelepon,
                'pekerjaanAyah' => $this->pekerjaanAyah,
                'password' => $this->password,
                'jenisKelamin' => $this->jenisKelamin,
                'tanggalLahir' => $this->tanggalLahir,
                'paketLatihan' => TrainingPackage::find($this->paketLatihan)?->name,
                'namaCoach' => $allCoachNames,
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
