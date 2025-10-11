<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    use HasFactory;

    protected $fillable = [
        'coach_id',
        'amount',
        'month',
        'status',
        'paid_at',
    ];

    /**
     * Relasi ke Coach
     * One Salary belongs to one Coach
     */
    public function coach()
    {
        return $this->belongsTo(Coach::class);
    }
}
