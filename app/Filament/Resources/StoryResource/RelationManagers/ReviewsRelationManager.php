<?php

namespace App\Filament\Resources\StoryResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;

class ReviewsRelationManager extends RelationManager
{
    protected static string $relationship = 'reviews';

    protected static ?string $title = 'Ulasan Anak & Orang Tua';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('comment')
            ->columns([
                TextColumn::make('anak.nama')
                    ->label('Nama Anak')
                    ->default('—')
                    ->searchable(),
                TextColumn::make('user.name')
                    ->label('Orang Tua')
                    ->default('—')
                    ->searchable(),
                Tables\Columns\TextColumn::make('rating')
                    ->label('Bintang')
                    ->formatStateUsing(fn ($state) => str_repeat('⭐', (int) $state) . " ($state/5)")
                    ->sortable(),
                TextColumn::make('comment')
                    ->label('Komentar')
                    ->limit(80)
                    ->default('—'),
                TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('rating')
                    ->label('Filter Bintang')
                    ->options([
                        '1' => '⭐ 1 Bintang',
                        '2' => '⭐⭐ 2 Bintang',
                        '3' => '⭐⭐⭐ 3 Bintang',
                        '4' => '⭐⭐⭐⭐ 4 Bintang',
                        '5' => '⭐⭐⭐⭐⭐ 5 Bintang',
                    ]),
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public function form(Form $form): Form
    {
        return $form->schema([]);
    }
}
