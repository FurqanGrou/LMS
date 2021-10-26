<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
//         $schedule->command('inspire')->hourly();

            $schedule->command('teacher:notify')->twiceDaily(4, 10);
            $schedule->command('teacher:notify')->twiceDaily(14, 18);
            $schedule->command('teacher:notify')->twiceDaily(21);
            // $schedule->command('teacher:notify')->everyMinute();

        // this command will execution daily by (Cron Job)
//         $schedule->exec('php artisan expired:ads');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
