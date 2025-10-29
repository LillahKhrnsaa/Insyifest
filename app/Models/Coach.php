<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany; // Ditambahkan untuk relasi Salary

class Coach extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'bio',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function schedules(): BelongsToMany
    {
        return $this->trainingSchedules();
    }
    /**
     * KETERANGAN: Ini adalah SATU-SATUNYA relasi ke TrainingSchedule yang benar.
     * Relasi Many-to-Many ke TrainingSchedule.
     * Seorang pelatih bisa memiliki banyak jadwal latihan.
     */
    public function trainingSchedules(): BelongsToMany
    {
        return $this->belongsToMany(TrainingSchedule::class, 'coach_training_schedule')->withTimestamps();
    }

    /**
     * Relasi ke Member melalui tabel pivot member_training_assignments.
     */
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(Member::class, 'member_training_assignments')->withTimestamps();
    }

    /**
     * Relasi ke Salary.
     */
    public function salaries(): HasMany
    {
        return $this->hasMany(Salary::class);
    }

    /**
     * Riwayat absensi yang telah dibuat oleh coach ini.
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }
}
