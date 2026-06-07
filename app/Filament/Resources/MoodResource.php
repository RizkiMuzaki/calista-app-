<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MoodResource\Pages;
use App\Models\Mood;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;

class MoodResource extends Resource
{
    protected static ?string $model = Mood::class;

    protected static ?string $navigationIcon = 'heroicon-o-face-smile';
    protected static ?string $navigationLabel = 'Mood Harian Anak';
    protected static ?string $modelLabel = 'Data Mood';
    protected static ?string $pluralModelLabel = 'Mood Harian Anak';
    protected static ?string $navigationGroup = 'Laporan & Analitik';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('anak.nama_anak')
                    ->label('Nama Anak')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('mood_type')
                    ->label('Mood Hari Ini')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'sangat_senang' => '😄 Sangat Senang',
                        'senang'        => '😊 Senang',
                        'biasa'         => '😐 Biasa Saja',
                        'sedih'         => '😢 Sedih',
                        'marah'         => '😠 Marah',
                        'takut'         => '😨 Takut',
                        default         => ucfirst(str_replace('_', ' ', $state ?? '—')),
                    })
                    ->color(fn ($state) => match ($state) {
                        'sangat_senang', 'senang' => 'success',
                        'biasa'                   => 'gray',
                        'sedih', 'takut'          => 'info',
                        'marah'                   => 'danger',
                        default                   => 'gray',
                    }),

                Tables\Columns\TextColumn::make('catatan')
                    ->label('Catatan')
                    ->placeholder('Tidak ada catatan')
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->catatan),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Check-in')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('mood_type')
                    ->label('Jenis Mood')
                    ->options([
                        'sangat_senang' => '😄 Sangat Senang',
                        'senang'        => '😊 Senang',
                        'biasa'         => '😐 Biasa Saja',
                        'sedih'         => '😢 Sedih',
                        'marah'         => '😠 Marah',
                        'takut'         => '😨 Takut',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMoods::route('/'),
        ];
    }
}
