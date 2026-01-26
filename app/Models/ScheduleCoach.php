<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScheduleCoach extends Model
{
    protected $table = 'schedule_coaches';

    protected $fillable = [
        'registration_schedule_id',
        'coach_id',
        'quota',
        'quota_used'
    ];

    public function schedule()
    {
        return $this->belongsTo(
            RegistrationSchedule::class,
            'registration_schedule_id',
            'id'
        );
    }

    public function coach()
    {
        return $this->belongsTo(Coach::class, 'coach_id');
    }
    
    public function submissions()
    {
        return $this->hasMany(RegistrationSubmission::class);
    }

    public function getRemainingQuotaAttribute()
    {
        return $this->quota - $this->quota_used;
    }
}

