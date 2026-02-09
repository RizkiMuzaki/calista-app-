<?php

namespace App\Filament\Widgets;

use App\Models\Book;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PremiumBookStatsWidget extends BaseWidget
{
    protected static ?int $sort = 6;
    protected ?string $heading = 'Statistik Buku Premium';
    
    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        // Buku aktif dan premium
        $premiumActiveBooks = Book::where('is_active', 1)
            ->where('is_premium', 1)
            ->count();

        // Buku aktif non-premium
        $freeActiveBooks = Book::where('is_active', 1)
            ->where('is_premium', 0)
            ->count();

        // Total buku (semua status)
        $totalBooks = Book::count();

        // Buku tidak aktif
        $inactiveBooks = Book::where('is_active', 0)->count();

        // Buku dengan halaman terbanyak
        $bookWithMostPages = Book::withCount('pages')
            ->orderBy('pages_count', 'desc')
            ->first();

        return [
            Stat::make('Buku Premium Aktif', $premiumActiveBooks)
                ->description('Aktif & premium')
                ->descriptionIcon('heroicon-m-star')
                ->color('warning'),

            Stat::make('Buku Gratis Aktif', $freeActiveBooks)
                ->description('Aktif & gratis')
                ->descriptionIcon('heroicon-m-book-open')
                ->color('success'),

            Stat::make('Total Buku', $totalBooks)
                ->description('Seluruh koleksi')
                ->descriptionIcon('heroicon-m-bookmark')
                ->color('primary'),

            Stat::make('Buku Tidak Aktif', $inactiveBooks)
                ->description('Status tidak aktif')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color('danger'),
        ];
    }
}