<?php

namespace App\Filament\Widgets;

use App\Models\Module;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Number;

class ModuleStatsWidget extends BaseWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 5;
    protected ?string $heading = 'Statistik Module';
    
    protected int | string | array $columnSpan = [
        'md' => 2,
        'xl' => 2,
    ];

    protected function getStats(): array
    {
        $startDate = $this->filters['startDate'] ?? null;
        $endDate = $this->filters['endDate'] ?? null;

        $totalModules = Module::query()
            ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
            ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
            ->count();
        
        // Hitung berdasarkan type jika ada field type di model
        $modulesByType = Module::query()
            ->select('type')
            ->selectRaw('count(*) as count')
            ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
            ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
            ->groupBy('type')
            ->get();

        $types = $modulesByType->pluck('type')->toArray();
        $counts = $modulesByType->pluck('count')->toArray();

        // Total levels dari semua module
        $totalLevels = Module::withCount('levels')
            ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
            ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
            ->get()
            ->sum('levels_count');

        // Total stories
        $totalStories = \App\Models\Story::count();

        return [
            Stat::make('Total Module', Number::format($totalModules))
                ->description('Seluruh modul pembelajaran')
                ->descriptionIcon('heroicon-m-cube')
                ->color('primary')
                ->extraAttributes([
                    'style' => 'border-top: 4px solid #3b82f6 !important; background: rgba(59, 130, 246, 0.04) !important;',
                ]),

            Stat::make('Jenis Module', count($types))
                ->description('Variasi tipe module')
                ->descriptionIcon('heroicon-m-square-3-stack-3d')
                ->color('info')
                ->extraAttributes([
                    'style' => 'border-top: 4px solid #06b6d4 !important; background: rgba(6, 182, 212, 0.04) !important;',
                ]),

            Stat::make('Total Level', Number::format($totalLevels))
                ->description('Level dari semua module')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('success')
                ->extraAttributes([
                    'style' => 'border-top: 4px solid #10b981 !important; background: rgba(16, 185, 129, 0.04) !important;',
                ]),

            Stat::make('Total Dongeng', Number::format($totalStories))
                ->description('Koleksi dongeng premium')
                ->descriptionIcon('heroicon-m-book-open')
                ->color('warning')
                ->extraAttributes([
                    'style' => 'border-top: 4px solid #f59e0b !important; background: rgba(245, 158, 11, 0.04) !important;',
                ]),
        ];
    }
}