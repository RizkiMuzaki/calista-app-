<?php

namespace App\Filament\Resources\PuzzleItemResource\Pages;

use App\Filament\Resources\PuzzleItemResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPuzzleItems extends ListRecords
{
    protected static string $resource = PuzzleItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
