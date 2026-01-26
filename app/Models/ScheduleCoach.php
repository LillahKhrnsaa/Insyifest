<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

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

    // Model ScheduleCoach
    public function getRemainingQuotaAttribute()
    {
        Log::info('ACCESSOR CALLED', [
            'id' => $this->id,
            'quota' => $this->quota,
            'quota_used' => $this->quota_used,
            'remaining' => $this->quota - $this->quota_used,
        ]);
        
        return $this->quota - $this->quota_used;
    }
}

