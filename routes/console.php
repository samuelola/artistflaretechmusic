<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
// Artisan::command('inspire', function () {
//     $this->comment(Inspiring::quote());
// })->purpose('Display an inspiring quote');


//Schedule::command('charge:renewals')->dailyAt('14:04')->withoutOverlapping();

Schedule::command('charge:renewals')->everyMinute();

Schedule::command('subscription:reminder')->twiceDaily(9, 21)->withoutOverlapping(); // run at 9am and 9pm also 13(1pm)

Schedule::command('queue:work --stop-when-empty')->everyMinute();




// Schedule::call(function () {
//         file_put_contents(storage_path('/logs/cron-test.log'), now() . " - Cron OK\n", FILE_APPEND);
//     })->everyFiveMinutes();



