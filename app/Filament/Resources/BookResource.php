<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Book;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Module;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TernaryFilter;
use App\Filament\Resources\BookResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\BookResource\RelationManagers;

class BookResource extends Resource
{
    protected static ?string $model = Book::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Managemen Module dan Buku';

    protected static ?string $navigationLabel = 'Buku';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('module_id')
                    ->label('Module')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->options(fn () => Module::whereIn('type', ['buku', 'book'])->pluck('name', 'id')),

                FileUpload::make('cover_image')
                    ->image()
                    ->disk('public')
                    ->directory('books')
                    ->required(),

                TextInput::make('title')
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),

                TextInput::make('slug')
                    ->disabled()
                    ->dehydrated(false)
                    ->helperText('Slug is generated automatically from the title.'),

                TextInput::make('order')
                    ->numeric()
                    ->default(1)
                    ->required(),

                Select::make('type')
                    ->label('Type')
                    ->options([
                        'cerita' => 'Cerita',
                        'membaca' => 'Membaca',
                    ])
                    ->default('cerita')
                    ->required(),

                Forms\Components\Textarea::make('description')
                    ->label('Description')
                    ->nullable(),

                Toggle::make('is_active')->label('Active')->default(true),
                Toggle::make('is_premium')->label('Premium')->default(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->searchable()->sortable(),
                TextColumn::make('module.name')->label('Module')->sortable()->searchable(),
                TextColumn::make('order')->sortable()->label('Order'),
                BadgeColumn::make('type')
                    ->label('Type')
                    ->formatStateUsing(fn ($state) => ucfirst($state))
                    ->colors([
                        'info' => 'cerita',
                        'primary' => 'membaca',
                    ]),
                TextColumn::make('description')->label('Description')->limit(50),
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
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('order');
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
            'index' => Pages\ListBooks::route('/'),
            'create' => Pages\CreateBook::route('/create'),
            'edit' => Pages\EditBook::route('/{record}/edit'),
        ];
    }
}
