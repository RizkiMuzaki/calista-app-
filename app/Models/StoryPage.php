<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StoryPage extends Model
{
    protected $fillable = [
        'story_id',
        'page_number',
        'story_text',
        'start_time',
        'end_time',
        'animation_trigger_state'
    ];

    protected $casts = [
        'page_number' => 'integer',
        'start_time' => 'double',
        'end_time' => 'double',
    ];

    public function story(): BelongsTo
    {
        return $this->belongsTo(Story::class);
    }
}