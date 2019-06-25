<?php

namespace App\Console;

use App\Console\Commands\PrepareLogsCommand;
use App\Console\Commands\UpdateMetroStations;
use App\Console\Commands\UpdatePriceListCommand;
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
        PrepareLogsCommand::class,
        UpdatePriceListCommand::class,
        UpdateMetroStations::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('update-price-lists')->everyTenMinutes();
        $schedule->command('logs:prepare')->everyThirtyMinutes();
        $schedule->command('avito:mail-parse')->everyTenMinutes();
        $schedule->command('metro:update-stations')->monthlyOn(26, '02:00');
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
