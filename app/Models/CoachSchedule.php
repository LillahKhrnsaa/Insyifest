<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoachSchedule extends Model
{
    protected $table = 'coach_training_schedule';

    protected $fillable = [
        'coach_id',
        'training_schedule_id',
        'quota',
    ];

    public function coach()
    {
        return $this->belongsTo(Coach::class);
    }

    public function trainingSchedule()
    {
        return $this->belongsTo(TrainingSchedule::class);
    }
}
