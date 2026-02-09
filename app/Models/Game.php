<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    /** @use HasFactory<\Database\Factories\GameFactory> */
    use HasFactory;

    protected $table = 'games';

    protected $fillable = [
        'foto',
        'nama_game',
        'status',
    ];

    public function hadiahs()
    {
        return $this->hasMany(Hadiah::class);
    }
}
