<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\TernaryFilter;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Pengguna (Orang Tua)';
    protected static ?string $modelLabel = 'Pengguna';
    protected static ?string $pluralModelLabel = 'Pengguna (Orang Tua)';
    protected static ?string $navigationGroup = 'Manajemen Pengguna';
    protected static ?int $navigationSort = 0;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Akun')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Lengkap')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),

                        Forms\Components\TextInput::make('parent_pin')
                            ->label('PIN Orang Tua')
                            ->maxLength(6)
                            ->nullable()
                            ->helperText('PIN 6 digit untuk area Parental Gate.'),
                            
                        Forms\Components\TextInput::make('password')
                            ->label('Password Baru')
                            ->password()
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $context): bool => $context === 'create')
                            ->maxLength(255)
                            ->helperText('Biarkan kosong jika tidak ingin mengubah password (pada mode edit).'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Orang Tua')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('subscription_status')
                    ->label('Paket Langganan')
                    ->badge()
                    ->state(function (User $record) {
                        $sub = $record->subscriptions()
                            ->where('status', 'aktif')
                            ->where('tanggal_berakhir', '>', now())
                            ->first();
                        if (!$sub) {
                            return 'Free Plan';
                        }
                        return $sub->plan?->nama_paket ?? 'Calista Plus';
                    })
                    ->color(function (User $record) {
                        $sub = $record->subscriptions()
                            ->where('status', 'aktif')
                            ->where('tanggal_berakhir', '>', now())
                            ->first();
                        return $sub ? 'success' : 'gray';
                    }),

                Tables\Columns\TextColumn::make('elevenlabs_usage')
                    ->label('ElevenLabs Credit')
                    ->state(function (User $record) {
                        $service = new \App\Services\AiCreditService();
                        $plan = $service->planFor($record);
                        $guard = $service->canSpend($record, 0);
                        return number_format($guard['used']) . ' / ' . number_format($guard['limit']) . ' (' . number_format($guard['remaining']) . ' sisa)';
                    })
                    ->description(fn (User $record) => 'Plan: ' . strtoupper((new \App\Services\AiCreditService())->planFor($record)['code'])),

                Tables\Columns\TextColumn::make('latest_payment')
                    ->label('Transaksi Louvin')
                    ->state(function (User $record) {
                        $payment = $record->payments()->latest()->first();
                        if (!$payment) {
                            return 'Belum ada';
                        }
                        return 'Rp' . number_format($payment->amount) . ' (' . $payment->status_display . ')';
                    })
                    ->description(fn (User $record) => $record->payments()->latest()->first()?->payment_method ?? '—'),

                Tables\Columns\TextColumn::make('anaks_count')
                    ->label('Profil Anak')
                    ->state(fn (User $record) => $record->anaks()->count() . ' anak')
                    ->description(fn (User $record) => $record->anaks->pluck('nama_anak')->implode(', ') ?: 'Belum buat profil'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Bergabung')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                TernaryFilter::make('email_verified_at')
                    ->label('Status Verifikasi Email'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit'   => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
