<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\User;

class Coach extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'bio',
    ];

    // Helper untuk dapetin nama coach
    public function getNameAttribute(): ?string
    {
        return $this->user?->name;
    }

    // Helper untuk dapetin email coach
    public function getEmailAttribute(): ?string
    {
        return $this->user?->email;
    }
    /**
     * Get the user that owns the coach profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function schedules(): BelongsToMany
    {
        return $this->belongsToMany(
            TrainingSchedule::class, 
            'coach_training_schedule', 
            'coach_id', 
            'training_schedule_id'
        )->withTimestamps();
    }

    public function salaries()
    {
        return $this->hasMany(Salary::class);
    }
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(
            Member::class,
            'member_training_assignments',
            'coach_id',
            'member_id'
        )->withTimestamps();
    }
}