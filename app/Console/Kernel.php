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
        // Import numbers
        $schedule->command("sync -N 1 9")->dailyAt('00:00')->timezone('Asia/Yekaterinburg')->runInBackground();
        $schedule->command("sync -N 10 9")->dailyAt('00:00')->timezone('Asia/Yekaterinburg')->runInBackground();
        $schedule->command("sync -N 19 9")->dailyAt('00:00')->timezone('Asia/Yekaterinburg')->runInBackground();
        $schedule->command("sync -N 28 9")->dailyAt('00:00')->timezone('Asia/Yekaterinburg')->runInBackground();
        $schedule->command("sync -N 37 9")->dailyAt('00:00')->timezone('Asia/Yekaterinburg')->runInBackground();
        $schedule->command("sync -N 46 9")->dailyAt('00:00')->timezone('Asia/Yekaterinburg')->runInBackground();
        $schedule->command("sync -N 55 9")->dailyAt('00:00')->timezone('Asia/Yekaterinburg')->runInBackground();
        $schedule->command("sync -N 64 9")->dailyAt('00:00')->timezone('Asia/Yekaterinburg')->runInBackground();

        // Clear sales
        $schedule->command("clearSales")->dailyAt('00:00')->timezone('Asia/Yekaterinburg')->runInBackground();

        // Import sales
        $schedule->command("sync -S 1 9")->dailyAt('00:05')->timezone('Asia/Yekaterinburg')->runInBackground();
        $schedule->command("sync -S 10 9")->dailyAt('00:05')->timezone('Asia/Yekaterinburg')->runInBackground();
        $schedule->command("sync -S 19 9")->dailyAt('00:05')->timezone('Asia/Yekaterinburg')->runInBackground();
        $schedule->command("sync -S 28 9")->dailyAt('00:05')->timezone('Asia/Yekaterinburg')->runInBackground();
        $schedule->command("sync -S 37 9")->dailyAt('00:05')->timezone('Asia/Yekaterinburg')->runInBackground();
        $schedule->command("sync -S 46 9")->dailyAt('00:05')->timezone('Asia/Yekaterinburg')->runInBackground();
        $schedule->command("sync -S 55 9")->dailyAt('00:05')->timezone('Asia/Yekaterinburg')->runInBackground();
        $schedule->command("sync -S 64 9")->dailyAt('00:05')->timezone('Asia/Yekaterinburg')->runInBackground();

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
