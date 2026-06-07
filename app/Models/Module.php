<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $table = 'modules';
    protected $fillable = ['foto', 'name', 'type', 'slug'];

    public function levels()
    {
        return $this->hasMany(Level::class);
    }



    public function playSessions()
    {
        return $this->hasMany(PlaySession::class);
    }
}
