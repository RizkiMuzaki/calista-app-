<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentResource\Pages;
use App\Models\Payment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Number;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    protected static ?string $navigationLabel = 'Transaksi Pembayaran';
    protected static ?string $modelLabel = 'Transaksi';
    protected static ?string $pluralModelLabel = 'Transaksi Pembayaran';
    protected static ?string $navigationGroup = 'Keuangan & Langganan';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Detail Transaksi')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('Pengguna')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\Select::make('plan_id')
                            ->label('Paket Langganan')
                            ->relationship('plan', 'nama_paket')
                            ->searchable()
                            ->preload()
                            ->nullable(),

                        Forms\Components\TextInput::make('reference')
                            ->label('Referensi Transaksi')
                            ->disabled(),

                        Forms\Components\Select::make('provider')
                            ->label('Provider Pembayaran')
                            ->options([
                                'tripay'  => 'Tripay',
                                'louvin'  => 'Louvin',
                                'manual'  => 'Manual',
                            ])
                            ->required(),

                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'UNPAID'  => 'Belum Bayar',
                                'PENDING' => 'Menunggu Konfirmasi',
                                'PAID'    => 'Sudah Dibayar',
                                'EXPIRED' => 'Kadaluarsa',
                                'FAILED'  => 'Gagal',
                            ])
                            ->required(),

                        Forms\Components\TextInput::make('amount')
                            ->label('Jumlah Tagihan (Rp)')
                            ->numeric()
                            ->disabled(),

                        Forms\Components\TextInput::make('amount_received')
                            ->label('Jumlah Diterima (Rp)')
                            ->numeric()
                            ->disabled(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Pengguna')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('plan.nama_paket')
                    ->label('Paket')
                    ->placeholder('—')
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('provider')
                    ->label('Provider')
                    ->badge()
                    ->color(fn ($state) => $state === 'louvin' ? 'warning' : 'gray'),

                Tables\Columns\TextColumn::make('payment_name')
                    ->label('Metode Bayar')
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('amount_received')
                    ->label('Diterima')
                    ->formatStateUsing(fn ($state) => 'Rp ' . Number::format($state, locale: 'id'))
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'UNPAID'  => 'Belum Bayar',
                        'PENDING' => 'Menunggu',
                        'PAID'    => 'Lunas',
                        'EXPIRED' => 'Kadaluarsa',
                        'FAILED'  => 'Gagal',
                        default   => $state,
                    })
                    ->color(fn ($state) => match ($state) {
                        'PAID'    => 'success',
                        'PENDING' => 'warning',
                        'UNPAID'  => 'info',
                        'EXPIRED' => 'gray',
                        'FAILED'  => 'danger',
                        default   => 'gray',
                    }),

                Tables\Columns\TextColumn::make('reference')
                    ->label('Referensi')
                    ->searchable()
                    ->copyable()
                    ->limit(20),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'UNPAID'  => 'Belum Bayar',
                        'PENDING' => 'Menunggu',
                        'PAID'    => 'Lunas',
                        'EXPIRED' => 'Kadaluarsa',
                        'FAILED'  => 'Gagal',
                    ]),

                SelectFilter::make('provider')
                    ->label('Provider')
                    ->options([
                        'tripay' => 'Tripay',
                        'louvin' => 'Louvin',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPayments::route('/'),
        ];
    }
}
