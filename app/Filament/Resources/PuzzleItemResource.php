<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PuzzleItemResource\Pages;
use App\Filament\Resources\PuzzleItemResource\RelationManagers;
use App\Models\PuzzleItem;
use App\Models\Level;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\IconColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PuzzleItemResource extends Resource
{
    protected static ?string $model = PuzzleItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')->required(),
                Select::make('level_id')
                    ->label('Level (module name)')
                    ->options(fn () => Level::with('module')->get()
                        ->mapWithKeys(fn ($l) => [$l->id => ($l->module->name ?? $l->name)])
                        ->toArray()
                    )
                    ->searchable()
                    ->required()
                    // hide the selector when editing an existing record
                    ->hidden(fn ($get, $record) => $record !== null),
                FileUpload::make('image')->image()->directory('puzzle-items')->required(),
                Select::make('grid_size')
                    ->options([3 => '3x3', 4 => '4x4'])
                    ->required(),
                TextInput::make('order_number')->numeric()->required(),
                Toggle::make('is_active')->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->searchable(),
                TextColumn::make('level.module.name')->label('Module')->sortable()->searchable(),
                TextColumn::make('grid_size')->label('Grid Size'),
                TextColumn::make('order_number')->sortable(),
                IconColumn::make('is_active')->boolean()->label('Active'),
                TextColumn::make('created_at')->label('Created')->dateTime(),
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
            'index' => Pages\ListPuzzleItems::route('/'),
            'create' => Pages\CreatePuzzleItem::route('/create'),
            'edit' => Pages\EditPuzzleItem::route('/{record}/edit'),
        ];
    }
}
