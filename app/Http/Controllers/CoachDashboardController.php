<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Coach;
use App\Models\Member;
use App\Models\Attendance;
use App\Models\PhysicalTest;
use App\Models\PhysicalTestVariable;
use App\Models\Raport;
use App\Models\TrainingPackage;
use App\Models\TrainingSchedule;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
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

        // 7. Get Packages and Schedules for Member Creation
        $packages = TrainingPackage::all();
        $allSchedules = $coach->trainingSchedules;

        return view('coach.dashboard', compact(
            'coach', 
            'totalMembers', 
            'activeMembers', 
            'inactiveMembers',
            'totalSchedules', 
            'activeRegularMembers', 
            'allOtherMembers',
            'attendances', 
            'existingStyles', // <-- Array manual dikirim ke view
            'packages',
            'allSchedules'
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
    public function getPhysicalVariables()
    {
        return response()->json([
            'success' => true,
            'variables' => PhysicalTestVariable::all()
        ]);
    }

    public function storePhysicalVariables(Request $request)
    {
        $request->validate([
            'variables' => 'required|array',
            'variables.*.name' => 'required|string',
            'variables.*.goal_value' => 'required|numeric',
        ]);

        DB::transaction(function () use ($request) {
            PhysicalTestVariable::truncate();
            foreach ($request->variables as $var) {
                PhysicalTestVariable::create($var);
            }
        });

        return response()->json(['success' => true, 'message' => 'Variabel fisik berhasil diperbarui!']);
    }

    public function getPhysicalData(Request $request)
    {
        $year = $request->input('year');
        $month = $request->input('month');

        $query = PhysicalTest::where('member_id', $request->member_id)
            ->where('year', $year);

        $history = (clone $query)->orderByRaw("CASE month 
                WHEN 'januari' THEN 1 WHEN 'februari' THEN 2 WHEN 'maret' THEN 3 
                WHEN 'april' THEN 4 WHEN 'mei' THEN 5 WHEN 'juni' THEN 6 
                WHEN 'juli' THEN 7 WHEN 'agustus' THEN 8 WHEN 'september' THEN 9 
                WHEN 'oktober' THEN 10 WHEN 'november' THEN 11 WHEN 'desember' THEN 12 
                ELSE 13 END")
            ->get();

        // Cari data spesifik untuk radar chart
        if ($month) {
            $selected = (clone $query)->where('month', $month)->first();
        } else {
            $selected = $history->last();
        }

        $variables = PhysicalTestVariable::all();
        $radarLabels = $variables->pluck('name')->toArray();
        $radarData = [];

        if ($selected && $variables->count() > 0) {
            $results = $selected->results ?? [];
            foreach ($variables as $var) {
                $val = $results[$var->name] ?? 0;
                // Skala 1-5 berdasarkan goal_value
                $score = ($var->goal_value > 0) ? round(($val / $var->goal_value) * 5, 2) : 0;
                $radarData[] = min(5, max(0, $score));
            }
        } else {
            // Fallback ke data lama jika belum ada dynamic variables (opsional)
            // Tapi user minta dynamic, jadi kalau ga ada ya kosongin aja
            $radarData = array_fill(0, count($radarLabels) ?: 5, 0);
        }

        return response()->json([
            'success' => true,
            'history' => $history,
            'radarData' => $radarData,
            'radarLabels' => $radarLabels,
            'selectedMonth' => $selected ? $selected->month : null,
            'variables' => $variables
        ]);
    }

    public function storePhysicalTest(Request $request)
    {
        $coach = Auth::user()->coach;
        if (!$coach) {
            return response()->json(['success' => false, 'message' => 'Hanya pelatih yang dapat menyimpan data fisik.'], 403);
        }

        $data = $request->only(['member_id', 'year', 'month', 'note']);
        $data['coach_id'] = $coach->id;
        $data['results'] = $request->input('results', []);

        // Calculate VO2 Max if level and shuttle are provided in results
        if (isset($data['results']['Bleep Level']) && isset($data['results']['Bleep Shuttle'])) {
             $level = $data['results']['Bleep Level'];
             $shuttle = $data['results']['Bleep Shuttle'];
             $shuttleTable = [
                1=>9, 2=>8, 3=>8, 4=>9, 5=>9, 6=>10, 7=>10, 
                8=>11, 9=>11, 10=>11, 11=>12, 12=>12, 13=>13
            ];
            $tsl = $shuttleTable[$level] ?? 10;
            $vo2max = round(3.46 * ($level + ($shuttle / $tsl)) + 12.2, 2);
            $data['results']['VO2 Max'] = $vo2max;
            $data['vo2max'] = $vo2max;
        }
        
        $phys = PhysicalTest::updateOrCreate(
            ['member_id' => $request->member_id, 'year' => $request->year, 'month' => $request->month],
            $data
        );

        return response()->json(['success' => true, 'message' => 'Data fisik berhasil disimpan!']);
    }

    public function updatePhysicalTest(Request $request, $id)
    {
        $coach = Auth::user()->coach;
        if (!$coach) {
            return response()->json(['success' => false, 'message' => 'Hanya pelatih yang dapat mengupdate data fisik.'], 403);
        }

        $phys = PhysicalTest::where('coach_id', $coach->id)->findOrFail($id);
        $phys->update($request->all());

        return response()->json(['success' => true, 'message' => 'Data fisik berhasil diperbarui!']);
    }

    public function deletePhysicalTest($id)
    {
        $coach = Auth::user()->coach;
        if (!$coach) {
            return response()->json(['success' => false, 'message' => 'Hanya pelatih yang dapat menghapus data fisik.'], 403);
        }

        $phys = PhysicalTest::where('coach_id', $coach->id)->findOrFail($id);
        $phys->delete();

        return response()->json(['success' => true, 'message' => 'Data fisik berhasil dihapus!']);
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

    /**
     * ═══════════════════════════════════════════════════════════════
     * MEMBER MANAGEMENT (CRUD)
     * ═══════════════════════════════════════════════════════════════
     */

    public function storeMember(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'phone' => 'required|string|unique:users,phone',
            'gender' => 'required|in:MALE,FEMALE',
            'training_package_id' => 'required|exists:training_packages,id',

            'status' => 'required|in:AKTIF,TIDAK_AKTIF',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            return DB::transaction(function () use ($request) {
                $coach = Auth::user()->coach;

                // 1. Create User
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'phone' => $request->phone,
                    'gender' => $request->gender,
                ]);

                // 2. Assign Role (assuming role name is 'Member')
                $memberRole = \App\Models\Role::where('name', 'member')->first();
                if ($memberRole) {
                    $user->roles()->attach($memberRole);
                }

                // 3. Create Member Profile
                $member = Member::create([
                    'user_id' => $user->id,
                    'training_package_id' => $request->training_package_id,
                    'status' => $request->status,
                    'start_date' => now(),
                ]);

                // 4. Assign to Coach
                $member->coaches()->attach($coach->id);



                return response()->json([
                    'success' => true,
                    'message' => 'Member berhasil ditambahkan dan ditugaskan ke Anda.',
                    'member' => $member->load('user')
                ]);
            });
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menambahkan member: ' . $e->getMessage()], 500);
        }
    }

    public function updateMember(Request $request, $id)
    {
        $member = Member::findOrFail($id);
        $user = $member->user;

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'phone' => 'required|string|unique:users,phone,' . $user->id,
            'gender' => 'required|in:MALE,FEMALE',
            'training_package_id' => 'required|exists:training_packages,id',

            'status' => 'required|in:AKTIF,TIDAK_AKTIF',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            DB::transaction(function () use ($request, $member, $user) {
                $coach = Auth::user()->coach;

                // 1. Update User
                $userData = [
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'gender' => $request->gender,
                ];
                if ($request->filled('password')) {
                    $userData['password'] = Hash::make($request->password);
                }
                $user->update($userData);

                // 2. Update Member Profile
                $member->update([
                    'training_package_id' => $request->training_package_id,
                    'status' => $request->status,
                ]);


            });

            return response()->json([
                'success' => true,
                'message' => 'Member berhasil diperbarui.',
                'member' => $member->load('user')
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal memperbarui member: ' . $e->getMessage()], 500);
        }
    }

    public function deleteMember($id)
    {
        try {
            $member = Member::findOrFail($id);
            $user = $member->user;

            DB::transaction(function () use ($member, $user) {

                // Remove coach assignments
                $member->coaches()->detach();
                // Delete member profile
                $member->delete();
                // Delete user (optional, depending on business rule)
                if ($user) {
                    $user->delete();
                }
            });

            return response()->json([
                'success' => true,
                'message' => 'Member berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menghapus member: ' . $e->getMessage()], 500);
        }
    }
}