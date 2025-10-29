<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TrainingPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'description',
    ];

    public function members(): HasMany
    {
        return $this->hasMany(Member::class);
    }

    /**
     * Riwayat absensi yang terkait dengan jadwal ini.
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'schedule_id');
    }
}
