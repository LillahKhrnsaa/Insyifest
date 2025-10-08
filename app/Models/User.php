<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Filament\Panel;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'birth_date',
        'gender',
        'password',
        'photo_path',
        'active',
        'role',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'password' => 'hashed',
    ];

    // PENTING: Tambahkan ini agar Filament bisa load file di form edit
    protected $appends = ['photo_url'];

    public function setRoleAttribute($value)
    {
        if ($value) {
            $this->syncRoles([$value]);
        }
    }

    public function getRoleAttribute()
    {
        return $this->roles->pluck('name')->first();
    }

    // Accessor untuk mendapatkan URL lengkap (untuk display di tempat lain)
    public function getPhotoUrlAttribute(): ?string
    {
        if ($this->photo_path && Storage::disk('public')->exists($this->photo_path)) {
            // Paksa pakai APP_URL dari config agar konsisten
            return config('app.url') . Storage::url($this->photo_path);
        }

        return null;
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return str_ends_with($this->email, '@cikampekswimming.gmail.com') 
            && (
                $this->hasRole('admin') || 
                $this->hasRole('coach') || 
                $this->hasRole('member') || 
                $this->hasRole('staff') || 
                $this->hasRole('owner')
            );
    }
}