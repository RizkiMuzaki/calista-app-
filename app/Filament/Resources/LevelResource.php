<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LevelResource\Pages;
use App\Filament\Resources\LevelResource\RelationManagers;
use App\Models\Level;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;

class LevelResource extends Resource
{
    protected static ?string $model = Level::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Managemen Module dan Buku';

    protected static ?string $navigationLabel = 'Level';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('module_id')
                    ->label('Module')
                    ->relationship('module', 'name', function ($query) {
                        $query->where('type', 'level');
                    })
                    ->required()
                    ->live()
                    ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set) {
                        $moduleId = $get('module_id');
                        if ($moduleId) {
                            // Ambil level terakhir di module ini
                            $lastLevel = Level::where('module_id', $moduleId)
                                ->orderBy('order_number', 'desc')
                                ->first();
                            
                            if ($lastLevel) {
                                // Set order_number ke order_number terakhir + 1
                                $nextOrderNumber = $lastLevel->order_number + 1;
                                $set('order_number', $nextOrderNumber);
                                
                                // Set title otomatis: "Level {nextOrderNumber}"
                                $set('title', "Level {$nextOrderNumber}");
                            } else {
                                // Jika belum ada level, mulai dari 1
                                $set('order_number', 1);
                                $set('title', 'Level 1');
                            }
                        }
                    }),
                Forms\Components\TextInput::make('order_number')
                    ->required()
                    ->numeric()
                    ->readOnly(),
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID')->sortable(),
                TextColumn::make('module.name')->label('Module')->sortable()->searchable(),
                TextColumn::make('order_number')->label('Order')->sortable(),
                TextColumn::make('title')->label('Title')->sortable()->searchable(),
                TextColumn::make('writing_items_count')->label('Items')->sortable(),
                TextColumn::make('created_at')->label('Created')->dateTime()->sortable(),
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
            'index' => Pages\ListLevels::route('/'),
            'create' => Pages\CreateLevel::route('/create'),
            'edit' => Pages\EditLevel::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('module')->withCount('writingItems');
    }
}
