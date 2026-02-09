<?php

namespace App\Filament\Widgets;

use App\Models\Payment;
use App\Models\Plan;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Number;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RevenuePlanStatsWidget extends BaseWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 1;
    
    protected int | string | array $columnSpan = [
        'md' => 2,
        'xl' => 3,
    ];

    protected function getStats(): array
    {
        $startDate = $this->filters['startDate'] ?? null;
        $endDate = $this->filters['endDate'] ?? null;

        $stats = [];

        // Total pendapatan dari payment aktif (UNPAID, PENDING) dengan plan_id
        $totalRevenue = Payment::query()
            ->whereNotNull('plan_id')
            ->where('status', 'PAID')
            ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
            ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
            ->sum('amount_received');

        // Jumlah payment aktif dengan plan
        $activePaymentsCount = Payment::query()
            ->whereNotNull('plan_id')
            ->where('status', 'PAID')
            ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
            ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
            ->count();

        // Jumlah unique plan yang aktif
        $uniquePlansCount = Payment::query()
            ->whereNotNull('plan_id')
            ->where('status', 'PAID')
            ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
            ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
            ->distinct('plan_id')
            ->count('plan_id');

        // Revenue per bulan
        $revenueByMonth = Payment::query()
            ->whereNotNull('plan_id')
            ->where('status', 'PAID')
            ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
            ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
            ->select(DB::raw('DATE_FORMAT(created_at, "%Y-%m") as bulan'))
            ->selectRaw('SUM(amount_received) as total_revenue')
            ->selectRaw('COUNT(*) as payment_count')
            ->groupBy(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'))
            ->orderByDesc('bulan')
            ->get();

        // Revenue per plan
        $revenuePerPlan = Payment::query()
            ->whereNotNull('plan_id')
            ->where('status', 'PAID')
            ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
            ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
            ->select('plan_id')
            ->selectRaw('SUM(amount_received) as total_revenue')
            ->selectRaw('COUNT(*) as payment_count')
            ->groupBy('plan_id')
            ->orderByDesc('total_revenue')
            ->get();

        // Add stats for revenue by month FIRST
        foreach ($revenueByMonth as $monthRevenue) {
            $bulanFormatted = Carbon::createFromFormat('Y-m', $monthRevenue->bulan)->translatedFormat('F Y');
            $stats[] = Stat::make(
                'Pendapatan ' . $bulanFormatted,
                'Rp ' . Number::format($monthRevenue->total_revenue, locale: 'id')
            )
                ->description($monthRevenue->payment_count . ' transaksi')
                ->descriptionIcon('heroicon-o-calendar')
                ->color('info')
                ->extraAttributes([
                    'class' => 'col-span-2',
                ]);
        }

        // Add stats for each plan
        foreach ($revenuePerPlan as $planRevenue) {
            $plan = Plan::find($planRevenue->plan_id);
            if ($plan) {
                $stats[] = Stat::make(
                    'Plan: ' . $plan->nama_paket,
                    'Rp ' . Number::format($planRevenue->total_revenue, locale: 'id')
                )
                    ->description($planRevenue->payment_count . ' transaksi')
                    ->descriptionIcon('heroicon-o-tag')
                    ->color('primary');
            }
        }

        return $stats;
    }
}