<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * 🎓 LEARNING: Model CharacterItem
 * 
 * Merepresentasikan satu item baju yang bisa dipakai Nusa.
 * Setiap baju punya tipe unlock: free, reward, atau premium.
 * 
 * Free = Semua anak dapat gratis saat daftar.
 * Reward = Unlock otomatis setelah capai milestone belajar.
 * Premium = Hanya untuk subscriber CALISTA Premium.
 */
class CharacterItem extends Model
{
    use HasFactory;

    protected $table = 'character_items';

    protected $fillable = [
        'name',
        'description',
        'image_url',
        'unlock_type',
        'reward_condition',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    // ========== RELATIONSHIPS ==========

    /**
     * Anak-anak yang memiliki item ini.
     */
    public function childItems()
    {
        return $this->hasMany(ChildItem::class);
    }

    // ========== SCOPES ==========

    /**
     * Scope: Hanya item yang aktif.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Item gratis.
     */
    public function scopeFree($query)
    {
        return $query->where('unlock_type', 'free');
    }

    /**
     * Scope: Item reward (unlock dari belajar).
     */
    public function scopeReward($query)
    {
        return $query->where('unlock_type', 'reward');
    }

    /**
     * Scope: Item premium (hanya subscriber).
     */
    public function scopePremium($query)
    {
        return $query->where('unlock_type', 'premium');
    }

    // ========== HELPERS ==========

    /**
     * Cek apakah item ini gratis.
     */
    public function isFree(): bool
    {
        return $this->unlock_type === 'free';
    }

    /**
     * Cek apakah item ini reward.
     */
    public function isReward(): bool
    {
        return $this->unlock_type === 'reward';
    }

    /**
     * Cek apakah item ini premium.
     */
    public function isPremium(): bool
    {
        return $this->unlock_type === 'premium';
    }
}
