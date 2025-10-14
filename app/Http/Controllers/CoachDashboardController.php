<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Coach;
use Illuminate\Support\Facades\Auth;

class CoachDashboardController extends Controller
{
    public function index()
    {
        // Ambil user yang sedang login
        $user = Auth::user();

        // Pastikan dia punya relasi coach
        $coach = Coach::with([
            'user',                          // profil user
            'members.user',                  // member yang dilatih beserta user-nya
            'trainingSchedules',             // jadwal latihan
        ])->where('user_id', $user->id)->firstOrFail();

        // Hitung statistik cepat
        $totalMembers = $coach->members->count();
        $activeMembers = $coach->members->where('status', 'AKTIF')->count();
        $inactiveMembers = $coach->members->where('status', 'TIDAK_AKTIF')->count();
        $totalSchedules = $coach->trainingSchedules->count();

        return view('filament.pages.coach-dashboard', compact(
            'coach',
            'totalMembers',
            'activeMembers',
            'inactiveMembers',
            'totalSchedules'
        ));
    }
}
