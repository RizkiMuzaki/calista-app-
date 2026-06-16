<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StoryResource\Pages;
use App\Filament\Resources\StoryResource\RelationManagers;
use App\Models\Story;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Filters\TernaryFilter;
use Illuminate\Support\Str;
use App\Filament\Resources\StoryResource\RelationManagers\ReviewsRelationManager;
use App\Filament\Resources\StoryResource\RelationManagers\LikesRelationManager;

class StoryResource extends Resource
{
    protected static ?string $model = Story::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $navigationGroup = 'Managemen Module dan Buku';

    protected static ?string $navigationLabel = 'Dongeng Premium';

    protected static ?string $modelLabel = 'Dongeng';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Utama')
                    ->schema([
                        TextInput::make('title')
                            ->label('Judul Cerita')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),

                        TextInput::make('slug')
                            ->label('Slug')
                            ->disabled()
                            ->dehydrated()
                            ->required()
                            ->unique(Story::class, 'slug', ignoreRecord: true),

                        Textarea::make('description')
                            ->label('Deskripsi')
                            ->rows(3)
                            ->nullable(),
                    ])->columns(2),

                Forms\Components\Section::make('Pengaturan & Metadata')
                    ->schema([
                        TextInput::make('rating')
                            ->label('Rating Bintang')
                            ->numeric()
                            ->default(5.0)
                            ->required(),

                        Select::make('age_group')
                            ->label('Kelompok Umur')
                            ->options([
                                '3-5' => '3-5 Tahun',
                                '5-7' => '5-7 Tahun',
                                '7-9' => '7-9 Tahun',
                                '9-12' => '9-12 Tahun',
                            ])
                            ->default('5-7')
                            ->required(),

                        TextInput::make('order')
                            ->label('Urutan Tampil')
                            ->numeric()
                            ->default(1)
                            ->required(),

                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true),

                        Toggle::make('is_premium')
                            ->label('Premium / Berbayar')
                            ->default(false),

                        TextInput::make('stars_required')
                            ->label('Bintang Dibutuhkan')
                            ->numeric()
                            ->default(0)
                            ->helperText('0 = gratis, 6 = perlu 6 bintang untuk unlock'),
                    ])->columns(2),

                Forms\Components\Section::make('Skrip Cerita (Tampil Melayang)')
                    ->schema([
                        Forms\Components\RichEditor::make('full_script')
                            ->label('Teks Skrip Lengkap')
                            ->toolbarButtons([
                                'bold', 'italic', 'underline', 'h2', 'h3', 'bulletList', 'orderedList', 'redo', 'undo',
                            ])
                            ->nullable()
                            ->columnSpanFull()
                            ->helperText('Tuliskan seluruh teks cerita di sini. Teks akan ditampilkan di atas video player aplikasi secara utuh.'),
                    ]),

                Forms\Components\Section::make('Aset Cerita (Unified Media)')
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('cover')
                            ->label('Gambar Cover Cerita')
                            ->collection('cover')
                            ->disk('public')
                            ->required()
                            ->image()
                            ->maxSize(10240), // 10MB

                        SpatieMediaLibraryFileUpload::make('full_narration')
                            ->label('Audio Narasi Lengkap (.mp3 / .wav)')
                            ->collection('full_narration')
                            ->disk('public')
                            ->required()
                            ->acceptedFileTypes(['audio/mpeg', 'audio/wav', 'audio/mp3', 'application/octet-stream'])
                            ->maxSize(51200), // 50MB

                        SpatieMediaLibraryFileUpload::make('full_animation')
                            ->label('Video Animasi Lengkap (.mp4) — Opsional')
                            ->collection('full_animation')
                            ->disk('public')
                            ->helperText('Upload file .mp4. Maksimum 500MB. Jika tidak ada video, cerita akan tampil dengan teks saja.')
                            ->nullable()
                            ->acceptedFileTypes(['video/mp4', 'application/octet-stream'])
                            ->maxSize(512000), // 500MB
                    ])->columns(1),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('cover')
                    ->label('Cover')
                    ->collection('cover'),

                TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('age_group')
                    ->label('Umur')
                    ->sortable(),

                TextColumn::make('rating')
                    ->label('Rating')
                    ->sortable(),

                ToggleColumn::make('is_active')
                    ->label('Aktif'),

                ToggleColumn::make('is_premium')
                    ->label('Premium'),

                TextColumn::make('stars_required')
                    ->label('Min. Bintang')
                    ->sortable(),

                TextColumn::make('order')
                    ->label('Urutan')
                    ->sortable(),
            ])
            ->filters([
                TernaryFilter::make('is_active')->label('Aktif'),
                TernaryFilter::make('is_premium')->label('Premium'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('order', 'asc');
    }

    public static function getRelations(): array
    {
        return [
            ReviewsRelationManager::class,
            LikesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStories::route('/'),
            'create' => Pages\CreateStory::route('/create'),
            'edit' => Pages\EditStory::route('/{record}/edit'),
        ];
    }
}
