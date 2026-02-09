<?php

namespace App\Filament\Resources\PageBookResource\Pages;

use App\Filament\Resources\PageBookResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPageBook extends EditRecord
{
    protected static string $resource = PageBookResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
