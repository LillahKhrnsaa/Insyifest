<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegistrationSubmission extends Model
{
    protected $table = 'registration_submissions';

    protected $fillable = [
        'registration_form_id',
        'registration_schedule_id',
        'schedule_coach_id'
    ];

    public function answers()
    {
        return $this->hasMany(RegistrationAnswer::class);
    }

    public function scheduleCoach()
    {
        return $this->belongsTo(ScheduleCoach::class);
    }

    public function schedule()
    {
        return $this->belongsTo(RegistrationSchedule::class);
    }

    public function form()
    {
        return $this->belongsTo(RegistrationForm::class);
    }
}

