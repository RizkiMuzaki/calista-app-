<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CharacterItemResource\Pages;
use App\Models\CharacterItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;

class CharacterItemResource extends Resource
{
    protected static ?string $model = CharacterItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-sparkles';
    protected static ?string $navigationLabel = 'Toko Baju Nusa';
    protected static ?string $modelLabel = 'Baju Nusa';
    protected static ?string $pluralModelLabel = 'Toko Baju Nusa';
    protected static ?string $navigationGroup = 'Manajemen Konten';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Baju')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Baju')
                            ->required()
                            ->maxLength(100),

                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi Baju')
                            ->rows(3)
                            ->nullable(),

                        Forms\Components\TextInput::make('image_url')
                            ->label('URL Gambar Baju')
                            ->url()
                            ->nullable()
                            ->columnSpanFull()
                            ->helperText('Masukkan URL gambar baju (PNG/WEBP, 1:1 ratio)'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Pengaturan Unlock')
                    ->schema([
                        Forms\Components\Select::make('unlock_type')
                            ->label('Jenis Unlock')
                            ->options([
                                'free'    => '🎁 Gratis (semua anak)',
                                'reward'  => '🏆 Reward (milestone belajar)',
                                'premium' => '⭐ Premium (subscriber)',
                            ])
                            ->required()
                            ->reactive()
                            ->default('free'),

                        Forms\Components\TextInput::make('reward_condition')
                            ->label('Syarat Reward (jumlah level selesai)')
                            ->numeric()
                            ->nullable()
                            ->visible(fn ($get) => $get('unlock_type') === 'reward')
                            ->helperText('Contoh: 10 = anak harus menyelesaikan 10 level'),

                        Forms\Components\TextInput::make('sort_order')
                            ->label('Urutan Tampil')
                            ->numeric()
                            ->default(1)
                            ->required(),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Aktif di Toko')
                            ->default(true),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image_url')
                    ->label('Gambar')
                    ->size(60)
                    ->circular(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Baju')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('unlock_type')
                    ->label('Jenis Unlock')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'free'    => '🎁 Gratis',
                        'reward'  => '🏆 Reward',
                        'premium' => '⭐ Premium',
                        default   => $state,
                    })
                    ->color(fn ($state) => match ($state) {
                        'free'    => 'success',
                        'reward'  => 'warning',
                        'premium' => 'danger',
                        default   => 'gray',
                    }),

                Tables\Columns\TextColumn::make('reward_condition')
                    ->label('Syarat (level)')
                    ->placeholder('-')
                    ->sortable(),

                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Urutan')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
            ])
            ->filters([
                SelectFilter::make('unlock_type')
                    ->label('Jenis Unlock')
                    ->options([
                        'free'    => 'Gratis',
                        'reward'  => 'Reward',
                        'premium' => 'Premium',
                    ]),

                TernaryFilter::make('is_active')
                    ->label('Status Aktif'),
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
            ->defaultSort('sort_order', 'asc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListCharacterItems::route('/'),
            'create' => Pages\CreateCharacterItem::route('/create'),
            'edit'   => Pages\EditCharacterItem::route('/{record}/edit'),
        ];
    }
}
