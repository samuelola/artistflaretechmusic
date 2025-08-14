<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Services\CheckoutService;
use Session;
use DB;
use App\Models\Subscription;

class CheckoutController extends Controller
{
    public function checkoutSubscription(Request $request)
    {
        $sub_id = $request->sub_id;
        $rel = (new CheckoutService)->checkforwallet();
        if($rel){
            $amount = 2000;
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

    public function checkoutPayment(Request $request)
    {
        
        try{
         DB::beginTransaction();
         $sub_id = $request->subc_id;
         $user_id = $request->user_id;

         //get All sub details
         $sub_detail = Subscription::where('id',$sub_id)->first();
         //check wallet minimium bal
         $checkwallet = DB::table('user_wallet')->where('user_id',$user_id)->first();
         if($checkwallet->minimium_balance == 0.00 || $checkwallet->minimium_balance < 2000.00){
            $amount = 2000;
            session()->flash('error', "Your balance is too low for this subscription,need a minimium of &#8358;{$amount} topup");
            return redirect()->back();
         }
         $total_balance = $checkwallet->balance + $checkwallet->minimium_balance;

         if($total_balance < $sub_detail->subscription_amount){
            session()->flash('error', "Your balance is low for this subscription,you need a subscription amount of &#8358;{$sub_detail->subscription_amount}");
            return redirect()->back();
         }

         // charge wallet

         // update role


         DB::commit();
        }catch(\Exception $e){
           DB::rollback();
           throw $e;
        }
    }
}
