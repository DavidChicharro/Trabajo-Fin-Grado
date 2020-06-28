<?php

namespace App\Console;

use App\Http\Controllers\IncidentsController;
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
    	$fileLog = "storage/logs/commands/cron_" . date('Y-m-d_H-i') . ".log";

		$schedule->call(function () {
			$incCtrl = new IncidentsController();
			$incCtrl->calcIncidentsSeverityLevel();
		})->dailyAt('05:00')->appendOutputTo($fileLog);
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
