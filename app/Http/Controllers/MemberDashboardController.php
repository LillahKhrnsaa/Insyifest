<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\Attendance;
use App\Models\Raport;
use App\Models\Coach;
use App\Models\TrainingSchedule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MemberDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        try {
            // Debug: Cek user
            Log::info('Member Dashboard Access', [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'user_email' => $user->email
            ]);

            // 1. Load member data
            $member = Member::with([
                'user',
                'trainingPackage',
                'assignedCoaches.user',
                'raports.coach.user',
                'attendances.schedule',
                'attendances.coach.user'
            ])->where('user_id', $user->id)->first();

            // Debug: Cek data member
            if ($member) {
                Log::info('Member Data Found', [
                    'member_id' => $member->id,
                    'member_status' => $member->status,
                    'has_training_package' => !is_null($member->trainingPackage),
                    'assigned_coaches_count' => $member->assignedCoaches->count(),
                    'attendances_count' => $member->attendances->count(),
                    'raports_count' => $member->raports->count()
                ]);
            } else {
                Log::warning('Member Data Not Found', ['user_id' => $user->id]);
                return redirect()->route('dashboard')->with('error', 'Data member tidak ditemukan. Silakan hubungi administrator.');
            }

            // 2. Get coach terkait
            $assignedCoaches = $member->assignedCoaches ?? collect();
            Log::info('Assigned Coaches', ['count' => $assignedCoaches->count()]);

            // 3. Get jadwal latihan
            $coachIds = $assignedCoaches->pluck('id');
            $trainingSchedules = TrainingSchedule::when($coachIds->isNotEmpty(), function($query) use ($coachIds) {
                $query->whereHas('coaches', function($q) use ($coachIds) {
                    $q->whereIn('coaches.id', $coachIds);
                });
            })
            ->with('coaches.user')
            ->get();

            Log::info('Training Schedules', ['count' => $trainingSchedules->count()]);

            // 4. Get riwayat absensi member
            $attendances = $member->attendances()
                ->with(['schedule', 'coach.user'])
                ->orderBy('date', 'desc')
                ->take(10)
                ->get();

            Log::info('Attendances', ['count' => $attendances->count()]);

            // 5. Get riwayat raport
            $raports = $member->raports()
                ->with(['coach.user'])
                ->orderBy('year', 'desc')
                ->orderByRaw("CASE month 
                    WHEN 'januari' THEN 1 
                    WHEN 'februari' THEN 2 
                    WHEN 'maret' THEN 3 
                    WHEN 'april' THEN 4 
                    WHEN 'mei' THEN 5 
                    WHEN 'juni' THEN 6 
                    WHEN 'juli' THEN 7 
                    WHEN 'agustus' THEN 8 
                    WHEN 'september' THEN 9 
                    WHEN 'oktober' THEN 10 
                    WHEN 'november' THEN 11 
                    WHEN 'desember' THEN 12 
                    ELSE 13 END")
                ->get();

            Log::info('Raports', ['count' => $raports->count()]);

            // 6. Hitung statistik
            $totalAttendances = $member->attendances()->count();
            $totalRaports = $member->raports()->count();
            $totalCoaches = $assignedCoaches->count();

            Log::info('Statistics', [
                'total_attendances' => $totalAttendances,
                'total_raports' => $totalRaports,
                'total_coaches' => $totalCoaches
            ]);

            return view('member.dashboard', compact(
                'member',
                'assignedCoaches',
                'trainingSchedules',
                'attendances',
                'raports',
                'totalAttendances',
                'totalRaports',
                'totalCoaches'
            ));

        } catch (\Exception $e) {
            Log::error('Member Dashboard Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'user_id' => $user->id ?? 'unknown'
            ]);
            return redirect()->route('dashboard')->with('error', 'Terjadi kesalahan saat memuat dashboard member: ' . $e->getMessage());
        }
    }

    public function getPerformanceData(Request $request)
    {
        try {
            $request->validate([
                'gaya' => 'required|string',
                'year' => 'required|integer|min:2000|max:2099',
            ]);

            $user = Auth::user();
            $member = Member::where('user_id', $user->id)->first();

            if (!$member) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data member tidak ditemukan',
                ], 404);
            }

            $gaya = $request->input('gaya');
            $year = $request->input('year');

            $raports = Raport::where('member_id', $member->id)
                ->where('gaya', $gaya)
                ->where('year', $year)
                ->whereNotNull('value')
                ->where('value', '>', 0)
                ->orderByRaw("CASE month 
                    WHEN 'januari' THEN 1 
                    WHEN 'februari' THEN 2 
                    WHEN 'maret' THEN 3 
                    WHEN 'april' THEN 4 
                    WHEN 'mei' THEN 5 
                    WHEN 'juni' THEN 6 
                    WHEN 'juli' THEN 7 
                    WHEN 'agustus' THEN 8 
                    WHEN 'september' THEN 9 
                    WHEN 'oktober' THEN 10 
                    WHEN 'november' THEN 11 
                    WHEN 'desember' THEN 12 
                    ELSE 13 END")
                ->with(['coach.user'])
                ->get();

            $labels = $raports->pluck('month')->map(fn($m) => ucfirst($m))->toArray();
            
            return response()->json([
                'success' => true,
                'raports' => $raports,
                
                'chartValue' => [
                    'labels' => $labels,
                    'datasets' => [
                        [
                            'label' => 'Waktu (detik)',
                            'data' => $raports->pluck('value')->map(fn($v) => (float) $v)->toArray(),
                            'borderColor' => 'rgb(59, 130, 246)',
                            'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                            'tension' => 0.4,
                        ]
                    ]
                ],
                
                'chartVolume' => [
                    'labels' => $labels,
                    'datasets' => [
                        [
                            'label' => 'Volume (meter)',
                            'data' => $raports->pluck('volume')->map(fn($v) => (float) $v)->toArray(),
                            'borderColor' => 'rgb(34, 197, 94)',
                            'backgroundColor' => 'rgba(34, 197, 94, 0.2)',
                            'tension' => 0.4,
                        ],
                        [
                            'label' => 'Peaking (%)',
                            'data' => $raports->pluck('peaking')->map(fn($v) => (float) $v)->toArray(),
                            'borderColor' => 'rgb(236, 72, 153)',
                            'backgroundColor' => 'rgba(236, 72, 153, 0.2)',
                            'tension' => 0.4,
                        ],
                        [
                            'label' => 'Intensity (%)',
                            'data' => $raports->pluck('intensity')->map(fn($v) => (float) $v)->toArray(),
                            'borderColor' => 'rgb(234, 179, 8)',
                            'backgroundColor' => 'rgba(234, 179, 8, 0.2)',
                            'tension' => 0.4,
                        ],
                    ]
                ],
            ]);

        } catch (\Exception $e) {
            Log::error('Member Performance Data Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function getAttendanceHistory(Request $request)
    {
        try {
            $user = Auth::user();
            $member = Member::where('user_id', $user->id)->first();

            if (!$member) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data member tidak ditemukan',
                ], 404);
            }

            $attendances = $member->attendances()
                ->with(['schedule', 'coach.user'])
                ->orderBy('date', 'desc')
                ->paginate(10);

            return response()->json([
                'success' => true,
                'attendances' => $attendances,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }
}