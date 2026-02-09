<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hadiah extends Model
{
    /** @use HasFactory<\Database\Factories\HadiahFactory> */
    use HasFactory;

    protected $table = 'hadiahs';
    protected $fillable = [
        'game_id',
        'nama_hadiah',
        'jenis_hadiah',
        'foto',
        'audio',
    ];

    public function game()
    {
        return $this->belongsTo(Game::class);
    }
}
