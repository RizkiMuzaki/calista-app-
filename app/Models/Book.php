<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Book extends Model
{
    protected $table = 'books';
    protected $fillable = ['module_id', 'title', 'slug', 'order', 'is_active', 'is_premium', 'cover_image', 'description', 'type'];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    // NEW: canonical relation name used across controllers/views
    public function pages()
    {
        return $this->hasMany(PageBook::class);
    }

    // keep existing name for backward compatibility
    public function pageBooks()
    {
        return $this->pages();
    }

    public static function booted()
    {
        static::creating(function (Book $book) {
            if (empty($book->slug)) {
                $book->slug = static::generateUniqueSlug($book->title);
            }
        });

        static::saving(function (Book $book) {
            // ensure slug exists when saving if empty (covers manual saves)
            if (empty($book->slug) && ! empty($book->title)) {
                $book->slug = static::generateUniqueSlug($book->title, $book->id ?? null);
            }
        });
    }

    protected static function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title ?: 'book');
        $slug = $base;
        $i = 1;

        while (static::where('slug', $slug)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }

    public function storyPages()
    {
        return $this->hasMany(StoryPage::class);
    }

    
}

