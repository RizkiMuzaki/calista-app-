<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiCreditUsage extends Model
{
    protected $fillable = [
        'user_id',
        'plan_code',
        'feature',
        'credits',
        'characters',
        'period_start',
        'metadata',
    ];

    protected $casts = [
        'credits' => 'integer',
        'characters' => 'integer',
        'period_start' => 'date',
        'metadata' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
