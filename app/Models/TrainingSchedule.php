<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
     * Relasi: satu jadwal bisa dimiliki banyak coach
     */
    public function coaches(): BelongsToMany
    {
        return $this->belongsToMany(
            Coach::class, 
            'coach_training_schedule', 
            'training_schedule_id', 
            'coach_id'
        )->withTimestamps();
    }

    public function coach(): BelongsTo
    {
        // 'coach_id' adalah foreign key di tabel training_schedules
        return $this->belongsTo(Coach::class, 'coach_id');
    }
}
