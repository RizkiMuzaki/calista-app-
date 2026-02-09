<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoryChoice extends Model
{
    protected $table = 'story_choices';

    protected $fillable = [
        'story_page_id',
        'choice_text',
        'next_page_number',
    ];

    public function storyPage()
    {
        return $this->belongsTo(StoryPage::class);
    }
}
