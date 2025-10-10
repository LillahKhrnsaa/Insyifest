<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;


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
     * Relasi: satu jadwal bisa dimiliki banyak coach
     */
    public function coaches(): HasMany
    {
        return $this->hasMany(Coach::class, 'training_schedule_id');
    }
}
