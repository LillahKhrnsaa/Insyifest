<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Coach;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AttendanceController extends Controller
{
    /**
     * Simpan data absensi baru.
     */
    public function store(Request $request)
    {
        // 1. Validasi input dari form modal
        $request->validate([
            'date' => 'required|date',
            'schedule_id' => 'required|exists:training_schedules,id',
            'place' => 'nullable|string|max:255',
            'members' => 'nullable|array', // Member reguler
            'members.*' => 'exists:members,id',
            'extra_members' => 'nullable|array', // Member tambahan
            'extra_members.*' => 'exists:members,id',
            'photo' => 'nullable|image|max:2048', // Foto (maks 2MB)
        ]);

        // 2. Dapatkan data coach yang sedang login
        $coach = Coach::where('user_id', Auth::id())->firstOrFail();

        // 3. Handle upload foto (jika ada)
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('attendance_photos', 'public');
        }

        // 4. Gabungkan semua member yang hadir
        $regularMembers = $request->input('members', []);
        $extraMembers = $request->input('extra_members', []);
        $allAttendingMemberIds = array_merge($regularMembers, $extraMembers);

        // 5. Gunakan Transaksi Database (agar aman)
        try {
            DB::beginTransaction();

            // Buat 'Attendance' (Header)
            $attendance = Attendance::create([
                'coach_id' => $coach->id,
                'schedule_id' => $request->schedule_id,
                'date' => $request->date,
                'place' => $request->place,
                'photo_path' => $photoPath,
            ]);

            // Sinkronkan semua member ke tabel pivot 'attendance_members'
            if (!empty($allAttendingMemberIds)) {
                $attendance->members()->sync($allAttendingMemberIds);
            }

            DB::commit(); // Simpan semua perubahan
            
        } catch (\Exception $e) {
            DB::rollBack(); // Batalkan jika ada error
            Log::error('Gagal simpan absensi: ' . $e->getMessage()); // Catat error
            return back()->with('error', 'Terjadi kesalahan, absensi gagal disimpan.');
        }

        // 6. Redirect kembali ke dashboard
        return back()->with('success', 'Absensi berhasil dicatat!');
    }
}