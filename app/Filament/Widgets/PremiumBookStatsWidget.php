<?php

namespace App\Filament\Widgets;

use App\Models\Story;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PremiumBookStatsWidget extends BaseWidget
{
    protected static ?int $sort = 6;
    protected ?string $heading = 'Statistik Dongeng Premium';
    
    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        // Dongeng aktif dan premium
        $premiumActiveStories = Story::where('is_active', 1)
            ->where('is_premium', 1)
            ->count();

        // Dongeng aktif non-premium
        $freeActiveStories = Story::where('is_active', 1)
            ->where('is_premium', 0)
            ->count();

        // Total Dongeng (semua status)
        $totalStories = Story::count();

        // Dongeng tidak aktif
        $inactiveStories = Story::where('is_active', 0)->count();

        return [
            Stat::make('Dongeng Premium Aktif', $premiumActiveStories)
                ->description('Aktif & premium')
                ->descriptionIcon('heroicon-m-star')
                ->color('warning')
                ->extraAttributes([
                    'style' => 'border-top: 4px solid #f59e0b !important; background: rgba(245, 158, 11, 0.04) !important;',
                ]),

            Stat::make('Dongeng Gratis Aktif', $freeActiveStories)
                ->description('Aktif & gratis')
                ->descriptionIcon('heroicon-m-book-open')
                ->color('success')
                ->extraAttributes([
                    'style' => 'border-top: 4px solid #10b981 !important; background: rgba(16, 185, 129, 0.04) !important;',
                ]),

            Stat::make('Total Dongeng', $totalStories)
                ->description('Seluruh koleksi')
                ->descriptionIcon('heroicon-m-bookmark')
                ->color('primary')
                ->extraAttributes([
                    'style' => 'border-top: 4px solid #3b82f6 !important; background: rgba(59, 130, 246, 0.04) !important;',
                ]),

            Stat::make('Dongeng Tidak Aktif', $inactiveStories)
                ->description('Status tidak aktif')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color('danger')
                ->extraAttributes([
                    'style' => 'border-top: 4px solid #ef4444 !important; background: rgba(239, 68, 68, 0.04) !important;',
                ]),
        ];
    }
}