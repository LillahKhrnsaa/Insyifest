<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegistrationAnswer extends Model
{
    protected $table = 'registration_answers';
    
    protected $fillable = [
        'registration_submission_id',
        'registration_field_id',
        'value'
    ];

    public function submission()
    {
        return $this->belongsTo(RegistrationSubmission::class);
    }

    public function field()
    {
        return $this->belongsTo(RegistrationField::class);
    }
}

