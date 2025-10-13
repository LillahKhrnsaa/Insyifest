<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TrainingSchedule;
use App\Models\TrainingPackage;
use App\Models\GeneralMaterial;
use App\Models\Member;
use App\Models\Coach;

class LandingController extends Controller
{
    /**
     * Menampilkan halaman landing page utama.
     */
    public function index()
    {
        // KETERANGAN: Mengubah 'coach.user' menjadi 'coaches.user'
        // Ini memuat relasi jamak 'coaches' yang baru saja kita definisikan.
        $schedules = TrainingSchedule::with('coaches.user')->get();

        // Mengambil semua Paket Latihan
        $packages = TrainingPackage::orderBy('price')->get();

        // Mengambil Postingan (General Materials) terbaru
        $posts = GeneralMaterial::latest()->take(5)->get();

        // Mengambil daftar member aktif
        $members = Member::with('user')->where('status', 'AKTIF')->latest()->take(12)->get();
        
        // Mengambil semua data Pelatih
        $coaches = Coach::with('user')->get();

        // Kirim semua data yang sudah diambil ke view 'welcome'
        return view('welcome', compact('schedules', 'packages', 'posts', 'members', 'coaches'));
    }
}
