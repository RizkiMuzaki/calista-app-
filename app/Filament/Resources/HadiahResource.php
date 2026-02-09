<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Hadiah;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\HadiahResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\HadiahResource\RelationManagers;

class HadiahResource extends Resource
{
    protected static ?string $model = Hadiah::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    
    protected static ?string $navigationGroup = 'Event';
    
    protected static ?string $navigationLabel = 'Hadiah';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('game_id')
                    ->relationship('game', 'nama_game')
                    ->required(),
                Forms\Components\TextInput::make('nama_hadiah')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('jenis_hadiah')
                    ->options([
                        'uang' => 'Uang',
                        'makanan' => 'Makanan',
                        'audio' => 'Audio',
                    ])
                    ->required(),
                Forms\Components\FileUpload::make('foto')
                    ->image()
                    ->directory('hadiahs')
                    ->required(),
                 FileUpload::make('audio')
                    ->label('Audio')
                    ->disk('public')
                    ->directory('hadiahs/audio')
                    ->acceptedFileTypes(['audio/*'])
                    ->maxSize(10240)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('game.nama_game')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama_hadiah')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jenis_hadiah')
                    ->badge(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHadiahs::route('/'),
            'create' => Pages\CreateHadiah::route('/create'),
            'edit' => Pages\EditHadiah::route('/{record}/edit'),
        ];
    }
}
