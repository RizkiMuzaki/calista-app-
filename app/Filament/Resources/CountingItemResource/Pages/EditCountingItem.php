<?php

namespace App\Filament\Resources\CountingItemResource\Pages;

use App\Filament\Resources\CountingItemResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCountingItem extends EditRecord
{
    protected static string $resource = CountingItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
