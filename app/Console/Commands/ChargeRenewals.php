<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use App\Services\PaystackService;
use App\Enum\Plan;
use Carbon\Carbon;

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
    public function handle(PaystackService $rewepaymentService)
    {
        $dateAfters = DB::table('sub_count')->get();
        foreach($dateAfters as $dateAfter){
           $dateAfter = DB::table('sub_count')->where('user_id',$dateAfter->user_id)->first();
        if(!is_null($dateAfter)){
            $d_date = $dateAfter->expires_at;
            if (now()->greaterThan($d_date)){
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
                ->where('sub_count.status', '=','notactive')
                ->first();
              
                if($get_authcode){
                    
                    $r = $rewepaymentService->renewalPayment($get_authcode);
                    if($r == 'success'){

                        if (in_array($get_authcode->subscription_name, [Plan::Basic, Plan::Freesub, Plan::Premium])) {

                            //update the subscription
                         DB::table('sub_count')->where('user_id',$dateAfter->user_id)
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

    
                        }elseif(in_array($get_authcode->subscription_name,[Plan::ForeverBasic,Plan::ForeverStandard,Plan::UnlimitedForever])){

                            //update the subscription
                         DB::table('sub_count')->where('user_id',$dateAfter->user_id)
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


                        }elseif($get_authcode->subscription_name == Plan::EasyBuy){

                            //update the subscription
                         DB::table('sub_count')->where('user_id',$dateAfter->user_id)
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


                        }
                      
                        
                    }
                }

            }

        }

        }
        

        $this->info("Processed renew subscription");
        return 0;
    }
}
