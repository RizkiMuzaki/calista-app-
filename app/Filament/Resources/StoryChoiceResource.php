<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StoryChoiceResource\Pages;
use App\Filament\Resources\StoryChoiceResource\RelationManagers;
use App\Models\StoryChoice;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StoryChoiceResource extends Resource
{
    protected static ?string $model = StoryChoice::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

     protected static ?string $navigationGroup = 'Cerita Rakyat';

    protected static ?string $navigationLabel = 'Pilihan Cerita';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('story_page_id')
                    ->relationship('storyPage', 'id', modifyQueryUsing: fn(Builder $query) => $query->with('book')->orderBy('book_id')->orderBy('page_number'))
                    ->getOptionLabelFromRecordUsing(fn($record) => "{$record->book->title} - Page {$record->page_number}")
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\TextInput::make('choice_text')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('next_page_number')
                    ->numeric()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('storyPage.id')
                    ->label('Story Page')
                    ->sortable(),
                Tables\Columns\TextColumn::make('choice_text')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('next_page_number')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('story_page_id')
                    ->relationship('storyPage', 'id'),
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
            'index' => Pages\ListStoryChoices::route('/'),
            'create' => Pages\CreateStoryChoice::route('/create'),
            'edit' => Pages\EditStoryChoice::route('/{record}/edit'),
        ];
    }
}
