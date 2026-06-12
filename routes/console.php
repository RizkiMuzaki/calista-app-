<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Jadwalkan pengiriman laporan belajar mingguan otomatis setiap hari Minggu jam 20:00
Schedule::command('app:send-weekly-reports')->weeklyOn(7, '20:00');
