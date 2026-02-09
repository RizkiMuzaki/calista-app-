<?php

namespace App\Filament\Resources\PageBookResource\Pages;

use App\Filament\Resources\PageBookResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPageBooks extends ListRecords
{
    protected static string $resource = PageBookResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
