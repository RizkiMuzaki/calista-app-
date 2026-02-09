<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Artikel extends Model
{
    use HasFactory;

    protected $table = 'artikels';

    protected $fillable = [
        'title',
        'slug',
        'content',
        'image',
        'is_published',
        'user_id'
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($article) {
            $article->slug = Str::slug($article->title);
        });
        
        static::updating(function ($article) {
            $article->slug = Str::slug($article->title);
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
