<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegistrationField extends Model
{
    protected $table = 'registration_fields';

    protected $fillable = [
        'registration_form_id',
        'label',
        'name',
        'type',
        'is_required',
        'options',
        'order'
    ];

    protected $casts = [
        'options' => 'array',
        'is_required' => 'boolean'
    ];

    public function form()
    {
        return $this->belongsTo(RegistrationForm::class);
    }

}
