<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PhysicalTest extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id', 'coach_id', 'year', 'month', 'vo2max', 
        'bleep_level', 'bleep_shuttle', 'sprint_20m', 'push_up', 
        'sit_up', 'shuttle_run', 'v_sit_reach', 'run_300m', 'note'
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function coach(): BelongsTo
    {
        return $this->belongsTo(Coach::class);
    }

    protected static function booted()
    {
        static::saving(function ($test) {
            if ($test->bleep_level && $test->bleep_shuttle) {
                // Standar total shuttle per level (Standard MSFT)
                $shuttleTable = [
                    1=>9, 2=>8, 3=>8, 4=>9, 5=>9, 6=>10, 7=>10, 
                    8=>11, 9=>11, 10=>11, 11=>12, 12=>12, 13=>13
                ];
                $tsl = $shuttleTable[$test->bleep_level] ?? 10;
                
                // Rumus: 3.46 * (Level + (Shuttle/TotalShuttleLevel)) + 12.2
                $test->vo2max = round(3.46 * ($test->bleep_level + ($test->bleep_shuttle / $tsl)) + 12.2, 2);
            }
        });
    }
}