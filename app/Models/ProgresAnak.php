<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $anak_id
 * @property int $level_id
 * @property int $score
 * @property int $bintang
 * @property bool $selesai
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class ProgresAnak extends Model
{
    protected $table = 'progres_anaks';

    protected $fillable = [
        'anak_id',
        'level_id',
        'score',
        'current_score',
        'bintang',
        'selesai',
        'current_item',
        'total_items',
        'mistakes',
        'lives_remaining',
        'duration_seconds',
        'last_played_at',
        'metadata',
    ];

    protected $casts = [
        'selesai' => 'boolean',
        'last_played_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function anak()
    {
        return $this->belongsTo(Anak::class);
    }

    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    public function module()
    {
        return $this->belongsTo(Level::class, 'level_id', 'id')
            ->select('levels.id', 'levels.module_id')
            ->with('module:id,name');
    }
}
