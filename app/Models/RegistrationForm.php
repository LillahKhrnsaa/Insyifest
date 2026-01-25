<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegistrationForm extends Model
{
    protected $table = 'registration_forms';

    protected $fillable = [
        'title', 
        'slug', 
        'description', 
        'is_active'
    ];

    public function fields()
    {
        return $this->hasMany(RegistrationField::class)->orderBy('order');
    }

    public function schedules()
    {
        return $this->hasMany(RegistrationSchedule::class);
    }

    public function submissions()
    {
        return $this->hasMany(RegistrationSubmission::class);
    }
}
