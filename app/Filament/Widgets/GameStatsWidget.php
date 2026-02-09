<?php

namespace App\Filament\Widgets;

use App\Models\Game;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Number;

class GameStatsWidget extends BaseWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 4;
    protected ?string $heading = 'Statistik Game';
    
    protected int | string | array $columnSpan = [
        'md' => 2,
        'xl' => 2,
    ];

    protected function getStats(): array
    {
        $startDate = $this->filters['startDate'] ?? null;
        $endDate = $this->filters['endDate'] ?? null;

        $totalGames = Game::query()
            ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
            ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
            ->count();

        $activeGames = Game::query()
            ->where('status', 1)
            ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
            ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
            ->count();

        $inactiveGames = Game::query()
            ->where('status', 0)
            ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
            ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
            ->count();

        // Games with hadiah (prizes)
        $gamesWithPrizes = Game::query()
            ->has('hadiahs')
            ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
            ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
            ->count();

        return [
            Stat::make('Total Game', Number::format($totalGames))
                ->description('Seluruh game')
                ->descriptionIcon('heroicon-o-puzzle-piece')
                ->color('primary')
                ->chart([2, 3, 5, 7, 10, 12, 15])
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                ]),

            Stat::make('Game Aktif', Number::format($activeGames))
                ->description('Status aktif (1)')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success'),

            Stat::make('Game Non-Aktif', Number::format($inactiveGames))
                ->description('Status tidak aktif (0)')
                ->descriptionIcon('heroicon-o-x-circle')
                ->color('danger'),

            Stat::make('Game dengan Hadiah', Number::format($gamesWithPrizes))
                ->description('Memiliki hadiah')
                ->descriptionIcon('heroicon-o-gift')
                ->color('warning'),
        ];
    }
}