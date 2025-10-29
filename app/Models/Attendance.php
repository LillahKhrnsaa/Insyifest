<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    /**
     * Kolom yang boleh diisi massal.
     */
    protected $fillable = [
        'coach_id',
        'schedule_id',
        'date',
        'place',
        'photo_path',
    ];

    /**
     * Relasi ke Coach (pembuat absensi).
     */
    public function coach()
    {
        return $this->belongsTo(Coach::class);
    }

    /**
     * Relasi ke Jadwal (sesi latihan yang diabsen).
     */
    public function schedule()
    {
        return $this->belongsTo(TrainingSchedule::class, 'schedule_id');
    }

    /**
     * Relasi ke SEMUA member yang hadir di sesi ini.
     * Menggunakan 'attendance_members' sebagai tabel pivot.
     */
    public function members()
    {
        return $this->belongsToMany(Member::class, 'attendance_members');
    }
}