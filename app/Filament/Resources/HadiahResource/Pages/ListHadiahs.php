<?php

namespace App\Filament\Resources\HadiahResource\Pages;

use App\Filament\Resources\HadiahResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListHadiahs extends ListRecords
{
    protected static string $resource = HadiahResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Tambah Hadiah'),
        ];
    }
}
