<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Widgets\Widget;
use Filament\Widgets\WidgetConfiguration;

class Dashboard extends BaseDashboard
{
    /**
     * @return array<class-string<Widget> | WidgetConfiguration>
     */
    public function getWidgets(): array
    {
        return [
            \App\Filament\Widgets\RevenuePlanStatsWidget::class,
            \App\Filament\Widgets\UserStatsWidget::class,
            \App\Filament\Widgets\ActiveChildrenWidget::class,
            \App\Filament\Widgets\GameStatsWidget::class,
            \App\Filament\Widgets\ModuleStatsWidget::class,
            \App\Filament\Widgets\PremiumBookStatsWidget::class,
        ];
    }

    /**
     * @return int | string | array<string, int | string | null>
     */
    public function getColumns(): int | string | array
    {
        return 2;
    }
}
