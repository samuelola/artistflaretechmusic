<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SubscriptionMailService;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class NewSubscription extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscription:created';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Email for new Subscription';

    /**
     * Execute the console command.
     */
    public function handle(SubscriptionMailService $subscriptionMailService)
    {
        
        $subscribes = DB::table('sub_count')->get();
        foreach ($subscribes as $subscribe) {
             $dateAfter = DB::table('sub_count')
                              ->where('user_id',$subscribe->user_id)
                              ->orderBy('id','desc')
                              ->first();
             if(!is_null($dateAfter)){

                if($dateAfter->status == 'active'){
                   $getUserInfo = DB::table('users')->where('id',$subscribe->user_id)->first();
                   $subscriptionMailService->sendSubMail($getUserInfo);
                }
                
             }
        }

        $this->info('Subscription emails sent successfully.');
        return 0;
    }
}
