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
    ];

    // Relasi ke coach
    public function coach()
    {
        return $this->belongsTo(Coach::class);
    }

    // Ambil jumlah member dari pivot table
    public function getMemberCountAttribute()
    {
        return $this->coach
            ? $this->coach->members()->count()
            : 0;
    }

    // Hitung total otomatis (optional)
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
