<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    use HasFactory;

    protected $fillable = [
        'coach_id',
        'training_sessions',
        'transport_fee',
        'additional_athletes',
        'per_meeting_fee',
        'per_member_fee',
        'health_fee',
        'bonus',
        'total_amount',
        'month',
        'status',
        'paid_at',
    ];

    protected $casts = [
        'paid_at' => 'date',
        'additional_athletes' => 'array',
    ];

    public function coach()
    {
        return $this->belongsTo(Coach::class);
    }

    public function getTotalMemberCountAttribute()
    {
        $originalCount = $this->coach ? $this->coach->members()->count() : 0;
        $additionalCount = is_array($this->additional_athletes) ? count($this->additional_athletes) : 0;
        
        return $originalCount + $additionalCount;
    }

    public function calculateTotal()
    {
        $memberCount = $this->getMemberCountAttribute();

        return
            ($this->training_sessions * $this->per_meeting_fee) +
            ($memberCount * $this->per_member_fee) +
            $this->transport_fee +
            $this->health_fee +
            $this->bonus;
    }
}
