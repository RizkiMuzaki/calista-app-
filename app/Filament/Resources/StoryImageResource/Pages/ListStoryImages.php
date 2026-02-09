<?php

namespace App\Filament\Resources\StoryImageResource\Pages;

use App\Filament\Resources\StoryImageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStoryImages extends ListRecords
{
    protected static string $resource = StoryImageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
