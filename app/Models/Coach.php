<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
}