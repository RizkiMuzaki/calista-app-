<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $table = 'plans';

    // ✅ SESUAIKAN DENGAN DATABASE
    protected $fillable = [
        'nama_paket',
        'durasi_bulan',
        'harga_jual', // atau 'harga' tergantung database
        'deskripsi',
    ];

    protected $casts = [
        'durasi_bulan' => 'integer',
        'harga_jual' => 'decimal:2',
    ];

    // ✅ ACCESSOR untuk handle field yang mungkin berbeda
    public function getHargaJualAttribute()
    {
        // Jika di database namanya 'harga', gunakan accessor
        return $this->attributes['harga_jual'] ?? $this->attributes['harga'] ?? 0;
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}