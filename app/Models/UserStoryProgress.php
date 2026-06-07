<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserStoryProgress extends Model
{
    protected $table = 'user_story_progress';

    protected $fillable = [
        'user_id',
        'story_id',
        'last_position_seconds',
        'completed',
        'completed_at',
    ];

    protected $casts = [
        'last_position_seconds' => 'double',
        'completed' => 'boolean',
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function story()
    {
        return $this->belongsTo(Story::class);
    }
}
