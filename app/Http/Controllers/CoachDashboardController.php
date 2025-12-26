<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Coach;
use App\Models\Member;
use App\Models\Attendance;
use App\Models\PhysicalTest;
use App\Models\Raport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CoachDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // 1. Load Coach, SEMUA member reguler + user-nya, dan jadwal
        $coach = Coach::with([
            'user',
            'members.user', // Load SEMUA member untuk stats & tabel
            'trainingSchedules',
        ])->where('user_id', $user->id)->firstOrFail();

        // 2. Hitung Statistik (Dari SEMUA member reguler, sesuai kode aslimu)
        $totalMembers = $coach->members->count();
        $activeMembers = $coach->members->where('status', 'AKTIF')->count();
        $inactiveMembers = $coach->members->where('status', 'TIDAK_AKTIF')->count();
        $totalSchedules = $coach->trainingSchedules->count();


        // --- INI UPDATE-NYA ---

        // 3. Buat list BARU untuk MODAL (Filter ganda)
        $activeRegularMembers = $coach->members->filter(function ($member) {
            // Cek status di tabel members DAN user
            return $member->status === 'AKTIF' && $member->user && $member->user->active === 1;
        });
        
        // 4. Buat list EKSTRA untuk MODAL (Query DB dengan filter ganda)
        $coachMemberIds = $coach->members->pluck('id');
        $allOtherMembers = Member::with('user')
                            ->where('status', 'AKTIF') // Cek status di 'members'
                            ->whereHas('user', function ($q) {
                                $q->where('active', 1); // Cek status di 'users'
                            })
                            ->whereNotIn('id', $coachMemberIds) // Bukan member reguler
                            ->get();

        // --- SELESAI UPDATE ---

        // 5. Ambil Riwayat Absensi (Tidak berubah, sudah benar)
        $attendances = Attendance::where('coach_id', $coach->id)
                            ->with('schedule') 
                            ->withCount('members') 
                            ->orderBy('date', 'desc') 
                            ->get();

        return view('coach.dashboard', compact(
            'coach',                // Untuk tabel 'Atlet' (menampilkan semua)
            'totalMembers',         // Stat
            'activeMembers',        // Stat
            'inactiveMembers',      // Stat
            'totalSchedules',
            'activeRegularMembers', // <-- Data BARU untuk modal checklist 1
            'allOtherMembers',      // Data BARU (terfilter) untuk modal checklist 2
            'attendances'
        ));
    }

    public function getChartData(Request $request)
    {
        try {
            $request->validate([
                'member_id' => 'required|integer|exists:members,id',
                'gaya' => 'required|string',
                'year' => 'required|integer|min:2000|max:2099',
            ]);

            $memberId = $request->input('member_id');
            $gaya = $request->input('gaya');
            $year = $request->input('year');

            $user = Auth::user();
            $coach = Coach::where('user_id', $user->id)->first();
            
            if (!$coach) return response()->json(['success' => false, 'message' => 'Coach tidak ditemukan'], 404);

            $member = $coach->members()->where('members.id', $memberId)->first();
            if (!$member) return response()->json(['success' => false, 'message' => 'Akses ditolak'], 403);

            // 1. Ambil Data Renang (Raports)
            $raports = Raport::where('member_id', $memberId)
                ->where('gaya', $gaya)
                ->where('year', $year)
                ->whereNotNull('value')
                ->orderByRaw("CASE month 
                    WHEN 'januari' THEN 1 WHEN 'februari' THEN 2 WHEN 'maret' THEN 3 WHEN 'april' THEN 4 
                    WHEN 'mei' THEN 5 WHEN 'juni' THEN 6 WHEN 'juli' THEN 7 WHEN 'agustus' THEN 8 
                    WHEN 'september' THEN 9 WHEN 'oktober' THEN 10 WHEN 'november' THEN 11 WHEN 'desember' THEN 12 
                    ELSE 13 END")
                ->get();

            // 2. Ambil Data Fisik Terbaru (PhysicalTest) untuk Radar
            $latestPhysical = \App\Models\PhysicalTest::where('member_id', $memberId)
                ->where('year', $year)
                ->orderByRaw("CASE month 
                    WHEN 'desember' THEN 1 WHEN 'november' THEN 2 WHEN 'oktober' THEN 3 WHEN 'september' THEN 4 
                    WHEN 'agustus' THEN 5 WHEN 'juli' THEN 6 WHEN 'juni' THEN 7 WHEN 'mei' THEN 8 
                    WHEN 'april' THEN 9 WHEN 'maret' THEN 10 WHEN 'februari' THEN 11 WHEN 'januari' THEN 12 
                    END")
                ->first();

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
                        ['label' => 'Volume (m)', 'data' => $raports->pluck('volume')->toArray(), 'borderColor' => 'rgb(34, 197, 94)'],
                        ['label' => 'Intensity (%)', 'data' => $raports->pluck('intensity')->toArray(), 'borderColor' => 'rgb(234, 179, 8)'],
                    ]
                ],
                // DATA RADAR UNTUK SPIDER CHART
                'chartRadar' => [
                    'labels' => ['Speed', 'Strength', 'Endurance', 'Flexibility', 'Agility'],
                    'datasets' => [[
                        'label' => 'Profil Fisik Atlet',
                        'data' => $latestPhysical ? [
                            round(max(0, min(5, 5 - ($latestPhysical->sprint_20m / 2))), 2),
                            round(min(5, ($latestPhysical->push_up + $latestPhysical->sit_up) / 16), 2),
                            round(min(5, $latestPhysical->vo2max / 10), 2),
                            round(min(5, $latestPhysical->v_sit_reach / 6), 2),
                            round(max(0, min(5, 10 - $latestPhysical->shuttle_run)), 2),
                        ] : [0, 0, 0, 0, 0],
                        'borderColor' => 'rgb(236, 72, 153)',
                        'backgroundColor' => 'rgba(236, 72, 153, 0.2)',
                    ]]
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * ═══════════════════════════════════════════════════════════════
     * CREATE Raport
     * ═══════════════════════════════════════════════════════════════
     */
    public function createRaport(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'member_id' => 'required|integer|exists:members,id',
                'gaya' => 'required|string',
                'year' => 'required|integer',
                'month' => 'required|string',
                'value' => 'required|numeric',
                'coach_id' => 'required|integer|exists:coaches,id',
            ]);

            if ($validator->fails()) return response()->json(['success' => false, 'errors' => $validator->errors()], 422);

            // Cek duplikasi raport renang
            $exists = Raport::where($request->only(['member_id', 'gaya', 'year', 'month']))->exists();
            if ($exists) return response()->json(['success' => false, 'message' => 'Data renang bulan ini sudah ada!'], 422);

            // 1. Simpan Data Renang
            $raport = Raport::create($request->only(['gaya', 'coach_id', 'member_id', 'year', 'month', 'note', 'value', 'volume', 'intensity', 'peaking']));

            // 2. Simpan/Update Data Fisik (Hanya jika bleep_level diisi)
            if ($request->filled('bleep_level')) {
                PhysicalTest::updateOrCreate(
                    ['member_id' => $request->member_id, 'year' => $request->year, 'month' => $request->month],
                    $request->only(['coach_id', 'sprint_20m', 'push_up', 'sit_up', 'run_300m', 'v_sit_reach', 'bleep_level', 'bleep_shuttle', 'shuttle_run', 'note'])
                );
            }

            return response()->json(['success' => true, 'message' => 'Raport & Data Fisik berhasil ditambahkan']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * ═══════════════════════════════════════════════════════════════
     * UPDATE Raport
     * ═══════════════════════════════════════════════════════════════
     */
    public function updateRaport(Request $request, $id)
    {
        try {
            $raport = Raport::findOrFail($id);
            
            // 1. Update Data Renang
            $raport->update($request->only(['value', 'volume', 'intensity', 'peaking', 'coach_id', 'note']));

            // 2. Update Data Fisik Terkait
            if ($request->filled('bleep_level')) {
                PhysicalTest::updateOrCreate(
                    ['member_id' => $raport->member_id, 'year' => $raport->year, 'month' => $raport->month],
                    $request->only(['coach_id', 'sprint_20m', 'push_up', 'sit_up', 'run_300m', 'v_sit_reach', 'bleep_level', 'bleep_shuttle', 'shuttle_run', 'note'])
                );
            }

            return response()->json(['success' => true, 'message' => 'Raport & Data Fisik berhasil diupdate']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * ═══════════════════════════════════════════════════════════════
     * DELETE Raport
     * ═══════════════════════════════════════════════════════════════
     */
    public function deleteRaport($id)
    {
        try {
            $raport = Raport::findOrFail($id);
            $raport->delete();

            return response()->json([
                'success' => true,
                'message' => 'Raport berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus raport: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * ═══════════════════════════════════════════════════════════════
     * GET Available Months (untuk dropdown Create)
     * ═══════════════════════════════════════════════════════════════
     */
    public function getAvailableMonths(Request $request)
    {
        $memberId = $request->input('member_id');
        $gaya = $request->input('gaya');
        $year = $request->input('year');

        $allMonths = [
            'januari' => 'Januari',
            'februari' => 'Februari',
            'maret' => 'Maret',
            'april' => 'April',
            'mei' => 'Mei',
            'juni' => 'Juni',
            'juli' => 'Juli',
            'agustus' => 'Agustus',
            'september' => 'September',
            'oktober' => 'Oktober',
            'november' => 'November',
            'desember' => 'Desember'
        ];

        $usedMonths = Raport::where('member_id', $memberId)
            ->where('gaya', $gaya)
            ->where('year', $year)
            ->pluck('month')
            ->toArray();

        $availableMonths = array_diff_key($allMonths, array_flip($usedMonths));

        return response()->json([
            'success' => true,
            'months' => $availableMonths
        ]);
    }

    /**
     * ═══════════════════════════════════════════════════════════════
     * GET Coaches List (untuk dropdown)
     * ═══════════════════════════════════════════════════════════════
     */
    public function getCoachesList()
    {
        $coaches = Coach::with('user')->get()->map(function ($coach) {
            return [
                'id' => $coach->id,
                'name' => $coach->user->name ?? "Coach #{$coach->id}"
            ];
        });

        return response()->json([
            'success' => true,
            'coaches' => $coaches
        ]);
    }
}