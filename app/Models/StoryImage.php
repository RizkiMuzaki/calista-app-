<?php
// app/Models/StoryImage.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoryImage extends Model
{
    protected $table = 'story_images';

    protected $fillable = [
        'story_page_id',
        'image_path',
        'type',
        'order',
        'start_second',
        'end_second',
        'is_active',
    ];

    protected $appends = ['full_url'];

    public function storyPage()
    {
        return $this->belongsTo(StoryPage::class);
    }

    // Getter untuk full URL
    public function getFullUrlAttribute()
    {
        if (!$this->image_path) {
            return null;
        }
        
        // Jika sudah full URL, return langsung
        if (filter_var($this->image_path, FILTER_VALIDATE_URL)) {
            return $this->image_path;
        }
        
        // Periksa apakah file ada di storage
        if (strpos($this->image_path, 'storage/') === 0) {
            return asset($this->image_path);
        }
        
        // Default: gunakan asset helper
        return asset('storage/' . $this->image_path);
    }

    // Method untuk memeriksa apakah file ada
    public function fileExists()
    {
        $path = $this->image_path;
        
        if (!$path) return false;
        
        // Jika sudah full path
        if (strpos($path, 'storage/') === 0) {
            $relativePath = str_replace('storage/', '', $path);
            $fullPath = storage_path('app/public/' . $relativePath);
        } else {
            $fullPath = storage_path('app/public/' . $path);
        }
        
        return file_exists($fullPath);
    }
}