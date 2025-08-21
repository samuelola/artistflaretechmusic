<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\DB;
// Artisan::command('inspire', function () {
//     $this->comment(Inspiring::quote());
// })->purpose('Display an inspiring quote');

$get_not_active = DB::table('sub_count')->where('status','notactive')->count();

if($get_not_active > 0){
   Schedule::command('charge:renewals')->everyMinute();
}

