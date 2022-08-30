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
        $schedule->command('biz_process:auto-send-mail')->everyMinute()->runInBackground();
        $schedule->command('biz_process:auto-send-mail 1')->everyMinute()->runInBackground();
        $schedule->command('biz_process:auto-send-mail 2')->everyMinute()->runInBackground();

        $schedule->command('biz_process:reset-schedule')->dailyAt('00:01')->runInBackground();
        $schedule->command('biz_process:clear-last-view')->dailyAt('00:05')->runInBackground();
        $schedule->command('biz_process:data-mail-cart')->hourlyAt(45)->runInBackground();
        $schedule->command('biz_process:data-mail-wishlist')->dailyAt('01:10')->runInBackground();
        $schedule->command('biz_process:data-mail-lastview')->dailyAt('01:15')->runInBackground();
        $schedule->command('biz_process:data-mail-review')->dailyAt('01:30')->runInBackground();
        $schedule->command('biz_process:data-mail-recommed')->dailyAt('01:45')->runInBackground();
        $schedule->command('biz_process:data-mail-ranking')->weeklyOn(2, '02:00')->runInBackground();
        $schedule->command('biz_process:data-mail-type-2')->dailyAt('02:10')->runInBackground();

        $schedule->command('biz_process:customer-rank-calculate')->cron('30 21 5,10,15,20,25 * *')->runInBackground();
        $schedule->command('biz_process:customer-rank-calculate')->lastDayOfMonth('21:30')->runInBackground();

        $schedule->command('biz_process:customer-rank-analytic-calculate')->cron('00 22 5,10,15,20,25 * *')->runInBackground();
        $schedule->command('biz_process:customer-rank-analytic-calculate')->lastDayOfMonth('22:00')->runInBackground();

        $schedule->command('biz_process:transition-rate-calculate')->cron('30 22 5,10,15,20,25 * *')->runInBackground();
        $schedule->command('biz_process:transition-rate-calculate')->lastDayOfMonth('22:30')->runInBackground();

        $schedule->command('biz_process:customer-ltv')->cron('45 22 5,10,15,20,25 * *')->runInBackground();
        $schedule->command('biz_process:customer-ltv')->lastDayOfMonth('22:45')->runInBackground();
        
        $schedule->command('biz_process:revenue-calculate')->cron('00 23 5,10,15,20,25 * *')->runInBackground();
        $schedule->command('biz_process:revenue-calculate')->lastDayOfMonth('23:00')->runInBackground();

        //Clear log
        $schedule->command('process:clear-log-query')->weeklyOn(0, '01:00')->runInBackground();
        $schedule->command('process:clear-mail-sent-content')->weeklyOn(0, '01:10')->runInBackground();

    //===========DF=================

        // $schedule->command('df_process:auto-send-mail')->everyMinute()->runInBackground();
        // $schedule->command('df_process:auto-send-mail 1')->everyMinute()->runInBackground();
        // $schedule->command('df_process:auto-send-mail 2')->everyMinute()->runInBackground();

        $schedule->command('df_process:reset-schedule')->dailyAt('00:01')->runInBackground();
        $schedule->command('df_process:clear-last-view')->dailyAt('00:05')->runInBackground();
        // $schedule->command('df_process:data-mail-cart')->hourlyAt(45)->runInBackground();
        // $schedule->command('df_process:data-mail-wishlist')->dailyAt('01:10')->runInBackground();
        // $schedule->command('df_process:data-mail-lastview')->dailyAt('01:15')->runInBackground();
        // $schedule->command('df_process:data-mail-review')->dailyAt('01:30')->runInBackground();
        // $schedule->command('df_process:data-mail-recommed')->dailyAt('01:45')->runInBackground();
        // $schedule->command('df_process:data-mail-ranking')->weeklyOn(2, '02:00')->runInBackground();
        // $schedule->command('df_process:data-mail-type-2')->dailyAt('02:10')->runInBackground();

        $schedule->command('df_process:customer-rank-calculate')->cron('30 21 5,10,15,20,25 * *')->runInBackground();
        $schedule->command('df_process:customer-rank-calculate')->lastDayOfMonth('21:30')->runInBackground();

        $schedule->command('df_process:customer-rank-analytic-calculate')->cron('00 22 5,10,15,20,25 * *')->runInBackground();
        $schedule->command('df_process:customer-rank-analytic-calculate')->lastDayOfMonth('22:00')->runInBackground();

        $schedule->command('df_process:transition-rate-calculate')->cron('30 22 5,10,15,20,25 * *')->runInBackground();
        $schedule->command('df_process:transition-rate-calculate')->lastDayOfMonth('22:30')->runInBackground();

        $schedule->command('df_process:customer-ltv')->cron('45 22 5,10,15,20,25 * *')->runInBackground();
        $schedule->command('df_process:customer-ltv')->lastDayOfMonth('22:45')->runInBackground();

        $schedule->command('df_process:revenue-calculate')->cron('00 23 5,10,15,20,25 * *')->runInBackground();
        $schedule->command('df_process:revenue-calculate')->lastDayOfMonth('23:00')->runInBackground();


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
