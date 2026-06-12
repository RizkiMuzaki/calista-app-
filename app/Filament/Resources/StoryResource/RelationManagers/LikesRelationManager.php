<?php

namespace App\Filament\Resources\StoryResource\RelationManagers;

use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class LikesRelationManager extends RelationManager
{
    protected static string $relationship = 'likes';

    protected static ?string $title = 'Likes (Disukai)';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('anak.nama')
                    ->label('Nama Anak')
                    ->default('—')
                    ->searchable(),
                TextColumn::make('user.name')
                    ->label('Orang Tua')
                    ->default('—')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Tanggal Suka')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public function form(Form $form): Form
    {
        return $form->schema([]);
    }
}
