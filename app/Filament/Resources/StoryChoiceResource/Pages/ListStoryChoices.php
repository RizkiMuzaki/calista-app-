<?php

namespace App\Filament\Resources\StoryChoiceResource\Pages;

use App\Filament\Resources\StoryChoiceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStoryChoices extends ListRecords
{
    protected static string $resource = StoryChoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
