<?php

namespace App\Filament\Resources\CountingItemResource\Pages;

use App\Filament\Resources\CountingItemResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCountingItem extends CreateRecord
{
    protected static string $resource = CountingItemResource::class;
}
