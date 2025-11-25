<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Raport extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terkait dengan model.
     *
     * @var string
     */
    protected $table = 'raports';

    /**
     * Kolom yang dapat diisi secara massal (mass assignable).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'gaya',
        'coach_id',
        'member_id',
        'year',
        'month',
        'note',
        'value', // Waktu tempuh (detik)
        'volume',
        'intensity',
        'peaking',
    ];

    /**
     * Kolom yang harus di-cast ke tipe data tertentu.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'value'     => 'double',    // Untuk waktu
        'intensity' => 'double',    // Untuk intensitas
        'volume'    => 'integer',
        'peaking'    => 'integer',
        'year'      => 'integer',
    ];

    // ----------------------------------------------------------------------
    // RELATIONS
    // ----------------------------------------------------------------------

    /**
     * Relasi many-to-one ke Model Coach.
     */
    public function coach(): BelongsTo
    {
        return $this->belongsTo(Coach::class);
    }

    /**
     * Relasi many-to-one ke Model Member.
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    // ----------------------------------------------------------------------
    // ACCESSORS (Opsional: Memudahkan Tampilan Waktu)
    // ----------------------------------------------------------------------

    /**
     * Accessor opsional untuk mengubah waktu (detik) menjadi format Menit:Detik.
     * * Contoh penggunaan di Blade: $raport->formatted_value
     *
     * @return string|null
     */
    public function getFormattedValueAttribute(): ?string
    {
        if (is_null($this->value)) {
            return null;
        }

        $totalSeconds = (float) $this->value;
        $minutes = floor($totalSeconds / 60);
        $seconds = $totalSeconds - ($minutes * 60);

        // Format: MM:SS.ms (misal: 01:23.45)
        return sprintf('%02d:%05.2f', $minutes, $seconds);
    }
}