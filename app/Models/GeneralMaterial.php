<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class GeneralMaterial extends Model
{
    use HasFactory;

    protected $table = 'general_materials';

    protected $fillable = [
        'title',
        'description',
        'file_path',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // HAPUS $appends karena bikin CORS issue
    // FileUpload Filament baca langsung dari 'file_path', bukan 'file_url'
    // protected $appends = ['file_url'];

    /**
     * Accessor untuk ambil URL file lengkap (untuk display di tempat lain)
     */
    public function getFileUrlAttribute(): ?string
    {
        if ($this->file_path && Storage::disk('public')->exists($this->file_path)) {
            return Storage::url($this->file_path);
        }
        return null;
    }
}