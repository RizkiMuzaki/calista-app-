<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * 🎓 LEARNING: Model ChildItem (Pivot Table)
 * 
 * Menghubungkan anak dengan baju yang dimilikinya.
 * Satu anak bisa punya banyak baju, tapi hanya 1 yang is_equipped = true.
 * 
 * Analogi: Ini seperti "lemari baju" anak.
 * - Ada banyak baju di lemari.
 * - Tapi cuma satu yang sedang dipakai.
 */
class ChildItem extends Model
{
    use HasFactory;

    protected $table = 'child_items';

    protected $fillable = [
        'anak_id',
        'character_item_id',
        'is_equipped',
        'unlocked_at',
    ];

    protected $casts = [
        'is_equipped' => 'boolean',
        'unlocked_at' => 'datetime',
    ];

    // ========== RELATIONSHIPS ==========

    /**
     * Anak yang memiliki item ini.
     */
    public function anak()
    {
        return $this->belongsTo(Anak::class);
    }

    /**
     * Detail item (nama, gambar, dll).
     */
    public function characterItem()
    {
        return $this->belongsTo(CharacterItem::class);
    }

    // ========== SCOPES ==========

    /**
     * Scope: Item yang sedang dipakai.
     */
    public function scopeEquipped($query)
    {
        return $query->where('is_equipped', true);
    }
}
