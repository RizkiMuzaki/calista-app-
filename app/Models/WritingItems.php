<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class writingItems extends Model
{
    protected $table = 'writing_items';
    protected $fillable = ['level_id', 'text', 'image_path', 'audio_path', 'type'];

    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    
}
