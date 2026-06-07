<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * 🎓 LEARNING: Mood Model
 * 
 * Model Eloquent untuk tabel 'moods'.
 * - Mewakili 1 sesi check-in emosi anak per hari.
 * - Relasi: Mood (Many) → Anak (One).
 */
class Mood extends Model
{
    use HasFactory;

    protected $table = 'moods';

    protected $fillable = [
        'anak_id',
        'mood_type',
        'catatan',
    ];

    // 🎓 LEARNING: cast → otomatis konversi tipe data
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ===== RELATIONSHIPS =====

    /**
     * Mood dimiliki oleh satu Anak.
     */
    public function anak()
    {
        return $this->belongsTo(Anak::class, 'anak_id');
    }

    // ===== SCOPES =====

    /**
     * Scope: Filter mood hari ini saja, berdasarkan anak.
     * 
     * 🎓 LEARNING: Local Scope membuat query lebih readable.
     * Usage: Mood::todayFor($childId)->exists()
     */
    public function scopeTodayFor($query, int $childId)
    {
        $start = Carbon::now('Asia/Jakarta')->startOfDay()->timezone('UTC');
        $end = Carbon::now('Asia/Jakarta')->endOfDay()->timezone('UTC');

        return $query->where('anak_id', $childId)
            ->whereBetween('created_at', [$start, $end]);
    }
}
