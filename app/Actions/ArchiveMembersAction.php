<?php

namespace App\Actions;

use App\Models\Member;
use App\Models\MemberArchive;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ArchiveMembersAction
{
    /**
     * Archive all active members and reset their status to inactive.
     *
     * @return int Number of members archived.
     */
    public function execute(bool $shouldResetStatus = true): int
    {
        $activeMembers = Member::with(['user', 'trainingPackage', 'coaches.user', 'coaches.trainingSchedules'])
            ->where('status', 'AKTIF')
            ->get();

        if ($activeMembers->isEmpty()) {
            return 0;
        }

        $period = Carbon::now()->format('Y-m');

        return DB::transaction(function () use ($activeMembers, $period, $shouldResetStatus) {
            $archiveCount = 0;

            // Hapus data lama di periode yang sama untuk member yang sedang diproses
            // Ini untuk mencegah duplikasi jika user melakukan "Arsip Ulang" di bulan yang sama
            $memberIds = $activeMembers->pluck('id');
            MemberArchive::where('archive_period', $period)
                ->whereIn('member_id', $memberIds)
                ->delete();

            foreach ($activeMembers as $member) {
                $baseData = [
                    'archive_period'       => $period,
                    'member_id'            => $member->id,
                    'user_id'              => $member->user_id,
                    'name'                 => $member->user?->name ?? 'Unknown',
                    'email'                => $member->user?->email ?? 'unknown@example.com',
                    'phone'                => $member->user?->phone,
                    'training_package_name'=> $member->trainingPackage?->name ?? 'N/A',
                    'status'               => $member->status,
                    'start_date'           => $member->start_date,
                    'end_date'             => $member->end_date,
                ];

                // Load member-specific schedules from pivot
                $memberSpecificSchedules = DB::table('member_schedules')
                    ->where('member_id', $member->id)
                    ->get();

                // Kita unikkan coach-nya agar jika member pilih coach yang sama berkali-kali (di paket Pro)
                // tidak terjadi duplikasi perkalian (misal 3 coach x 3 jadwal = 9 baris)
                $uniqueCoaches = $member->coaches->unique('id');

                if ($uniqueCoaches->isEmpty()) {
                    // Jika sama sekali tidak ada coach
                    MemberArchive::create(array_merge($baseData, [
                        'coach_name' => null,
                        'coach_id'   => null,
                        'training_day' => null,
                        'training_time' => null,
                        'training_day_index' => null,
                    ]));
                    $archiveCount++;
                } else {
                    foreach ($uniqueCoaches as $coach) {
                        // Filter jadwal member yang spesifik untuk coach ini
                        $specificForCoach = $memberSpecificSchedules->where('coach_id', $coach->id);

                        if ($specificForCoach->isNotEmpty()) {
                            // Gunakan jadwal yang dipilih member
                            foreach ($specificForCoach as $ms) {
                                $ts = \App\Models\TrainingSchedule::find($ms->training_schedule_id);
                                if ($ts) {
                                    MemberArchive::create(array_merge($baseData, [
                                        'coach_name' => $coach->user?->name,
                                        'coach_id'   => $coach->id,
                                        'training_day' => $ts->day,
                                        'training_time' => $ts->time,
                                        'training_day_index' => $this->getDayIndex($ts->day),
                                    ]));
                                    $archiveCount++;
                                }
                            }
                        } else {
                            // FALLBACK: Gunakan semua jadwal yang dimiliki coach
                            $coachSchedules = $coach->trainingSchedules;

                            if ($coachSchedules->isNotEmpty()) {
                                foreach ($coachSchedules as $ts) {
                                    MemberArchive::create(array_merge($baseData, [
                                        'coach_name' => $coach->user?->name,
                                        'coach_id'   => $coach->id,
                                        'training_day' => $ts->day,
                                        'training_time' => $ts->time,
                                        'training_day_index' => $this->getDayIndex($ts->day),
                                    ]));
                                    $archiveCount++;
                                }
                            } else {
                                // Coach tidak punya jadwal sama sekali
                                MemberArchive::create(array_merge($baseData, [
                                    'coach_name' => $coach->user?->name,
                                    'coach_id'   => $coach->id,
                                    'training_day' => null,
                                    'training_time' => null,
                                    'training_day_index' => null,
                                ]));
                                $archiveCount++;
                            }
                        }
                    }
                }
            }

            // Reset semua member aktif jadi non-aktif jika diminta
            if ($shouldResetStatus) {
                Member::where('status', 'AKTIF')->update(['status' => 'TIDAK_AKTIF']);
            }

            return $archiveCount;
        });
    }

    /**
     * Get numeric index for Indonesian day names.
     */
    private function getDayIndex(?string $day): ?int
    {
        if (!$day) return null;

        $days = [
            'SENIN' => 1,
            'SELASA' => 2,
            'RABU' => 3,
            'KAMIS' => 4,
            'JUMAT' => 5,
            'SABTU' => 6,
            'MINGGU' => 7,
        ];

        return $days[strtoupper($day)] ?? 99;
    }
}
