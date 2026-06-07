<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    protected $table = 'levels';
    protected $fillable = ['module_id', 'activity_type', 'order_number', 'title', 'local_level_id'];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function progresAnaks()
    {
        return $this->hasMany(ProgresAnak::class);
    }

    public function playSessions()
    {
        return $this->hasMany(PlaySession::class);
    }
}
