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
        foreach(['sales' => 'S', 'numbers' => 'N'] as $entity => $option)
        {
            $instances = config("api.schedule.{$entity}.instances");
            $pagesPerInstance = config("api.schedule.{$entity}.pagesPerInstance");

            for($i = 1; $i <= $instances; $i++)
            {
                $pageStart = $i * $pagesPerInstance - $pagesPerInstance + 1;
                $schedule->command("sync -{$option} {$pageStart} {$pagesPerInstance}")->dailyAt('00:00')->runInBackground();
            }
        }
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
