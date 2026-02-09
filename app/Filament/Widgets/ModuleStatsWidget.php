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

        // Total books dari semua module
        $totalBooks = Module::withCount('books')
            ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
            ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
            ->get()
            ->sum('books_count');

        return [
            Stat::make('Total Module', Number::format($totalModules))
                ->description('Seluruh modul pembelajaran')
                ->descriptionIcon('heroicon-o-cube')
                ->color('primary'),

            Stat::make('Jenis Module', count($types))
                ->description('Variasi tipe module')
                ->descriptionIcon('heroicon-o-square-3-stack-3d')
                ->color('info'),

            Stat::make('Total Level', Number::format($totalLevels))
                ->description('Level dari semua module')
                ->descriptionIcon('heroicon-o-chart-bar')
                ->color('success'),

            Stat::make('Total Buku', Number::format($totalBooks))
                ->description('Buku dari semua module')
                ->descriptionIcon('heroicon-o-book-open')
                ->color('warning'),
        ];
    }
}