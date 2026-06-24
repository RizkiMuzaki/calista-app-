<?php

namespace App\Models;

/**
 * @property int $id
 * @property int $user_id
 * @property int $anak_id
 * @property int|null $level_id
 * @property int|null $module_id
 * @property string $session_uuid
 * @property string $source
 * @property string $module_slug
 * @property string $module_name
 * @property string $level_title
 * @property string $status
 * @property int $score
 * @property int $current_score
 * @property int $bintang
 * @property int $current_item
 * @property int $total_items
 * @property int $mistakes
 * @property int $lives_remaining
 * @property int $duration_seconds
 * @property \Illuminate\Support\Carbon|null $started_at
 * @property \Illuminate\Support\Carbon|null $ended_at
 * @property \Illuminate\Support\Carbon|null $played_on
 * @property array|null $metadata
 */
class PlaySession extends Model
{
    protected $fillable = [
        'user_id',
        'anak_id',
        'level_id',
        'module_id',
        'session_uuid',
        'source',
        'module_slug',
        'module_name',
        'level_title',
        'status',
        'score',
        'current_score',
        'bintang',
        'current_item',
        'total_items',
        'mistakes',
        'lives_remaining',
        'duration_seconds',
        'started_at',
        'ended_at',
        'played_on',
        'metadata',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'played_on' => 'date',
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
        return $this->belongsTo(Module::class);
    }
}
