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
use App\Notifications\NewMessageNotification;
use App\Models\SubCount;


class CheckoutController extends Controller
{


    public $checkoutService;

    public function __construct(CheckoutService $checkoutService){

        $this->checkoutService = $checkoutService;
    }

    public function checkoutSubscription(Request $request)
    {

        $sub_id = $request->sub_id;
        // if subscription is active return users
        $activeSubscription = SubCount::where('user_id', auth()->id())
        ->where('status', 'active')
        ->first();
        
        if ($activeSubscription) {

            return redirect()->back()->with('error',"You already have an active subscription.");
         
        }

        return redirect()->route('checkout_details',['id'=>$sub_id]);
       
        
    }

    public function checkoutDetails(Request $request, $id)
    {
       $vat_value = DB::table('vats')->first();
       $sub_details = DB::table('subscription_plan')->where('id',$id)->first();
       $currencyExchangeRate = DB::table('currency')->where('code','NGN')->first();
       return view('dashboard.pages.checkout_details',compact('sub_details','currencyExchangeRate','vat_value'));
    }

    public function checkoutPayment(Request $request)
    {
    
         $sub_id = $request->subc_id;
         $user_id = $request->user_id;
         $amount = MinimumBalance::Min;
         $total_amt = $request->total_amt;

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
         $rel_tot_bal = $this->checkoutService->checktotalbalance($total_balance,$sub_detail,$total_amt);
         if($rel_tot_bal){
            //session()->flash('error', "Your balance is low for this subscription,you need a subscription amount of &#8358;{$sub_detail->subscription_amount}");
            //for vat purpose
            session()->flash('error', "Your balance is low for this subscription,you need an amount of &#8358;{$total_amt}");
            return redirect()->back();
         }

         // charge wallet
         $this->chargeTheWallet($total_balance,$sub_detail,$user_id,$total_amt);

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
        $groups = Plan::groups();

        if (in_array($sub_detail->subscription_name, $groups['yearly'])) {
            $expiry = now()->addYear();
        } elseif (in_array($sub_detail->subscription_name, $groups['forever'])) {
            $expiry = null;
        } elseif (in_array($sub_detail->subscription_name, $groups['monthly'])) {
            $expiry = now()->addMonth();
        } else {
            throw new \Exception("Unknown subscription plan: {$sub_detail->subscription_name}");
        }

        // Insert into sub_count
        $subccountt = DB::table('sub_count')->where('user_id',$user_id)->first();

        if(is_null($subccountt)){
             DB::table('sub_count')->insert([
            'user_id' => $user_id,
            'subscription_id' => $sub_id,
            'status' => 'active',
            'start_date' => now(),
            'expires_at' => $expiry,
           ]);
        }
        else{

          DB::table('sub_count')->where('user_id',$user_id)->update([
            'user_id' => $user_id,
            'subscription_id' => $sub_id,
            'status' => 'active',
            'start_date' => now(),
            'expires_at' => $expiry,
            ]);

        }
        

         // Insert into transactions
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

         // send email here 
            $user = auth()->user();
            $send_email_sub = (new SubscriptionMailService())->sendSubMail($user);
            // if($send_email_sub){
            //     dd('yes');
            // }else{
            //     dd('no');
            // }
            

             //auth()->user()->notify(new NewMessageNotification("Your Subscription of ₦{$sub_detail->subscription_amount} is successful"));

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

         $result = $this->checkoutService->chargeCoin($sub_id,$user_id);

         if(!$result){
            return back()->with('error', 'Insufficient coin balance.');
         }

         // send email here 
         $user = auth()->user();
         $send_email_sub = (new SubscriptionMailService())->sendSubMail($user);

         return redirect()->route('dashboard');
    }

    public function chargeTheWallet($total_balance,$sub_detail,$user_id,$total_amt){

        //$charged_sub = $total_balance - $sub_detail->subscription_amount;
        $charged_sub = $total_balance - $total_amt;
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
