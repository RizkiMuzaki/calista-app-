<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\Subscription;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Number;

class UserStatsWidget extends BaseWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 2;
    
    protected int | string | array $columnSpan = [
        'md' => 2,
        'xl' => 3,
    ];

    protected function getStats(): array
    {
        $startDate = $this->filters['startDate'] ?? null;
        $endDate = $this->filters['endDate'] ?? null;

        $totalUsers = User::query()
            ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
            ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
            ->count();

        $usersWithSubscriptions = User::query()
            ->has('subscriptions')
            ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
            ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
            ->count();

        $usersWithActiveSubscriptions = User::query()
            ->whereHas('subscriptions', function ($query) {
                $query->where('status', 'aktif')
                      ->where('tanggal_berakhir', '>', now());
            })
            ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
            ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
            ->count();

        // Total subscriptions
        $totalSubscriptions = Subscription::query()
            ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
            ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
            ->count();

        // Active subscriptions
        $activeSubscriptions = Subscription::query()
            ->where('status', 'aktif')
            ->where('tanggal_berakhir', '>', now())
            ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
            ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
            ->count();

        return [
            Stat::make('Total Pengguna', Number::format($totalUsers))
                ->description('Seluruh pengguna terdaftar')
                ->descriptionIcon('heroicon-o-user-group')
                ->color('primary')
                ->chart([5, 3, 10, 15, 12, 20, 25])
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                ]),

            Stat::make('Pengguna Berlangganan', Number::format($usersWithSubscriptions))
                ->description('Memiliki riwayat langganan')
                ->descriptionIcon('heroicon-o-credit-card')
                ->color('success'),

            Stat::make('Langganan Aktif', Number::format($activeSubscriptions))
                ->description('Status aktif & belum berakhir')
                ->descriptionIcon('heroicon-o-check-badge')
                ->color('warning'),


        ];
    }
}