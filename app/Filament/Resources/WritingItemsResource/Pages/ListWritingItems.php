<?php

namespace App\Filament\Resources\WritingItemsResource\Pages;

use App\Filament\Resources\WritingItemsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWritingItems extends ListRecords
{
    protected static string $resource = WritingItemsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
