<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PlaySessionResource\Pages;
use App\Models\PlaySession;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;

class PlaySessionResource extends Resource
{
    protected static ?string $model = PlaySession::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationLabel = 'Sesi Belajar Anak';
    protected static ?string $modelLabel = 'Sesi Belajar';
    protected static ?string $pluralModelLabel = 'Sesi Belajar Anak';
    protected static ?string $navigationGroup = 'Laporan & Analitik';
    protected static ?int $navigationSort = 1;

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

                Tables\Columns\TextColumn::make('module_name')
                    ->label('Modul')
                    ->searchable()
                    ->badge()
                    ->color(fn ($state) => match (strtolower($state ?? '')) {
                        'menulis', 'writing'     => 'warning',
                        'membaca', 'reading'     => 'info',
                        'berhitung', 'counting'  => 'success',
                        'puzzle'                 => 'danger',
                        default                  => 'gray',
                    }),

                Tables\Columns\TextColumn::make('level_title')
                    ->label('Level')
                    ->placeholder('—')
                    ->limit(25),

                Tables\Columns\TextColumn::make('score')
                    ->label('Skor')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('bintang')
                    ->label('Bintang')
                    ->formatStateUsing(fn ($state) => str_repeat('⭐', (int) $state))
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('mistakes')
                    ->label('Kesalahan')
                    ->numeric()
                    ->sortable()
                    ->color(fn ($state) => $state > 5 ? 'danger' : ($state > 2 ? 'warning' : 'success')),

                Tables\Columns\TextColumn::make('duration_seconds')
                    ->label('Durasi')
                    ->formatStateUsing(function ($state) {
                        if (!$state) return '—';
                        $minutes = floor($state / 60);
                        $secs = $state % 60;
                        return "{$minutes} mnt {$secs} dtk";
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'completed' => 'Selesai',
                        'failed'    => 'Gagal',
                        'ongoing'   => 'Berlangsung',
                        default     => $state ?? '—',
                    })
                    ->color(fn ($state) => match ($state) {
                        'completed' => 'success',
                        'failed'    => 'danger',
                        'ongoing'   => 'warning',
                        default     => 'gray',
                    }),

                Tables\Columns\TextColumn::make('played_on')
                    ->label('Tanggal Main')
                    ->date('d/m/Y')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'completed' => 'Selesai',
                        'failed'    => 'Gagal',
                        'ongoing'   => 'Berlangsung',
                    ]),

                SelectFilter::make('module_slug')
                    ->label('Modul')
                    ->options([
                        'menulis'    => 'Menulis',
                        'membaca'    => 'Membaca',
                        'berhitung'  => 'Berhitung',
                        'puzzle'     => 'Puzzle',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([])
            ->defaultSort('played_on', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPlaySessions::route('/'),
        ];
    }
}
