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
    public function execute(): int
    {
        $activeMembers = Member::with(['user', 'trainingPackage'])
            ->where('status', 'AKTIF')
            ->get();

        if ($activeMembers->isEmpty()) {
            return 0;
        }

        $period = Carbon::now()->format('Y-m');

        return DB::transaction(function () use ($activeMembers, $period) {
            foreach ($activeMembers as $member) {
                MemberArchive::create([
                    'archive_period' => $period,
                    'member_id' => $member->id,
                    'user_id' => $member->user_id,
                    'name' => $member->user?->name ?? 'Unknown',
                    'email' => $member->user?->email ?? 'unknown@example.com',
                    'phone' => $member->user?->phone,
                    'training_package_name' => $member->trainingPackage?->name ?? 'N/A',
                    'status' => $member->status,
                    'start_date' => $member->start_date,
                    'end_date' => $member->end_date,
                ]);
            }

            // Reset all members to inactive
            Member::where('status', 'AKTIF')->update(['status' => 'TIDAK_AKTIF']);
            
            return $activeMembers->count();
        });
    }
}
