<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AnakResource\Pages;
use App\Models\Anak;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Illuminate\Support\Number;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use App\Models\PlaySession;
use App\Models\Mood;

class AnakResource extends Resource
{
    protected static ?string $model = Anak::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationLabel = 'Profil Anak';
    protected static ?string $modelLabel = 'Anak';
    protected static ?string $pluralModelLabel = 'Profil Anak';
    protected static ?string $navigationGroup = 'Manajemen Pengguna';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Data Anak')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('Akun Orang Tua')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\TextInput::make('nama_anak')
                            ->label('Nama Anak')
                            ->required()
                            ->maxLength(100),

                        Forms\Components\DatePicker::make('tanggal_lahir')
                            ->label('Tanggal Lahir')
                            ->required(),

                        Forms\Components\Select::make('jenis_kelamin')
                            ->label('Jenis Kelamin')
                            ->options([
                                'L' => 'Laki-laki',
                                'P' => 'Perempuan',
                            ])
                            ->required(),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Status Aktif')
                            ->default(true),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Pengaturan Timer Harian')
                    ->schema([
                        Forms\Components\TextInput::make('limit_detik')
                            ->label('Limit Waktu Harian (menit)')
                            ->numeric()
                            ->default(60)
                            ->formatStateUsing(fn ($state) => $state ? round($state / 60) : 0)
                            ->dehydrateStateUsing(fn ($state) => $state ? $state * 60 : 0)
                            ->helperText('Diinput dalam menit, disimpan ke database dalam detik (contoh: 60 = 1 jam).'),

                        Forms\Components\TextInput::make('sisa_detik')
                            ->label('Sisa Waktu Hari Ini (menit)')
                            ->numeric()
                            ->default(60)
                            ->formatStateUsing(fn ($state) => $state ? round($state / 60) : 0)
                            ->dehydrateStateUsing(fn ($state) => $state ? $state * 60 : 0)
                            ->helperText('Diinput dalam menit, disimpan ke database dalam detik (contoh: 60 = 1 jam).'),

                        Forms\Components\DatePicker::make('tanggal_reset')
                            ->label('Tanggal Reset Terakhir')
                            ->nullable(),
                    ])
                    ->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_anak')
                    ->label('Nama Anak')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Orang Tua')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.email')
                    ->label('Email Orang Tua')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('jenis_kelamin')
                    ->label('Gender')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state === 'L' ? 'Laki-laki' : 'Perempuan')
                    ->color(fn ($state) => $state === 'L' ? 'info' : 'danger'),

                Tables\Columns\TextColumn::make('tanggal_lahir')
                    ->label('Tanggal Lahir')
                    ->date('d/m/Y')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),

                Tables\Columns\TextColumn::make('sisa_detik')
                    ->label('Sisa Waktu')
                    ->formatStateUsing(function ($state) {
                        if (!$state) return '0:00';
                        $hours = floor($state / 3600);
                        $minutes = floor(($state % 3600) / 60);
                        $secs = $state % 60;
                        return $hours > 0
                            ? sprintf('%d jam %02d mnt', $hours, $minutes)
                            : sprintf('%d mnt %02d dtk', $minutes, $secs);
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('limit_detik')
                    ->label('Limit Harian')
                    ->formatStateUsing(function ($state) {
                        if (!$state) return '-';
                        $hours = floor($state / 3600);
                        $minutes = floor(($state % 3600) / 60);
                        return $hours > 0 ? "{$hours} jam" : "{$minutes} mnt";
                    }),

                Tables\Columns\IconColumn::make('timer_started_at')
                    ->label('Timer Berjalan?')
                    ->boolean()
                    ->trueIcon('heroicon-o-play-circle')
                    ->falseIcon('heroicon-o-stop-circle')
                    ->trueColor('success')
                    ->falseColor('gray'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Status Aktif'),

                SelectFilter::make('jenis_kelamin')
                    ->label('Jenis Kelamin')
                    ->options([
                        'L' => 'Laki-laki',
                        'P' => 'Perempuan',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('unduhLaporanMingguan')
                        ->label('📄 PDF Mingguan')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->color('info')
                        ->url(fn (Anak $record) => route('admin.anak.pdf', [
                            'childId' => $record->id,
                            'period'  => 'week',
                        ]))
                        ->openUrlInNewTab(),
                    Tables\Actions\Action::make('unduhLaporanBulanan')
                        ->label('📊 PDF Bulanan')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->color('warning')
                        ->url(fn (Anak $record) => route('admin.anak.pdf', [
                            'childId' => $record->id,
                            'period'  => 'month',
                        ]))
                        ->openUrlInNewTab(),
                ])->label('Laporan PDF')->icon('heroicon-o-document-chart-bar'),
                Tables\Actions\Action::make('kirimLaporan')
                    ->label('Kirim Laporan')
                    ->icon('heroicon-o-envelope')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Kirim Laporan Belajar?')
                    ->modalDescription('Tindakan ini akan mengirimkan email laporan rekapitulasi belajar anak selama 7 hari terakhir ke orang tua.')
                    ->action(function (Anak $record) {
                        $user = $record->user;
                        if (!$user || !$user->email) {
                            \Filament\Notifications\Notification::make()
                                ->title('Email Orang Tua Tidak Ditemukan')
                                ->danger()
                                ->send();
                            return;
                        }

                        $sent = \App\Services\EmailService::sendWeeklyReportEmail($user->email, $user->name, $record, true);
                        if ($sent) {
                            \Filament\Notifications\Notification::make()
                                ->title('Laporan Belajar Terkirim')
                                ->success()
                                ->body("Berhasil mengirim laporan belajar {$record->nama_anak} ke {$user->email}.")
                                ->send();
                        } else {
                            \Filament\Notifications\Notification::make()
                                ->title('Gagal Mengirim Laporan')
                                ->danger()
                                ->body('Periksa logs mail untuk detail error.')
                                ->send();
                        }
                    }),
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
            'index'  => Pages\ListAnaks::route('/'),
            'create' => Pages\CreateAnak::route('/create'),
            'edit'   => Pages\EditAnak::route('/{record}/edit'),
        ];
    }
}
