<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PageBookResource\Pages;
use App\Filament\Resources\PageBookResource\RelationManagers;
use App\Models\PageBook;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\RichEditor;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\SelectFilter;

class PageBookResource extends Resource
{
    protected static ?string $model = PageBook::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Managemen Module dan Buku';

    protected static ?string $navigationLabel = 'Halaman Buku';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('book_id')
                    ->relationship('book', 'title', fn ($query) => $query->where('type', '!=', 'cerita'))
                    ->label('Book')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->live()
                    ->afterStateUpdated(function ($state, Forms\Set $set) {
                        if ($state) {
                            $maxPageNumber = PageBook::where('book_id', $state)->max('page_number') ?? 0;
                            $set('page_number', $maxPageNumber + 1);
                        }
                    }),

                TextInput::make('page_number')
                    ->label('Page Number')
                    ->numeric()
                    ->minValue(1)
                    ->required()
                    ->default(1),

                FileUpload::make('image_path')
                    ->label('Image')
                    ->image()
                    ->disk('public')
                    ->directory('page_books/images')
                    ->required(),

                FileUpload::make('audio_path')
                    ->label('Audio')
                    ->disk('public')
                    ->directory('page_books/audio')
                    ->acceptedFileTypes(['audio/*'])
                    ->maxSize(10240)
                    ->required(),

                FileUpload::make('audio_kata')
                    ->label('Audio Kata')
                    ->disk('public')
                    ->directory('page_books/audio_kata')
                    ->acceptedFileTypes(['audio/*'])
                    ->maxSize(10240)
                    ,

                TextInput::make('nama_benda')
                    ->label('Nama Benda')
                    ->required(),

                TextInput::make('suku_kata')
                    ->label('Suku Kata')
                    ->required(),

                RichEditor::make('explanation')
                    ->label('Explanation')
                    ->nullable(),

                Toggle::make('is_active')->label('Active')->default(true),
                Toggle::make('is_premium')->label('Premium')->default(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('page_number')->label('Page')->sortable(),
                TextColumn::make('book.title')->label('Book')->sortable()->searchable(),
                TextColumn::make('nama_benda')->label('Nama Benda')->searchable(),
                TextColumn::make('suku_kata')->label('Suku Kata'),
                TextColumn::make('audio_kata')
                    ->label('Audio Kata')
                    ->formatStateUsing(fn ($state) => $state ? basename($state) : null)
                    ->url(fn ($record) => $record->audio_kata ? asset('storage/' . ltrim($record->audio_kata, '/')) : null)
                    ->openUrlInNewTab(),
                BadgeColumn::make('is_active')
                    ->label('Active')
                    ->formatStateUsing(fn ($state) => $state ? 'Active' : 'Inactive')
                    ->colors([
                        'success' => fn ($state): bool => (bool) $state,
                        'danger' => fn ($state): bool => ! (bool) $state,
                    ]),
                BadgeColumn::make('is_premium')
                    ->label('Premium')
                    ->formatStateUsing(fn ($state) => $state ? 'Premium' : 'Free')
                    ->colors([
                        'warning' => fn ($state): bool => (bool) $state,
                        'secondary' => fn ($state): bool => ! (bool) $state,
                    ]),
                TextColumn::make('created_at')->dateTime()->label('Created')->sortable(),
            ])
            ->filters([
                TernaryFilter::make('is_active')->label('Active'),
                TernaryFilter::make('is_premium')->label('Premium'),
                SelectFilter::make('book_id')->label('Book')->relationship('book', 'title'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListPageBooks::route('/'),
            'create' => Pages\CreatePageBook::route('/create'),
            'edit' => Pages\EditPageBook::route('/{record}/edit'),
        ];
    }
}
