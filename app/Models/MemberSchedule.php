<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberSchedule extends Model
{
    protected $table = 'member_schedules';

    protected $fillable = [
        'member_id',
        'coach_id',
        'training_schedule_id',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function coach()
    {
        return $this->belongsTo(Coach::class);
    }

    public function trainingSchedule()
    {
        return $this->belongsTo(TrainingSchedule::class);
    }
}
