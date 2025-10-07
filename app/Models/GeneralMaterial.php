<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralMaterial extends Model
{
    use HasFactory;

    /**
     * Nama tabel (opsional, kalau sesuai konvensi bisa di-skip)
     */
    protected $table = 'general_materials';

    /**
     * Kolom yang boleh diisi (mass assignable)
     */
    protected $fillable = [
        'title',
        'description',
        'file_path',
        'uploaded_at',
    ];

    /**
     * Casting kolom ke tipe data tertentu
     */
    protected $casts = [
        'uploaded_at' => 'datetime',
    ];

    /**
     * Accessor untuk ambil URL file (kalau kamu simpan path di storage)
     */
    public function getFileUrlAttribute(): ?string
    {
        return $this->file_path
            ? asset('storage/' . $this->file_path)
            : null;
    }
}
