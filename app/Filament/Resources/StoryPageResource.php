<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StoryPageResource\Pages;
use App\Filament\Resources\StoryPageResource\RelationManagers;
use App\Models\StoryPage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StoryPageResource extends Resource
{
    protected static ?string $model = StoryPage::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Cerita Rakyat';
    
    protected static ?string $navigationLabel = 'Halaman Cerita';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Halaman Cerita')
                    ->description('Isi data halaman cerita')
                    ->schema([
                        Forms\Components\Select::make('book_id')
                            ->relationship('book', 'title')
                            ->required()
                            ->label('Buku'),
                        
                        Forms\Components\TextInput::make('page_number')
                            ->required()
                            ->numeric()
                            ->label('Nomor Halaman')
                            ->minValue(1),
                        
                        Forms\Components\Textarea::make('story_text')
                            ->required()
                            ->rows(6)
                            ->label('Teks Cerita')
                            ->columnSpanFull(),
                        
                        Forms\Components\TextInput::make('audio_path')
                            ->nullable()
                            ->label('Path Audio')
                            ->placeholder('storage/audio/story_audio.mp3'),
                        
                        Forms\Components\TextInput::make('question')
                            ->nullable()
                            ->label('Pertanyaan Interaktif')
                            ->placeholder('Apa yang akan dilakukan tokoh selanjutnya?'),
                        
                        Forms\Components\TextInput::make('duration')
                            ->nullable()
                            ->numeric()
                            ->label('Durasi Audio (detik)')
                            ->minValue(0),
                        
                        Forms\Components\Toggle::make('is_active')
                            ->default(true)
                            ->label('Aktif'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('book.title')
                    ->label('Buku')
                    ->sortable()
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('page_number')
                    ->label('Halaman')
                    ->sortable()
                    ->numeric(),
                
                Tables\Columns\TextColumn::make('story_text')
                    ->label('Teks Cerita')
                    ->limit(50)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        return $column->getState();
                    }),
                
                Tables\Columns\TextColumn::make('question')
                    ->label('Pertanyaan')
                    ->limit(40),
                
                Tables\Columns\TextColumn::make('duration')
                    ->label('Durasi (detik)')
                    ->numeric(),
                
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('Aktif'),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Halaman Aktif'),
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
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListStoryPages::route('/'),
            'create' => Pages\CreateStoryPage::route('/create'),
            'edit' => Pages\EditStoryPage::route('/{record}/edit'),
        ];
    }
}
