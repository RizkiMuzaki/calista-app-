<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgresAnak extends Model
{
    protected $table = 'progres_anaks';

    protected $fillable = [
        'anak_id',
        'level_id',
        'score',
        'bintang',
        'selesai',
    ];

    protected $casts = [
        'selesai' => 'boolean',
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
        return $this->belongsTo(Level::class, 'level_id', 'id')
            ->select('levels.id', 'levels.module_id')
            ->with('module:id,name');
    }
}
