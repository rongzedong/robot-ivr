<?php

namespace App\Console;

use App\Console\Commands\FreeSwitchSipDel;
use App\Console\Commands\FreeSwitchSipReg;
use App\Console\Commands\IvrSignUp;
use App\Jobs\ScanTaskJob;
use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        FreeSwitchSipReg::class,
        FreeSwitchSipDel::class,
        IvrSignUp::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //扫描任务
        $schedule->job(new ScanTaskJob())->withoutOverlapping()->runInBackground();
    }
}
