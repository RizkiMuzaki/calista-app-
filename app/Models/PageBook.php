<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PageBook extends Model
{
    protected $table = 'page_books';
    protected $fillable = ['book_id', 'page_number', 'audio_kata','image_path', 'audio_path', 'nama_benda', 'suku_kata', 'explanation', 'is_active', 'is_premium', 'type'];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
    public function getLettersAttribute()
    {
        $cleaned = preg_replace('/[^A-Z]/', '', strtoupper($this->nama_benda));
        return str_split($cleaned);
    }
    
    public function getLetterAudioUrlsAttribute()
    {
        $urls = [];
        foreach ($this->letters as $letter) {
            $path = "page_books/huruf/{$letter}.mp3";
            if (Storage::disk('public')->exists($path)) {
                $urls[$letter] = Storage::url($path);
            }
        }
        return $urls;
    }
}
