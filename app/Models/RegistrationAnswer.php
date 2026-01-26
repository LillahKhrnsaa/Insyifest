<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegistrationAnswer extends Model
{
    protected $table = 'registration_answers';
    
    protected $fillable = [
        'registration_submission_id',
        'registration_field_id',
        'value'
    ];

    public function submission(): BelongsTo
    {
        return $this->belongsTo(RegistrationSubmission::class, 'registration_submission_id');
    }

    public function field(): BelongsTo
    {
        return $this->belongsTo(RegistrationField::class, 'registration_field_id');
    }
}

