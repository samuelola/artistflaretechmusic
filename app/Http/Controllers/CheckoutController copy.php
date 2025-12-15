<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Services\CheckoutService;
use App\Services\SubscriptionMailService;
use Session;
use DB;
use App\Models\Subscription;
use App\Enum\MinimumBalance;
use App\Enum\Plan;
use App\Enum\UserStatus;
use Illuminate\Support\Facades\Log;
use App\Exceptions\FailedCheckoutException;
use Illuminate\Support\Str;
use App\Enum\SubscriptionPurchase;
use Illuminate\Support\Facades\Cache;


class CheckoutController extends Controller
{


    public $checkoutService;

    public function __construct(CheckoutService $checkoutService){

        $this->checkoutService = $checkoutService;
    }

    public function checkoutSubscription(Request $request)
    {
        $sub_id = $request->sub_id;
        return redirect()->route('checkout_details',['id'=>$sub_id]);
        // $rel = (new CheckoutService)->checkforwallet();
        // if($rel){
        //     $amount = MinimumBalance::Min;
        //     session()->flash('error', "Your balance is too low for this subscription,need a minimium of &#8358;{$amount} topup");
            
        //     return redirect()->back();
        // }else{
        //     return redirect()->route('checkout_details',['id'=>$sub_id]);
        // }
        
    }

    public function checkoutDetails(Request $request, $id)
    {
       $sub_details = DB::table('subscription_plan')->where('id',$id)->first();
       $currencyExchangeRate = DB::table('currency')->where('code','NGN')->first();
       return view('dashboard.pages.checkout_details',compact('sub_details','currencyExchangeRate'));
    }

    public function checkoutPayment(Request $request)
    {
    
         $sub_id = $request->subc_id;
         $user_id = $request->user_id;
         $amount = MinimumBalance::Min;

         //get All sub details
         $sub_detail = Subscription::where('id',$sub_id)->first();
         //check wallet minimium bal
         $checkwallet = DB::table('user_wallet')->where('user_id',$user_id)->first();
         $rell = $this->checkoutService->checkwalletPayment($checkwallet);
         if($rell){
            session()->flash('error', "Your balance is too low for this subscription,need a minimium of &#8358;{$amount} topup");
            return redirect()->back();
         }
         $total_balance = $checkwallet->balance + $checkwallet->minimium_balance;
         $rel_tot_bal = $this->checkoutService->checktotalbalance($total_balance,$sub_detail);
         if($rel_tot_bal){
            session()->flash('error', "Your balance is low for this subscription,you need a subscription amount of &#8358;{$sub_detail->subscription_amount}");
            return redirect()->back();
         }

         // charge wallet
         $this->chargeTheWallet($total_balance,$sub_detail,$user_id);

         // add coins for subscription
         $check_stats = DB::table('user_statistics')->where('user_id',auth()->user()->id)->first();
         if(is_null($check_stats->sub_purchase)){
               DB::table('user_statistics')
                     ->where('user_id', auth()->user()->id)
                     ->update(['sub_purchase'=>SubscriptionPurchase::SubPurchase]);
            }else{
               DB::table('user_statistics')
                     ->where('user_id', auth()->user()->id)
                     ->increment('sub_purchase',SubscriptionPurchase::SubPurchase);
            }

         // update role
         DB::table('users')->where('id',$user_id)->update([
            'role_id'=> UserStatus::Artist
         ]);
        
         //add subscription with date 
      //   $allowedPlans = Cache::remember(Plan::$cacheKey, now()->addMinutes(30), function () {
      //       return Plan::where('status', 'active')
      //          ->pluck('subscription_name')
      //          ->toArray();
      //    }); 
        $reference = 'REF-' . Str::upper(Str::random(10));
        if (in_array($sub_detail->subscription_name, [Plan::Basic, Plan::Freesub, Plan::Premium])) {

                DB::table('sub_count')->insert([
                'user_id' => $user_id,
                'subscription_id' => $sub_id,
                'status' => 'active',
                'start_date' => now(),
                'expires_at' => now()->addYear()
              ]);

              DB::table('transactions')->insert([

                'reference' => $reference ?? 'NULL',
                'amount' => $sub_detail->subscription_amount,
                'user_id' => auth()->user()->id,
                'subscription_id' => $sub_id,
                'status' => 'success',
                'currency' => $currency ?? 'NULL',
                'paid_at' => now(), 
                'remarks' => 'Subscription Payment',
                'gateway' => 'System-Wallet',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        elseif(in_array($sub_detail->subscription_name,[Plan::ForeverBasic,Plan::ForeverStandard,Plan::UnlimitedForever])){
                  DB::table('sub_count')->insert([
                'user_id' => $user_id,
                'subscription_id' => $sub_id,
                'status' => 'active',
                'start_date' => now(),
              ]);

              DB::table('transactions')->insert([

                'reference' => $reference ?? 'NULL',
                'amount' => $sub_detail->subscription_amount,
                'user_id' => auth()->user()->id,
                'subscription_id' => $sub_id,
                'status' => 'success',
                'currency' => $currency ?? 'NULL',
                'paid_at' => now(), 
                'remarks' => 'Subscription Payment',
                'gateway' => 'System-Wallet',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

        }
        elseif($sub_detail->subscription_name == Plan::EasyBuy){
           
                DB::table('sub_count')->insert([
                'user_id' => $user_id,
                'subscription_id' => $sub_id,
                'status' => 'active',
                'start_date' => now(),
                'expires_at' => now()->addMonth()
              ]);
              
              DB::table('transactions')->insert([

                'reference' => $reference ?? 'NULL',
                'amount' => $sub_detail->subscription_amount,
                'user_id' => auth()->user()->id,
                'subscription_id' => $sub_id,
                'status' => 'success',
                'currency' => $currency ?? 'NULL',
                'paid_at' => now(), 
                'remarks' => 'Subscription Payment',
                'gateway' => 'System-Wallet',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

       
         // send email here 
            $user = auth()->user();
            $send_email_sub = (new SubscriptionMailService())->sendSubMail($user);

         return redirect()->route('dashboard');

        
    }

    public function checkoutCoinPayment(Request $request){

         $sub_id = $request->subc_id;
         $user_id = $request->user_id;
         $rel_tot_bal = $this->checkoutService->checktotalcoins($sub_id);
         if($rel_tot_bal){
            session()->flash('error', "Your balance is low for this subscription,use another option");
            return redirect()->back();
         }

         $this->checkoutService->chargeCoin($sub_id,$user_id);

         // send email here 
         $user = auth()->user();
         $send_email_sub = (new SubscriptionMailService())->sendSubMail($user);

         return redirect()->route('dashboard');
    }

    public function chargeTheWallet($total_balance,$sub_detail,$user_id){

        $charged_sub = $total_balance - $sub_detail->subscription_amount;
         if($charged_sub > MinimumBalance::Min){
           $charged_sub_bal = $charged_sub - MinimumBalance::Min;
           DB::table('user_wallet')->where('user_id', $user_id)->update(
              [
                'minimium_balance'=> MinimumBalance::Min,
                'balance'=> $charged_sub_bal
              ]
           );

         }elseif($charged_sub < MinimumBalance::Min){
              DB::table('user_wallet')->where('user_id', $user_id)->update(
              [
                'minimium_balance'=>$charged_sub,
                'balance'=> 0.00
              ]
           );
         }
    }

    
}
