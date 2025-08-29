<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use App\Services\PaystackService;
use App\Services\SubscriptionMailService;
use App\Enum\Plan;
use Carbon\Carbon;
use Illuminate\Support\Str;

class ChargeRenewals extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'charge:renewals';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Autocharge Subscription';

    /**
     * Execute the console command.
     */
    public function handle(PaystackService $rewepaymentService,SubscriptionMailService $subscriptionMailService)
    {
        $dateAfters = DB::table('sub_count')->get();
        foreach($dateAfters as $dateAfter){
           $dateAfter = DB::table('sub_count')
                            ->where('user_id',$dateAfter->user_id)
                            ->orderBy('id','desc')
                            ->first();
        if(!is_null($dateAfter)){
            // $d_date = Carbon::parse($dateAfter->expires_at)->format("Y-m-d");
             $d_date = $dateAfter->expires_at;
             $reminderDate = Carbon::parse($d_date)->subDays(3);
            if (Carbon::now()->toDateString() === $reminderDate->toDateString()){
               // get authorization code with expired sub details

              $get_authcode = DB::table('user_auto_code')
                ->selectRaw(
                    'user_auto_code.auth_code,
                     subscription_plan.subscription_amount,
                     user_auto_code.user_id,
                     subscription_plan.id,
                     subscription_plan.subscription_name
                    '
                )
                ->join('sub_count','sub_count.user_id', '=', 'user_auto_code.user_id')
                ->join('subscription_plan', 'subscription_plan.id', '=', 'sub_count.subscription_id')
                ->where('sub_count.user_id',$dateAfter->user_id)
                ->where('sub_count.status', '=','active')
                ->first();
              
                if($get_authcode){
                    
                    $r = $rewepaymentService->renewalPayment($get_authcode);
                    if($r == 'success'){
                        $reference = 'REF-' . Str::upper(Str::random(10));
                        if (in_array($get_authcode->subscription_name, [Plan::Basic, Plan::Freesub, Plan::Premium])) {
                         //update the subscription
                         DB::table('sub_count')
                        ->where(['user_id'=>$dateAfter->user_id,'id'=>$dateAfter->id])
                        ->update([
                        'subscription_id' => $get_authcode->id,
                        'status' => 'active',
                        'start_date' => now(),
                        'expires_at' => now()->addYear()
                        ]);

                        $amount = $get_authcode->subscription_amount;
                        $newamount = (int)$amount;

                        DB::table('transactions')->insert([
                            'reference' => $reference ?? 'Not Available',
                            'amount' => $newamount,
                            'user_id' => $dateAfter->user_id,
                            'subscription_id' => $get_authcode->id,
                            'status' => 'success',
                            'paid_at' => now(), 
                            'remarks' => 'Subscription Payment',
                            'gateway' => 'System-Wallet',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);

                        // send email for renewal of subscription 

                          $subscriptionMailService->sendRenewalMail($dateAfter->user_id);

                        }elseif(in_array($get_authcode->subscription_name,[Plan::ForeverBasic,Plan::ForeverStandard,Plan::UnlimitedForever])){

                            //update the subscription
                            DB::table('sub_count')
                            ->where(['user_id'=>$dateAfter->user_id,'id'=>$dateAfter->id])
                            ->update([
                            'subscription_id' => $get_authcode->id,
                            'status' => 'active',
                            'start_date' => now(),
                            
                            ]);

                        $amount = $get_authcode->subscription_amount;
                        $newamount = (int)$amount;

                        DB::table('transactions')->insert([
                            'reference' => $reference ?? 'Not Available',
                            'amount' => $newamount,
                            'user_id' => $dateAfter->user_id,
                            'subscription_id' => $get_authcode->id,
                            'status' => 'success',
                            'paid_at' => now(), 
                            'remarks' => 'Subscription Payment',
                            'gateway' => 'System-Wallet',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);

                        // send email for renewal of subscription 

                          $subscriptionMailService->sendRenewalMail($dateAfter->user_id);

                        }elseif($get_authcode->subscription_name == Plan::EasyBuy){

                        //update the subscription
                        DB::table('sub_count')
                        ->where(['user_id'=>$dateAfter->user_id,'id'=>$dateAfter->id])
                        ->update([
                        'subscription_id' => $get_authcode->id,
                        'status' => 'active',
                        'start_date' => now(),
                        'expires_at' => now()->addMonth()
                        ]);

                        $amount = $get_authcode->subscription_amount;
                        $newamount = (int)$amount;

                        DB::table('transactions')->insert([
                            'reference' => $reference ?? 'Not Available',
                            'amount' => $newamount,
                            'user_id' => $dateAfter->user_id,
                            'subscription_id' => $get_authcode->id,
                            'status' => 'success',
                            'paid_at' => now(), 
                            'remarks' => 'Subscription Payment',
                            'gateway' => 'System-Wallet',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);

                        // send email for renewal of subscription 

                          $subscriptionMailService->sendRenewalMail($dateAfter->user_id);

                        }
                       

                    }
                }

            }

        }

        $this->info("Charge for auto renewal");

        }
        

        // $this->info("Processed renew subscription");
        return 0;
        
    }
}
