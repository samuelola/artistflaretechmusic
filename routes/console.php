<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
// Artisan::command('inspire', function () {
//     $this->comment(Inspiring::quote());
// })->purpose('Display an inspiring quote');


Schedule::command('charge:renewals')->everyMinute()->withoutOverlapping(); 
//laravel check every minute send out emails for new subscription
// Schedule::command('subscription:created')->everyMinute();
// Schedule::command('subscription:reminder')->twiceMonthly(1, 26, '8:42');
//laravel check daily send out reminders based on plan rules
//Schedule::command('subscription:reminder')->dailyAt('01:00')->withoutOverlapping();

//Stop After First Run (One-Time Execution)

// Schedule::command('charge:renewals')
//         ->everyMinute()
//         ->withoutOverlapping()
//         ->when(function () {
//             // Run only if it hasn’t run before
//             if (! cache()->has('charge:renewals')) {
//                 cache()->forever('charge:renewals', true);
//                 return true;
//             }
//             return false;
//         });

Schedule::command('subscription:created')
        ->everyMinute()
        ->when(function () {
            // Run only if it hasn’t run before
            if (! cache()->has('subscription:created')) {
                cache()->forever('subscription:created', true);
                return true;
            }
            return false;
        });


Schedule::command('subscription:reminder')
        ->everyMinute()
        ->when(function () {
            // Run only if it hasn’t run before
            if (! cache()->has('subscription:reminder')) {
                cache()->forever('subscription:reminder', true);
                return true;
            }
            return false;
        });

Schedule::command('queue:work --stop-when-empty')->everyMinute();

