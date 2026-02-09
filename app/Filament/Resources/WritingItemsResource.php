<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WritingItemsResource\Pages;
use App\Filament\Resources\WritingItemsResource\RelationManagers;
use App\Models\WritingItems;
use App\Models\Level;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Support\Facades\Storage;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Filesystem\FilesystemAdapter;


class WritingItemsResource extends Resource
{
    protected static ?string $model = WritingItems::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    
    protected static ?string $navigationGroup = 'Menulis';
    
    protected static ?string $navigationLabel = 'Item Menulis';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('level_id')
                    ->label('Level')
                    ->options(function () {
                        // ambil semua level_id yang sudah dipakai di WritingItems
                        $used = WritingItems::query()->pluck('level_id')->filter()->unique()->toArray();
                        // jika sedang edit, pastikan level saat ini tetap termasuk
                        $routeRecord = request()->route('record') ?? null;
                        $current = null;
                        if ($routeRecord) {
                            if (is_numeric($routeRecord) || is_string($routeRecord)) {
                                $current = WritingItems::find($routeRecord)->level_id ?? null;
                            } elseif (is_object($routeRecord)) {
                                $current = $routeRecord->level_id ?? null;
                            }
                        }
                        if ($current) {
                            $used = array_diff($used, [$current]);
                        }
                        // ambil level dari module "Menulis" yang belum dipakai
                        $levels = Level::with('module')
                            ->whereHas('module', function ($query) {
                                $query->where('name', 'Menulis');
                            })
                            ->where(function ($query) use ($used, $current) {
                                $query->whereNotIn('id', $used)
                                    ->orWhere('id', $current);
                            })
                            ->orderBy('id')
                            ->get();
                        return $levels->mapWithKeys(fn($lvl) => [
                            $lvl->id => $lvl->title . ($lvl->module ? ' - ' . $lvl->module->name : ''),
                        ])->toArray();
                    })
                    ->searchable()
                    ->required(),
                Forms\Components\TextInput::make('text')
                    ->required()
                    ->maxLength(255),
                Forms\Components\FileUpload::make('image_path')
                    ->label('Image')
                    ->image()
                    ->imagePreviewHeight('250')
                    ->directory('writing-items/images')
                    ->disk('public')
                    ->preserveFilenames(),
                Forms\Components\FileUpload::make('audio_path')
                    ->label('Audio')
                    ->disk('public')
                    ->directory('writing-items/audio')
                    ->acceptedFileTypes(['audio/mpeg', 'audio/mp3', 'audio/wav']),
                Forms\Components\Select::make('type')
                    ->options([
                        'letter' => 'Letter',
                        'word' => 'Word',
                    ])
                    ->required()
                    ->default('word'),
            ]);
    }

   public static function table(Table $table): Table
{
    return $table
        ->columns([
            TextColumn::make('level.title')
            ->label('Level')
                ->sortable()
                ->searchable(),

            TextColumn::make('level.module.name')
                ->label('Module')
                ->sortable(),

            TextColumn::make('text')
                ->label('Text')
                ->searchable()
                ->sortable()
                ->limit(30),

            TextColumn::make('type')
                ->label('Type')
                ->badge()
                ->color(fn (string $state) => $state === 'letter' ? 'warning' : 'success')
                ->formatStateUsing(fn (string $state) => ucfirst($state)),

            TextColumn::make('audio_path')
                ->label('Audio')
                ->formatStateUsing(fn ($state) => $state ? 'Available' : '—')
                ->badge()
                ->color(fn ($state) => $state ? 'success' : 'gray'),

            TextColumn::make('created_at')
                ->label('Created')
                ->dateTime('d M Y')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ])
        ->filters([
            Tables\Filters\SelectFilter::make('type')
                ->options([
                    'letter' => 'Letter',
                    'word'   => 'Word',
                ]),

            Tables\Filters\SelectFilter::make('level_id')
                ->label('Level')
                ->relationship('level', 'title'),
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ])
        ->bulkActions([
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
            ]),
        ]);
}

    /**
     * Return public url for a given path on 'public' disk.
     *
     * @param string|null $path
     * @return string|null
     */
  

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWritingItems::route('/'),
            'create' => Pages\CreateWritingItems::route('/create'),
            'edit' => Pages\EditWritingItems::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        // eager-load module juga supaya kolom Level / Module tidak menimbulkan N+1
        return parent::getEloquentQuery()->with('level.module');
    }
}
