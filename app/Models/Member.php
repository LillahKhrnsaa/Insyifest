<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Models\PaymentHistory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Member extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'training_package_id',
        'status',
        'start_date',
        'end_date',
    ];

     protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    /**
     * Get the user that owns the member profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the training package associated with the member.
     */
    public function trainingPackage(): BelongsTo
    {
        return $this->belongsTo(TrainingPackage::class);
    }

    public function paymentHistories(): HasMany
    {
        return $this->hasMany(PaymentHistory::class);
    }

    protected function userName(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->user->name ?? 'N/A',
        );
    }

    public function assignedCoaches(): BelongsToMany
    {
        return $this->belongsToMany(
            Coach::class, 
            'member_training_assignments', 
            'member_id', 
            'coach_id'
        )->withTimestamps();
    }

    public function coaches(): BelongsToMany
    {
        return $this->belongsToMany(
            Coach::class,
            'member_training_assignments',
            'member_id',
            'coach_id'
        )->withTimestamps();
    }

    public function coachesSalary()
    {
        return $this->belongsToMany(Coach::class, 'member_training_assignments')
                    ->withTimestamps();
    }

}
