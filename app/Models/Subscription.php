<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Subscription extends Model
{
    protected $table = 'subscriptions';

    protected $fillable = [
        'user_id',
        'plan_id',
        'tanggal_mulai',
        'tanggal_berakhir',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    // Cek apakah subscription aktif
    public function isActive()
    {
        return $this->status === 'aktif' && 
               Carbon::now()->between(
                   Carbon::parse($this->tanggal_mulai),
                   Carbon::parse($this->tanggal_berakhir)
               );
    }

    // Scope untuk subscription aktif
    public function scopeActive($query)
    {
        return $query->where('status', 'aktif')
                    ->where('tanggal_mulai', '<=', Carbon::now())
                    ->where('tanggal_berakhir', '>=', Carbon::now());
    }
}