<?php

namespace App\Filament\Resources\WritingItemsResource\Pages;

use App\Filament\Resources\WritingItemsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWritingItems extends EditRecord
{
    protected static string $resource = WritingItemsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
