<?php

namespace App\Filament\Resources\StoryChoiceResource\Pages;

use App\Filament\Resources\StoryChoiceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStoryChoice extends EditRecord
{
    protected static string $resource = StoryChoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
