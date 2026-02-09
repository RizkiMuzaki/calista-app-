<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StoryImageResource\Pages;
use App\Filament\Resources\StoryImageResource\RelationManagers;
use App\Models\StoryImage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class StoryImageResource extends Resource
{
    protected static ?string $model = StoryImage::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

     protected static ?string $navigationGroup = 'Cerita Rakyat';

    protected static ?string $navigationLabel = 'Gambar Cerita';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('story_page_id')
                    ->relationship(
                        'storyPage',
                        'id',
                        fn ($query) => $query->with('book')->orderByDesc('id')
                    )
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->book ? "{$record->book->title} - Page {$record->page_number}: " . Str::limit($record->story_text, 40) : "Story Page {$record->id}")
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\FileUpload::make('image_path')
                    ->directory('story-images')
                    ->required(),
                Forms\Components\Select::make('type')
                    ->options([
                        'background' => 'Background',
                        'character' => 'Character',
                        'object' => 'Object',
                    ])
                    ->default('background')
                    ->required(),
                Forms\Components\TextInput::make('order')
                    ->numeric()
                    ->default(1)
                    ->required(),
                Forms\Components\TextInput::make('start_second')
                    ->numeric()
                    ->default(0)
                    ->required(),
                Forms\Components\TextInput::make('end_second')
                    ->numeric(),
                Forms\Components\Toggle::make('is_active')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('storyPage.id')
                    ->label('Story Page')
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->badge(),
                Tables\Columns\TextColumn::make('order')
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_second')
                    ->label('Start (s)')
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_second')
                    ->label('End (s)'),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'background' => 'Background',
                        'character' => 'Character',
                        'object' => 'Object',
                    ]),
                Tables\Filters\TernaryFilter::make('is_active'),
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
            'index' => Pages\ListStoryImages::route('/'),
            'create' => Pages\CreateStoryImage::route('/create'),
            'edit' => Pages\EditStoryImage::route('/{record}/edit'),
        ];
    }
}
