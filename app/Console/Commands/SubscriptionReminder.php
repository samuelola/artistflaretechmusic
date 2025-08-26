<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SubscriptionMailService;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SubscriptionReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscription:reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Email Subscription Reminder';

    /**
     * Execute the console command.
     */
    public function handle(SubscriptionMailService $subscriptionMailService)
    {

        $subscribes = DB::table('sub_count')->get();
        foreach ($subscribes as $subscribe) {
             $dateAfter = DB::table('sub_count')->where('user_id',$subscribe->user_id)->first();
             if(!is_null($dateAfter)){
                 $d_date = $dateAfter->expires_at;
                 $reminderDate = Carbon::parse($d_date)->subDays(3);
                 if (Carbon::now()->toDateString() === $reminderDate->toDateString()) {
                     $getUserInfo = DB::table('users')->where('id',$subscribe->user_id)->first(); 
                     $subscriptionMailService->sendExpireReminderMail($getUserInfo);
                 }
             }
        }

        $this->info('Expiration reminder emails sent successfully.');
        
    }
}
