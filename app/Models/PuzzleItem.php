<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PuzzleItem extends Model
{
    protected $table = 'puzzle_items';
    protected $fillable = ['level_id', 'title', 'image', 'grid_size', 'order_number', 'is_active'];

    public function level()
    {
        return $this->belongsTo(Level::class);
    }
}
