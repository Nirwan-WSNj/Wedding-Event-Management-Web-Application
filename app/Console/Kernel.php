<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Process sync queue every minute
        $schedule->command('sync:process')
                 ->everyMinute()
                 ->withoutOverlapping()
                 ->runInBackground();

        // Clean up old completed sync items daily
        $schedule->command('sync:cleanup')
                 ->daily()
                 ->at('02:00');

        // Reset stuck processing items every 5 minutes
        $schedule->command('sync:reset-stuck')
                 ->everyFiveMinutes();

        // Generate sync queue statistics hourly
        $schedule->command('sync:stats')
                 ->hourly();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}