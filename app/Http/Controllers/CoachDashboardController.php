<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Coach;
use App\Models\Member;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;

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
}