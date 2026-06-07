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
                            $lastLevel = Level::where('module_id', $moduleId)
                                ->orderBy('order_number', 'desc')
                                ->first();
                            
                            if ($lastLevel) {
                                $nextOrderNumber = $lastLevel->order_number + 1;
                                $set('order_number', $nextOrderNumber);
                                $set('title', "Level {$nextOrderNumber}");
                            } else {
                                $set('order_number', 1);
                                $set('title', 'Level 1');
                            }
                        }
                    }),
                Forms\Components\Select::make('activity_type')
                    ->label('Tipe Aktivitas')
                    ->options([
                        'reading' => '📖 Membaca',
                        'writing' => '✍️ Menulis',
                        'counting' => '🔢 Berhitung',
                        'puzzle' => '🧩 Puzzle',
                    ])
                    ->required()
                    ->live()
                    ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set) {
                        $moduleId = $get('module_id');
                        $activityType = $get('activity_type');
                        $orderNumber = $get('order_number');
                        if ($moduleId && $activityType && $orderNumber) {
                            $module = \App\Models\Module::find($moduleId);
                            if ($module) {
                                $set('local_level_id', "{$activityType}-{$module->slug}-{$orderNumber}");
                            }
                        }
                    }),
                Forms\Components\TextInput::make('order_number')
                    ->label('Level Ke')
                    ->required()
                    ->numeric()
                    ->readOnly(),
                Forms\Components\TextInput::make('title')
                    ->label('Judul Level')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('local_level_id')
                    ->label('Local Level ID (Flutter)')
                    ->required()
                    ->maxLength(255)
                    ->helperText('Format: [tipe_aktivitas]-[slug_modul]-[level_ke], Contoh: reading-zoo-1'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID')->sortable(),
                TextColumn::make('module.name')->label('Module / Chapter')->sortable()->searchable(),
                TextColumn::make('activity_type')
                    ->label('Tipe Aktivitas')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'reading' => '📖 Membaca',
                        'writing' => '✍️ Menulis',
                        'counting' => '🔢 Berhitung',
                        'puzzle' => '🧩 Puzzle',
                        default => $state,
                    })
                    ->color(fn ($state) => match ($state) {
                        'reading' => 'success',
                        'writing' => 'info',
                        'counting' => 'warning',
                        'puzzle' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('order_number')->label('Level Ke')->sortable(),
                TextColumn::make('title')->label('Judul Level')->sortable()->searchable(),
                TextColumn::make('local_level_id')->label('Local Level ID (Flutter)')->sortable()->searchable(),
                TextColumn::make('created_at')->label('Dibuat')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->paginated([10])
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
        return parent::getEloquentQuery()->with('module');
    }
}
