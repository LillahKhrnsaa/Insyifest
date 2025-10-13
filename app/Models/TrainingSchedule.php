<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TrainingSchedule extends Model
{
    use HasFactory;

    protected $table = 'training_schedules';

    protected $fillable = [
        'day',
        'time',
        'place',
    ];

    /**
     * KETERANGAN: Ini adalah SATU-SATUNYA relasi ke Coach yang benar.
     * Relasi Many-to-Many ke Coach melalui tabel pivot.
     * Satu jadwal latihan bisa memiliki beberapa pelatih.
     */
    public function coaches(): BelongsToMany
    {
        return $this->belongsToMany(Coach::class, 'coach_training_schedule')->withTimestamps();
    }
}
