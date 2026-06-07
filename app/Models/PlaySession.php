<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
