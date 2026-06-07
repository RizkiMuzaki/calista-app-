<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Support\Str;

class Story extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'full_script',
        'rating',
        'age_group',
        'duration',
        'is_active',
        'is_premium',
        'order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_premium' => 'boolean',
        'rating' => 'double',
        'order' => 'integer',
    ];

    public function pages(): HasMany
    {
        return $this->hasMany(StoryPage::class)->orderBy('page_number');
    }

    public static function booted()
    {
        static::creating(function (Story $story) {
            if (empty($story->slug)) {
                $story->slug = static::generateUniqueSlug($story->title);
            }
        });

        static::saving(function (Story $story) {
            if (empty($story->slug) && !empty($story->title)) {
                $story->slug = static::generateUniqueSlug($story->title, $story->id ?? null);
            }
        });
    }

    protected static function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title ?: 'story');
        $slug = $base;
        $i = 1;

        while (static::where('slug', $slug)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }
}
