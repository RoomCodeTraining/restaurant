<?php

namespace App\Console;

use App\Console\Commands\ChargeUsers;
use App\Console\Commands\DeleteTemporaryCards;
use App\Console\Commands\GenerateBreakfastOrders;
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
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command(ChargeUsers::class)->dailyAt(config('cantine.order.charge_at'));
        $schedule->command(GenerateBreakfastOrders::class)->everyMinute();
        $schedule->command(DeleteTemporaryCards::class)->dailyAt('01:00');
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
