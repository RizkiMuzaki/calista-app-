<?php

namespace App\Filament\Resources\PuzzleItemResource\Pages;

use App\Filament\Resources\PuzzleItemResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPuzzleItem extends EditRecord
{
    protected static string $resource = PuzzleItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
