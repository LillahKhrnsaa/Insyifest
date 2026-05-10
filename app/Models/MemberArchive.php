<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberArchive extends Model
{
    protected $fillable = [
        'archive_period',
        'member_id',
        'user_id',
        'name',
        'email',
        'phone',
        'training_package_name',
        'coach_name',
        'coach_id',
        'training_day',
        'training_time',
        'training_day_index',
        'status',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
