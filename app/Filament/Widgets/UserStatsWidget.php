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
    
    protected ?string $heading = 'Statistik Pengguna & Langganan';
    
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
                ->descriptionIcon('heroicon-m-user-group')
                ->color('primary')
                ->extraAttributes([
                    'style' => 'border-top: 4px solid #3b82f6 !important; background: rgba(59, 130, 246, 0.04) !important;',
                ]),

            Stat::make('Pengguna Berlangganan', Number::format($usersWithSubscriptions))
                ->description('Memiliki riwayat langganan')
                ->descriptionIcon('heroicon-m-credit-card')
                ->color('success')
                ->extraAttributes([
                    'style' => 'border-top: 4px solid #10b981 !important; background: rgba(16, 185, 129, 0.04) !important;',
                ]),

            Stat::make('Langganan Aktif', Number::format($activeSubscriptions))
                ->description('Status aktif & belum berakhir')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('warning')
                ->extraAttributes([
                    'style' => 'border-top: 4px solid #f59e0b !important; background: rgba(245, 158, 11, 0.04) !important;',
                ]),
        ];
    }
}