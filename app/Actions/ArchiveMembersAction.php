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
        $activeMembers = Member::with(['user', 'trainingPackage', 'coaches.user'])
            ->where('status', 'AKTIF')
            ->get();

        if ($activeMembers->isEmpty()) {
            return 0;
        }

        $period = Carbon::now()->format('Y-m');

        return DB::transaction(function () use ($activeMembers, $period, $shouldResetStatus) {
            $archiveCount = 0;

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

                if ($member->coaches->isEmpty()) {
                    // Member tanpa coach → 1 record, coach null
                    MemberArchive::create(array_merge($baseData, [
                        'coach_name' => null,
                        'coach_id'   => null,
                    ]));
                    $archiveCount++;
                } else {
                    // Member dengan N coach → N record (1 per coach)
                    foreach ($member->coaches as $coach) {
                        MemberArchive::create(array_merge($baseData, [
                            'coach_name' => $coach->user?->name,
                            'coach_id'   => $coach->id,
                        ]));
                        $archiveCount++;
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
}
