<?php

namespace App\Filament\Resources\StoryPageResource\Pages;

use App\Filament\Resources\StoryPageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStoryPages extends ListRecords
{
    protected static string $resource = StoryPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
