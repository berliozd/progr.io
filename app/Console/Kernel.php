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
        $schedule->command('sitemap:generate')->everyFourHours();
        $schedule->command('project:auto_populate_projects')->everyFiveMinutes();
        $schedule->command('project:enrich_projects')->everyFiveMinutes();
        $schedule->command('project:set_metas')->everyFiveMinutes();
        $schedule->command('project:create_project_ideas')->everySixHours();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
