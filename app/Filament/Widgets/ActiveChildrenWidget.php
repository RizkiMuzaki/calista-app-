<?php

namespace App\Filament\Widgets;

use App\Models\Anak;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Number;

class ActiveChildrenWidget extends BaseWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 3;
    protected ?string $heading = 'Statistik Anak Aktif';
    
    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        $startDate = $this->filters['startDate'] ?? null;
        $endDate = $this->filters['endDate'] ?? null;

        $totalAnak = Anak::query()
            ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
            ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
            ->count();

        $activeAnak = Anak::query()
            ->where('is_active', true)
            ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
            ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
            ->count();

        $anakWithTimeRemaining = Anak::query()
            ->where('sisa_detik', '>', 0)
            ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
            ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
            ->count();

        // Rata-rata sisa waktu
        $averageRemainingTime = Anak::query()
            ->where('sisa_detik', '>', 0)
            ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
            ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
            ->avg('sisa_detik');

        $formattedAverageTime = $this->formatSeconds($averageRemainingTime);

        // Anak dengan timer berjalan
        $anakWithRunningTimer = Anak::query()
            ->whereNotNull('timer_started_at')
            ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
            ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
            ->count();

        // Total waktu tersisa
        $totalRemainingTime = Anak::query()
            ->where('sisa_detik', '>', 0)
            ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
            ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
            ->sum('sisa_detik');

        $formattedTotalTime = $this->formatSeconds($totalRemainingTime);

        return [
            Stat::make('Total Anak', Number::format($totalAnak))
                ->description('Seluruh anak terdaftar')
                ->descriptionIcon('heroicon-m-user')
                ->color('primary')
                ->extraAttributes([
                    'style' => 'border-top: 4px solid #3b82f6 !important; background: rgba(59, 130, 246, 0.04) !important;',
                ]),

            Stat::make('Anak Aktif', Number::format($activeAnak))
                ->description('Status aktif')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success')
                ->extraAttributes([
                    'style' => 'border-top: 4px solid #10b981 !important; background: rgba(16, 185, 129, 0.04) !important;',
                ]),

            Stat::make('Waktu Tersisa', Number::format($anakWithTimeRemaining))
                ->description("Rata-rata: {$formattedAverageTime}")
                ->descriptionIcon('heroicon-m-clock')
                ->color('info')
                ->extraAttributes([
                    'style' => 'border-top: 4px solid #06b6d4 !important; background: rgba(6, 182, 212, 0.04) !important;',
                ]),

            Stat::make('Timer Berjalan', Number::format($anakWithRunningTimer))
                ->description("Total waktu: {$formattedTotalTime}")
                ->descriptionIcon('heroicon-m-play-circle')
                ->color('warning')
                ->extraAttributes([
                    'style' => 'border-top: 4px solid #f59e0b !important; background: rgba(245, 158, 11, 0.04) !important;',
                ]),
        ];
    }

    private function formatSeconds($seconds): string
    {
        if (!$seconds) return '0:00';
        
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $secs = $seconds % 60;
        
        if ($hours > 0) {
            return sprintf('%d:%02d:%02d', $hours, $minutes, $secs);
        }
        
        return sprintf('%d:%02d', $minutes, $secs);
    }
}