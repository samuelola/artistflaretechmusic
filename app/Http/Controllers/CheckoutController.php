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


class CheckoutController extends Controller
{
    public function checkoutSubscription(Request $request)
    {
        $sub_id = $request->sub_id;
        $rel = (new CheckoutService)->checkforwallet();
        if($rel){
            $amount = MinimumBalance::Min;
            session()->flash('error', "Your balance is too low for this subscription,need a minimium of &#8358;{$amount} topup");
            
            return redirect()->back();
        }else{
            return redirect()->route('checkout_details',['id'=>$sub_id]);
        }
        
    }

    public function checkoutDetails(Request $request, $id)
    {
       $sub_details = DB::table('subscription_plan')->where('id',$id)->first();
       $currencyExchangeRate = DB::table('currency')->where('code','NGN')->first();
       return view('dashboard.pages.checkout_details',compact('sub_details','currencyExchangeRate'));
    }

    public function checkoutPayment(Request $request, CheckoutService $checkoutService)
    {

        try{
         DB::beginTransaction();
         $sub_id = $request->subc_id;
         $user_id = $request->user_id;
         $amount = MinimumBalance::Min;

         //get All sub details
         $sub_detail = Subscription::where('id',$sub_id)->first();
         //check wallet minimium bal
         $checkwallet = DB::table('user_wallet')->where('user_id',$user_id)->first();
         $rell = $checkoutService->checkwalletPayment($checkwallet);
         if($rell){
            session()->flash('error', "Your balance is too low for this subscription,need a minimium of &#8358;{$amount} topup");
            return redirect()->back();
         }
         $total_balance = $checkwallet->balance + $checkwallet->minimium_balance;

         $rel_tot_bal = $checkoutService->checktotalbalance($total_balance,$sub_detail);
         if($rel_tot_bal){
            session()->flash('error', "Your balance is low for this subscription,you need a subscription amount of &#8358;{$sub_detail->subscription_amount}");
            return redirect()->back();
         }

         // charge wallet
         $this->chargeTheWallet($total_balance,$sub_detail,$user_id);

         // update role
         DB::table('users')->where('id',$user_id)->update([
            'role_id'=> UserStatus::Artist
         ]);
        
         //add subscription with date 
        if($sub_detail->subscription_name == Plan::Basic){

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

        }elseif($sub_detail->subscription_name == Plan::EasyBuy){
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

        elseif($sub_detail->subscription_name == Plan::ForeverBasic){
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

        elseif($sub_detail->subscription_name == Plan::ForeverStandard){
            DB::table('sub_count')->insert([
            'user_id' => $user_id,
            'subscription_id' => $sub_id,
            'status' => 'active',
            'start_date' => now(),
          ]);
        }

        elseif($sub_detail->subscription_name == Plan::Freesub){
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

        elseif($sub_detail->subscription_name == Plan::Premium){
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

        elseif($sub_detail->subscription_name == Plan::Standard){
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

        elseif($sub_detail->subscription_name == Plan::UnlimitedForever){
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

         // send email here 
        //  $user = auth()->user();
        //  $send_email_sub = (new SubscriptionMailService())->sendSubMail($user);

         DB::commit();
         return redirect()->route('dashboard');

        }catch(\Exception $e){
           DB::rollback();
           Log::error('Checkout failed: ' . $e->getMessage());
           throw $e;
        }
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
