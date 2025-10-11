<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TrainingSchedule;
use App\Models\TrainingPackage;
use App\Models\GeneralMaterial;
use App\Models\Member;
use App\Models\User; // Pastikan model User di-import

class LandingController extends Controller
{
    /**
     * Menampilkan halaman landing page utama.
     */
    public function index()
    {
        // 1. Mengambil Jadwal Latihan beserta data Coach dan User-nya
        // Eager loading 'coach.user' untuk efisiensi query
        $schedules = TrainingSchedule::with('coach.user')->orderBy('day')->get();

        // 2. Mengambil semua Paket Latihan
        $packages = TrainingPackage::orderBy('price')->get();

        // 3. Mengambil Postingan (General Materials) terbaru (misal: 5 terakhir)
        $posts = GeneralMaterial::latest()->take(5)->get();

        // 4. Mengambil daftar member aktif (misal: 12 member terbaru untuk galeri foto)
        // Kita hanya mengambil User yang rolenya bukan coach/admin jika ada role
        // Untuk sekarang kita ambil dari tabel members saja
        $members = Member::with('user')->where('status', 'AKTIF')->latest()->take(12)->get();


        // Kirim semua data yang sudah diambil ke view 'welcome'
        return view('welcome', compact('schedules', 'packages', 'posts', 'members'));
    }
}