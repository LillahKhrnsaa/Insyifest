<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Coach;
use App\Models\Member;
use App\Models\Attendance;
use App\Models\PhysicalTest;
use App\Models\Raport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CoachDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // 1. Load Coach, Member, dan User terkait
        // Menggunakan firstOrFail agar jika user bukan coach, langsung 404 (atau bisa handle error lain)
        $coach = Coach::with([
            'user',
            'members.user', // Eager load user dari setiap member
            'trainingSchedules',
        ])->where('user_id', $user->id)->firstOrFail();

        // 2. Hitung Statistik
        $totalMembers = $coach->members->count();
        $activeMembers = $coach->members->where('status', 'AKTIF')->count();
        $inactiveMembers = $coach->members->where('status', 'TIDAK_AKTIF')->count();
        $totalSchedules = $coach->trainingSchedules->count();

        // 3. DAFTAR GAYA (HARDCODED)
        // Ini solusi agar dropdown tidak kosong meskipun belum ada data di tabel raport
        $existingStyles = [
            'gaya_bebas_50', 'gaya_bebas_100', 'gaya_bebas_200', 'gaya_bebas_400', 'gaya_bebas_800', 'gaya_bebas_1500',
            'gaya_dada_50', 'gaya_dada_100', 'gaya_dada_200',
            'gaya_punggung_50', 'gaya_punggung_100', 'gaya_punggung_200',
            'gaya_kupu_50', 'gaya_kupu_100', 'gaya_kupu_200',
            'gaya_ganti_200', 'gaya_ganti_400'
        ];

        // 4. List Member Reguler (Filter Aktif di Member & User)
        // Digunakan untuk Modal Checklist Absensi
        $activeRegularMembers = $coach->members->filter(function ($member) {
            return $member->status === 'AKTIF' && $member->user && $member->user->active === 1;
        });
        
        // 5. List Member Lain (Di luar binaan coach ini)
        // Jika nanti dibutuhkan untuk fitur transfer atlet atau melihat atlet lain
        $coachMemberIds = $coach->members->pluck('id');
        $allOtherMembers = Member::with('user')
                            ->where('status', 'AKTIF')
                            ->whereHas('user', function ($q) {
                                $q->where('active', 1);
                            })
                            ->whereNotIn('id', $coachMemberIds)
                            ->get();

        // 6. Riwayat Absensi
        $attendances = Attendance::where('coach_id', $coach->id)
                            ->with(['members.user', 'schedule'])
                            ->withCount('members') 
                            ->orderBy('date', 'desc') 
                            ->get();

        return view('coach.dashboard', compact(
            'coach', 
            'totalMembers', 
            'activeMembers', 
            'inactiveMembers',
            'totalSchedules', 
            'activeRegularMembers', 
            'allOtherMembers',
            'attendances', 
            'existingStyles' // <-- Array manual dikirim ke view
        ));
    }

    public function getChartData(Request $request)
    {
        try {
            // Validasi input
            $request->validate([
                'member_id' => 'required|integer|exists:members,id',
                'gaya' => 'required|string',
                'year' => 'required|integer|min:2000|max:2099',
            ]);

            $memberId = $request->input('member_id');
            $gaya = $request->input('gaya');
            $year = $request->input('year');

            // Opsional: Cek apakah coach berhak akses member ini
            $user = Auth::user();
            $coach = Coach::where('user_id', $user->id)->first();
            
            if (!$coach) {
                return response()->json([
                    'success' => false,
                    'message' => 'Coach tidak ditemukan'
                ], 404);
            }

            // ✅ FIX: Tambahkan prefix tabel untuk menghindari ambiguous column
            $member = $coach->members()
                ->where('members.id', $memberId) // ← TAMBAHKAN 'members.' prefix
                ->first();
            
            if (!$member) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses ke member ini'
                ], 403);
            }

            // Query raport dengan ordering bulan
            $raports = Raport::where('member_id', $memberId)
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

            // Format data untuk response
            $labels = $raports->pluck('month')->map(fn($m) => ucfirst($m))->toArray();
            
            return response()->json([
                'success' => true,
                'raports' => $raports,
                
                // Data untuk Chart 1 (Waktu Tempuh)
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
                
                // Data untuk Chart 2 (Volume, Peaking, Intensity)
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

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            // Log error untuk debugging
            Log::error('Raport Chart Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
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
                'volume' => 'required|numeric',
                'intensity' => 'required|numeric',
                'peaking' => 'required|numeric',
                'coach_id' => 'required|integer|exists:coaches,id',
                'note' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            // Cek duplikasi
            $exists = Raport::where('member_id', $request->member_id)
                ->where('gaya', $request->gaya)
                ->where('year', $request->year)
                ->where('month', $request->month)
                ->exists();

            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data raport untuk bulan ini sudah ada!'
                ], 422);
            }

            $raport = Raport::create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Raport berhasil ditambahkan',
                'data' => $raport
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan raport: ' . $e->getMessage()
            ], 500);
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

            $validator = Validator::make($request->all(), [
                'value' => 'required|numeric',
                'volume' => 'required|numeric',
                'intensity' => 'required|numeric',
                'peaking' => 'required|numeric',
                'coach_id' => 'required|integer|exists:coaches,id',
                'note' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            // Lock field penting (tidak bisa diubah)
            $raport->update([
                'value' => $request->value,
                'volume' => $request->volume,
                'intensity' => $request->intensity,
                'peaking' => $request->peaking,
                'coach_id' => $request->coach_id,
                'note' => $request->note,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Raport berhasil diupdate',
                'data' => $raport
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate raport: ' . $e->getMessage()
            ], 500);
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

    // --- KHUSUS FISIK ---
    public function getPhysicalData(Request $request)
    {
        $history = \App\Models\PhysicalTest::where('member_id', $request->member_id)
            ->where('year', $request->year)
            ->orderByRaw("CASE month 
                WHEN 'januari' THEN 1 WHEN 'februari' THEN 2 WHEN 'maret' THEN 3 
                WHEN 'april' THEN 4 WHEN 'mei' THEN 5 WHEN 'juni' THEN 6 
                WHEN 'juli' THEN 7 WHEN 'agustus' THEN 8 WHEN 'september' THEN 9 
                WHEN 'oktober' THEN 10 WHEN 'november' THEN 11 WHEN 'desember' THEN 12 
                ELSE 13 END")
            ->get();

        $latest = $history->last();

        // Normalisasi Skor Radar 1-5
        $radarData = $latest ? [
            round(max(0, min(5, 5 - ($latest->sprint_20m / 2))), 2),
            round(min(5, $latest->push_up / 10), 2),
            round(min(5, $latest->vo2max / 10), 2),
            round(min(5, $latest->v_sit_reach / 6), 2),
            round(max(0, min(5, 10 - $latest->shuttle_run)), 2),
        ] : [0,0,0,0,0];

        return response()->json([
            'success' => true,
            'history' => $history,
            'radarData' => $radarData
        ]);
    }

    public function storePhysicalTest(Request $request)
    {
        $data = $request->all();
        $data['coach_id'] = Auth::user()->coach->id; // Auto set coach
        
        $phys = PhysicalTest::updateOrCreate(
            ['member_id' => $request->member_id, 'year' => $request->year, 'month' => $request->month],
            $data
        );

        return response()->json(['success' => true, 'message' => 'Data fisik berhasil disimpan!']);
    }

    public function updateAttendance(Request $request, $id)
    {
        try {
            $attendance = Attendance::where('coach_id', Auth::user()->coach->id)->findOrFail($id);

            $validator = Validator::make($request->all(), [
                'date' => 'required|date',
                'time' => 'required',
                'place' => 'required|string|max:255',
                'notes' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
            }

            $attendance->update([
                'date' => $request->date,
                'time' => $request->time,
                'place' => $request->place,
                'notes' => $request->notes,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data absensi berhasil diperbarui!'
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal update: ' . $e->getMessage()], 500);
        }
    }

    public function destroyAttendance($id)
    {
        try {
            // Pastikan hanya menghapus milik coach yang sedang login
            $attendance = Attendance::where('coach_id', Auth::user()->coach->id)->findOrFail($id);

            DB::transaction(function () use ($attendance) {
                // Hapus relasi member (pivot table)
                $attendance->members()->detach();
                // Hapus record absensi
                $attendance->delete();
            });

            return response()->json([
                'success' => true,
                'message' => 'Riwayat absensi berhasil dihapus permanen.'
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal hapus: ' . $e->getMessage()], 500);
        }
    }
}