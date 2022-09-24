<?php

namespace App\Console;

use App\Models\Notification;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        Commands\MailNotification::class,

    ];

    protected function schedule(Schedule $schedule)
    {
         $schedule->command('mail:notification')->cron('*/' . Notification::CRON_NOTIFICATION_TIME . ' * * * *');
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
