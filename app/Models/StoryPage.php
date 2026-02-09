<?php
// app/Models/StoryPage.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoryPage extends Model
{
protected $table = 'story_pages';

protected $fillable = [
    'book_id',
    'page_number',
    'story_text',
    'audio_path',
    'question',
    'duration',
    'is_active',
];

protected $appends = ['full_image_paths'];

public function book()
{
    return $this->belongsTo(Book::class);
}

public function images()
{
    return $this->hasMany(StoryImage::class, 'story_page_id');
}

public function choices()
{
    return $this->hasMany(StoryChoice::class, 'story_page_id');
}

// Getter untuk full image URLs
public function getFullImagePathsAttribute()
{
    if (!$this->relationLoaded('images')) {
        return [];
    }
    
    return $this->images->map(function ($image) {
        return [
            'id' => $image->id,
            'type' => $image->type,
            'order' => $image->order,
            'start_second' => $image->start_second,
            'end_second' => $image->end_second,
            'image_path' => $image->image_path,
            'full_url' => asset('storage/' . $image->image_path),
            'storage_path' => storage_path('app/public/' . $image->image_path),
        ];
    });
}
}