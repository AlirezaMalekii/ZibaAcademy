<?php

namespace App\Console;

use App\Events\SendAnnouncementNotifications;
use App\Http\Controllers\Api\v1\SuperAdmin\AnnouncementController;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $schedule->command('queue:work --queue=announcements-queue')->everyFiveMinutes()->withoutOverlapping()->runInBackground();
        $schedule->command('queue:work --queue=database-queue')->everyFiveMinutes()->withoutOverlapping()->runInBackground();
        $schedule->command('queue:work --queue=kavenegar-queue')->everyFiveMinutes()->withoutOverlapping()->runInBackground();

        $schedule->job(function () {
            $unAnnouncedAnnouncements = AnnouncementController::unAnnouncedAnnouncements();
            foreach ($unAnnouncedAnnouncements as $unAnnouncedAnnouncement){
                SendAnnouncementNotifications::dispatch($unAnnouncedAnnouncement->id);
            }
        })->everyMinute()->withoutOverlapping();
        //$schedule->command('sitemap:generate')->daily();
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
