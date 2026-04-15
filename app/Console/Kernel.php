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

        //Test
            \App\Console\Commands\Test\CheckEnv::class,
            \App\Console\Commands\Test\CheckTest::class,
            \App\Console\Commands\Test\CheckPushNotification::class,
            \App\Console\Commands\CheckMail::class,
            \App\Console\Commands\CheckSms::class,
            \App\Console\Commands\CheckBirthday::class,
            \App\Console\Commands\CheckAnniversary::class,
            \App\Console\Commands\CheckNotification::class,
            \App\Console\Commands\CheckHelp::class,
            \App\Console\Commands\CheckPrayerExpiry::class,
            \App\Console\Commands\CheckPrayerCleanup::class,
            \App\Console\Commands\CheckMailQueue::class,
            \App\Console\Commands\CheckQuote::class,
            \App\Console\Commands\CheckGetResponse::class,
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

        $schedule->command('gego:checksubscription')
                 ->daily()
                 ->withoutOverlapping();

        $schedule->command('gego:checksubscriptionexpired')
                 ->daily()
                 ->withoutOverlapping();

        $schedule->command('gego:checkbirthday')
                 ->daily()
                 ->withoutOverlapping();

        $schedule->command('gego:checkanniversary')
                 ->daily()
                 ->withoutOverlapping();

        $schedule->command('gego:checknotification')
                 ->hourly()
                 ->withoutOverlapping();

        $schedule->command('gego:checksms')
                 ->hourly()
                 ->withoutOverlapping();

        $schedule->command('gego:checkmail')
                 ->hourly()
                 ->withoutOverlapping();

        $schedule->command('gego:checkmailqueue')
                 ->everyminute()
                 ->withoutOverlapping();

        $schedule->command('gego:checkhelp')
                 ->hourly()
                 ->withoutOverlapping();

        $schedule->command('gego:checkprayerexpiry')
                 ->daily()
                 ->withoutOverlapping();

        $schedule->command('gego:checkprayercleanup')
                 ->daily()
                 ->withoutOverlapping();

        $schedule->command('gego:checkquote')
                 ->hourly()
                 ->withoutOverlapping();

        $schedule->command('gego:checkgetresponse')
                 ->daily()
                 ->withoutOverlapping();
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
