<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AiCreditUsageResource\Pages;
use App\Models\AiCreditUsage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Number;

class AiCreditUsageResource extends Resource
{
    protected static ?string $model = AiCreditUsage::class;

    protected static ?string $navigationIcon = 'heroicon-o-microphone';
    protected static ?string $navigationLabel = 'Penggunaan AI Nusa';
    protected static ?string $modelLabel = 'Log AI';
    protected static ?string $pluralModelLabel = 'Penggunaan AI Nusa';
    protected static ?string $navigationGroup = 'Laporan & Analitik';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Pengguna')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('feature')
                    ->label('Fitur')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'tts'           => '🎙️ TTS Suara',
                        'writing_tts'   => '✏️ TTS Menulis',
                        'counting_tts'  => '🔢 TTS Berhitung',
                        'chat'          => '💬 Chat Nusa',
                        default         => ucfirst(str_replace('_', ' ', $state ?? '—')),
                    })
                    ->color(fn ($state) => match ($state) {
                        'tts'           => 'info',
                        'writing_tts'   => 'warning',
                        'counting_tts'  => 'success',
                        'chat'          => 'danger',
                        default         => 'gray',
                    }),

                Tables\Columns\TextColumn::make('plan_code')
                    ->label('Plan TTS')
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('credits')
                    ->label('Kredit Dipakai')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('characters')
                    ->label('Karakter Di-generate')
                    ->numeric()
                    ->sortable()
                    ->formatStateUsing(fn ($state) => Number::format($state, locale: 'id') . ' karakter'),

                Tables\Columns\TextColumn::make('period_start')
                    ->label('Periode')
                    ->date('d/m/Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Log')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('feature')
                    ->label('Fitur')
                    ->options([
                        'tts'          => '🎙️ TTS Suara',
                        'writing_tts'  => '✏️ TTS Menulis',
                        'counting_tts' => '🔢 TTS Berhitung',
                        'chat'         => '💬 Chat Nusa',
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
            'index' => Pages\ListAiCreditUsages::route('/'),
        ];
    }
}
