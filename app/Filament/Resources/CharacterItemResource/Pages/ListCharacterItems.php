<?php

namespace App\Filament\Resources\CharacterItemResource\Pages;

use App\Filament\Resources\CharacterItemResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCharacterItems extends ListRecords
{
    protected static string $resource = CharacterItemResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
