<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\Attendance;
use App\Models\Raport;
use App\Models\Coach;
use App\Models\PhysicalTest;
use App\Models\PhysicalTestVariable;
use App\Models\TrainingSchedule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MemberDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        try {
            $member = Member::with([
                'user',
                'trainingPackage',
                'assignedCoaches.user',
                'raports.coach.user',
                'attendances.schedule',
                'attendances.coach.user'
            ])->where('user_id', $user->id)->first();

            if (!$member) {
                return redirect()->route('dashboard')->with('error', 'Data member tidak ditemukan.');
            }

            $assignedCoaches = $member->assignedCoaches ?? collect();

            $coachIds = $assignedCoaches->pluck('id');
            $trainingSchedules = TrainingSchedule::when($coachIds->isNotEmpty(), function($query) use ($coachIds) {
                $query->whereHas('coaches', function($q) use ($coachIds) {
                    $q->whereIn('coaches.id', $coachIds);
                });
            })
            ->with('coaches.user')
            ->get();

            $attendances = $member->attendances()
                ->with(['schedule', 'coach.user'])
                ->orderBy('date', 'desc')
                ->take(10)
                ->get();

            $raports = $member->raports()
                ->with(['coach.user'])
                ->orderBy('year', 'desc')
                ->get();

            $totalAttendances = $member->attendances()->count();
            $totalRaports = $member->raports()->count();
            $totalCoaches = $assignedCoaches->count();

            $existingStyles = [
                'gaya_bebas_50', 'gaya_bebas_100', 'gaya_bebas_200', 'gaya_bebas_400', 'gaya_bebas_800', 'gaya_bebas_1500',
                'gaya_dada_50', 'gaya_dada_100', 'gaya_dada_200',
                'gaya_punggung_50', 'gaya_punggung_100', 'gaya_punggung_200',
                'gaya_kupu_50', 'gaya_kupu_100', 'gaya_kupu_200',
                'gaya_ganti_200', 'gaya_ganti_400'
            ];

            return view('member.dashboard', compact(
                'member',
                'assignedCoaches',
                'trainingSchedules',
                'attendances',
                'raports',
                'totalAttendances',
                'totalRaports',
                'totalCoaches',
                'existingStyles'
            ));

        } catch (\Exception $e) {
            Log::error('Member Dashboard Error: ' . $e->getMessage());
            return redirect()->route('dashboard')->with('error', 'Terjadi kesalahan sistem.');
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
                return response()->json(['success' => false, 'message' => 'Data member tidak ditemukan'], 404);
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
                    'datasets' => [[
                        'label' => 'Waktu (detik)',
                        'data' => $raports->pluck('value')->map(fn($v) => (float) $v)->toArray(),
                        'borderColor' => 'rgb(59, 130, 246)',
                        'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                        'tension' => 0.4,
                    ]]
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
            Log::error('Member Performance Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan sistem'], 500);
        }
    }

    public function getPhysicalData(Request $request)
    {
        $user = Auth::user();
        $member = Member::where('user_id', $user->id)->first();

        if (!$member) {
            return response()->json(['success' => false, 'message' => 'Member not found'], 404);
        }

        $year = $request->input('year');
        $month = $request->input('month');

        $query = PhysicalTest::where('member_id', $member->id)
            ->where('year', $year);

        $history = (clone $query)->orderByRaw("CASE month 
                WHEN 'januari' THEN 1 WHEN 'februari' THEN 2 WHEN 'maret' THEN 3 
                WHEN 'april' THEN 4 WHEN 'mei' THEN 5 WHEN 'juni' THEN 6 
                WHEN 'juli' THEN 7 WHEN 'agustus' THEN 8 WHEN 'september' THEN 9 
                WHEN 'oktober' THEN 10 WHEN 'november' THEN 11 WHEN 'desember' THEN 12 
                ELSE 13 END")
            ->get();

        if ($month) {
            $selected = (clone $query)->where('month', $month)->first();
        } else {
            $selected = $history->last();
        }

        // Normalisasi dynamic
        $variables = PhysicalTestVariable::all();
        $radarLabels = $variables->pluck('name')->toArray();
        $radarData = [];

        if ($selected && $variables->count() > 0) {
            $results = $selected->results ?? [];
            foreach ($variables as $var) {
                $val = $results[$var->name] ?? 0;
                $score = ($var->goal_value > 0) ? round(($val / $var->goal_value) * 5, 2) : 0;
                $radarData[] = min(5, max(0, $score));
            }
        } else {
            $radarData = array_fill(0, count($radarLabels) ?: 5, 0);
        }

        return response()->json([
            'success' => true,
            'history' => $history,
            'radarData' => $radarData,
            'radarLabels' => $radarLabels,
            'selectedMonth' => $selected ? $selected->month : null
        ]);
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

    public function toggleStatus(Request $request)
    {
        $member = Member::where('user_id', Auth::id())->firstOrFail();

        $member->status = $member->status === 'AKTIF'
            ? 'TIDAK_AKTIF'
            : 'AKTIF';

        $member->save();

        return response()->json([
            'success' => true,
            'status' => $member->status,
        ]);
    }
}