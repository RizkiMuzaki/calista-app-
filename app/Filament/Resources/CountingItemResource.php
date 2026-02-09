<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CountingItemResource\Pages;
use App\Models\CountingItem;
use App\Models\Level;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Str;

class CountingItemResource extends Resource
{
    protected static ?string $model = CountingItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    
    protected static ?string $navigationGroup = 'Berhitung';
    
    protected static ?string $navigationLabel = 'Item Berhitung';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('level_id')
                    ->label('Level')
                    ->options(function () {
                        // ambil semua level_id yang sudah dipakai di CountingItem
                        $used = CountingItem::query()->pluck('level_id')->filter()->unique()->toArray();
                        // jika sedang edit, pastikan level saat ini tetap termasuk
                        $routeRecord = request()->route('record') ?? null;
                        $current = null;
                        if ($routeRecord) {
                            if (is_numeric($routeRecord) || is_string($routeRecord)) {
                                $current = CountingItem::find($routeRecord)->level_id ?? null;
                            } elseif (is_object($routeRecord)) {
                                $current = $routeRecord->level_id ?? null;
                            }
                        }
                        if ($current) {
                            $used = array_diff($used, [$current]);
                        }
                        // ambil level dari module "Berhitung" yang belum dipakai
                        $levels = Level::with('module')
                            ->whereHas('module', function ($query) {
                                $query->where('name', 'Menghitung');
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

                Forms\Components\TextInput::make('nama_objek')
                    ->label('Nama Objek')
                    ->required()
                    ->maxLength(255),

                Forms\Components\FileUpload::make('gambar_objek')
                    ->label('Gambar Objek')
                    ->image()
                    ->imagePreviewHeight('250')
                    ->directory('counting-items/images')
                    ->disk('public')
                    ->preserveFilenames(),

                Forms\Components\Select::make('jenis_operasi')
                    ->label('Jenis Operasi')
                    ->options([
                        'tambah' => Str::title('tambah'),
                        'kurang' => Str::title('kurang'),
                    ])
                    ->default('tambah')
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set, $get) {
                        // recompute hasil ketika operasi berubah
                        $left = (int) ($get('nilai_kiri') ?? 0);
                        $right = (int) ($get('nilai_kanan') ?? 0);
                        $set('hasil', $state === 'tambah' ? $left + $right : $left - $right);
                    }),

                Forms\Components\TextInput::make('nilai_kiri')
                    ->label('Nilai Kiri')
                    ->numeric()
                    ->minValue(0)
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set, $get) {
                        $op = $get('jenis_operasi') ?? 'tambah';
                        $right = (int) ($get('nilai_kanan') ?? 0);
                        $set('hasil', $op === 'tambah' ? $state + $right : $state - $right);
                    }),

                Forms\Components\TextInput::make('nilai_kanan')
                    ->label('Nilai Kanan')
                    ->numeric()
                    ->minValue(0)
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set, $get) {
                        $op = $get('jenis_operasi') ?? 'tambah';
                        $left = (int) ($get('nilai_kiri') ?? 0);
                        $set('hasil', $op === 'tambah' ? $left + $state : $left - $state);
                    }),

                Forms\Components\TextInput::make('hasil')
                    ->label('Hasil')
                    ->numeric()
                    ->minValue(0)
                    ->required(),
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

                TextColumn::make('nama_objek')
                    ->label('Nama Objek')
                    ->searchable()
                    ->sortable()
                    ->limit(30),

                TextColumn::make('jenis_operasi')
                    ->label('Jenis Operasi')
                    ->badge()
                    ->color(fn (string $state) => $state === 'tambah' ? 'success' : 'danger')
                    ->formatStateUsing(fn (string $state) => Str::title($state))
                    ->sortable(),

                TextColumn::make('nilai_kiri')
                    ->label('Nilai Kiri')
                    ->sortable(),

                TextColumn::make('nilai_kanan')
                    ->label('Nilai Kanan')
                    ->sortable(),

                TextColumn::make('hasil')
                    ->label('Hasil')
                    ->sortable()
                    ->badge()
                    ->color('primary'),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('jenis_operasi')
                    ->options([
                        'tambah' => 'Tambah',
                        'kurang' => 'Kurang',
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCountingItems::route('/'),
            'create' => Pages\CreateCountingItem::route('/create'),
            'edit' => Pages\EditCountingItem::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        // eager-load module juga supaya kolom Level / Module tidak menimbulkan N+1
        return parent::getEloquentQuery()->with('level.module');
    }
}