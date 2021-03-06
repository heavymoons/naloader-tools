<?php

namespace App\Console;

use App\Console\Commands\CrawlAll;
use App\Console\Commands\DumpAll;
use App\Console\Commands\RegisterAuthorUrl;
use App\Console\Commands\RegisterNovelUrl;
use App\Console\Commands\RetrieveNovelUrl;
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
        RegisterNovelUrl::class,
        RegisterAuthorUrl::class,
        CrawlAll::class,
        DumpAll::class,
        RetrieveNovelUrl::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
