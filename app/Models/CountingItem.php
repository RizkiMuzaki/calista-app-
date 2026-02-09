<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CountingItem extends Model
{
    protected $table = 'counting_items';
    protected $fillable = ['level_id', 'nama_objek', 'gambar_objek', 'jenis_operasi', 'nilai_kiri', 'nilai_kanan', 'hasil'];

    public function level()
    {
        return $this->belongsTo(Level::class);
    }
}
