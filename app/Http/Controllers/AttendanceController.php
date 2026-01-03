<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Coach;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AttendanceController extends Controller
{
    /**
     * Simpan data absensi baru.
     */
    public function index()
    {
        // 1. Data Coach & Jadwalnya untuk dropdown di Modal
        $coach = Coach::with('trainingSchedules')->where('user_id', Auth::id())->firstOrFail();

        // 2. Data SEMUA Member untuk fitur Search di Modal
        // Kita ambil id, nama (lewat user), dan kategori
        $allMembers = Member::with('user:id,name')->get();

        // 3. Data Riwayat Absensi untuk Tabel
        $attendances = Attendance::where('coach_id', $coach->id)
                        ->with(['schedule', 'members'])
                        ->withCount('members')
                        ->latest()
                        ->get();

        return view('coach.dashboard', compact('coach', 'allMembers', 'attendances'));
    }
    public function store(Request $request)
    {
        // 1. Validasi
        $request->validate([
            'date' => 'required|date',
            'time' => 'required', // Jam latihan aktual
            'schedule_id' => 'nullable|exists:training_schedules,id',
            'place' => 'required|string|max:255',
            'members' => 'nullable|array',
            'notes' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
        ]);

        $coach = Coach::where('user_id', Auth::id())->firstOrFail();

        // 2. Handle Foto
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('attendance_photos', 'public');
        }

        try {
            DB::beginTransaction();

            // 3. Simpan Header Absensi
            $attendance = Attendance::create([
                'coach_id'    => $coach->id,
                'schedule_id' => $request->schedule_id, // Bisa null jika luar jadwal
                'date'        => $request->date,
                'time'        => $request->time,     // Jam aktual
                'place'       => $request->place,
                'notes'       => $request->notes,    // Catatan tambahan
                'photo_path'  => $photoPath,
            ]);

            // 4. Sinkronisasi Member (Absensi Lintas Murid)
            if ($request->has('members')) {
                $attendance->members()->sync($request->members);
            }

            DB::commit();
            return back()->with('success', 'Absensi berhasil dicatat!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal simpan absensi: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
    }
}