<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegistrationSchedule extends Model
{
    protected $table = 'registration_schedules';

    protected $fillable = [
        'registration_form_id', 
        'schedule_group', 
        'location',        
        'day', 
        'time', 
        'date'
    ];

    public function form()
    {
        return $this->belongsTo(
            RegistrationForm::class,
            'registration_form_id', // FK di tabel ini
            'id'                    // PK di parent
        );
    }


    public function coaches()
    {
        return $this->hasMany(ScheduleCoach::class);
    }

    
}

