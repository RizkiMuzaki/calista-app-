<?php

namespace App\Filament\Resources\HadiahResource\Pages;

use App\Filament\Resources\HadiahResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditHadiah extends EditRecord
{
    protected static string $resource = HadiahResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
