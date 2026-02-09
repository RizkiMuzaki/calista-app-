<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    protected $table = 'levels';
    protected $fillable = ['module_id', 'order_number', 'title'];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function writingItems()
    {
        return $this->hasMany(WritingItems::class);
    }

    public function countingItems()
    {
        return $this->hasMany(CountingItem::class);
    }

    public function puzzleItems()
    {
        return $this->hasMany(PuzzleItem::class);
    }

    public function progresAnaks()
    {
        return $this->hasMany(ProgresAnak::class);
    }
}
