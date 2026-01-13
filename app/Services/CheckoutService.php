<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Interfaces\CheckoutInterface;
use App\Models\Userwallet;
use App\Enum\MinimumBalance;
use App\Enum\MinTransferAmount;
use DB;
use App\Models\Subscription;
use App\Enum\UserStatus;
use Illuminate\Support\Str;
use App\Enum\Plan;
use App\Notifications\NewMessageNotification;


class CheckoutService implements CheckoutInterface
{

    public function checkforTransfer($amount){
        
        $checkwallett = Userwallet::where('user_id',auth()->user()->id)->first();
        if($checkwallett->balance < $amount){
           return true;
        }else{
            return false;
        }
        
    }

    public function checkformainbalance(){
        
        //check if user can buy a plan
        $checkwallett = Userwallet::where('user_id',auth()->user()->id)->first();
        if($checkwallett->balance == 0.00){
           return true;
        }else{
            return false;
        }
        
    }

    public function minimumTransferAmount(){
        
        $checkwallett = Userwallet::where('user_id',auth()->user()->id)->first();
        if($checkwallett->balance < MinTransferAmount::Min){
           return true;
        }else{
            return false;
        }
        
    }

    public function checkforwallet(){
        
        //check if user can buy a plan
        $checkwallet = Userwallet::where('user_id',auth()->user()->id)->first();
        if($checkwallet->minimium_balance == 0.00){
           return true;
        }else{
            return false;
        }
        
    }

    public function checkwalletPayment($checkwallet){
       
         if($checkwallet->minimium_balance == 0.00 || $checkwallet->minimium_balance < MinimumBalance::Min){
            return true;
         }else{
            return false;
         }

    }

    public function checktotalbalance($total_balance,$sub_detail,$total_amt=''){

      //   if($total_balance < $sub_detail->subscription_amount){
      //       return true;
      //    }else{
      //       return false;
      //    }

         // for var purpose
         if($total_balance < $total_amt){
            return true;
         }else{
            return false;
         }
    }

    public function checktotalcoins($sub_id){

        //get All sub details
         $sub_detail = Subscription::where('id',$sub_id)->first();
         //check wallet minimum coin
         $coins = DB::table('user_statistics')->where('user_id',auth()->user()->id)->first();
         $total_coins = $coins->coin_balance +
          $coins->upload_release + 
          $coins->funds_added_count + 
          $coins->invite_points + 
          $coins->account_creation +
          $coins->sub_purchase + 
          $coins->wallet_topup;
         $coin_equivalent = DB::table('settings')->where('setting_name','FlareProCoin')->first();
         $get_totalcoin_equivalent = $total_coins * $coin_equivalent->ngn;

         if($get_totalcoin_equivalent < $sub_detail->subscription_amount){
            return true;
         }else{
            return false;
         }

    }

    public function chargeCoin($sub_id,$user_id){

         //get All sub details
         $sub_detail = Subscription::where('id',$sub_id)->first();

         $coins = DB::table('user_statistics')
         ->where('user_id', auth()->id())
         ->select(
               'coin_balance',
               'invite_members',
               'upload_release',
               'funds_added_count',
               'invite_points',
               'wallet_topup',
               'account_creation',
               'sub_purchase'
               )
         ->first();
        
        $total_coins = (int) array_sum((array) $coins);


         // $coins = DB::table('user_statistics')->where('user_id',auth()->user()->id)->first();
         // $total_coins = $coins->coin_balance + 
         // $coins->upload_release + 
         // $coins->funds_added_count + 
         // $coins->invite_points +
         // $coins->account_creation +
         // $coins->sub_purchase + 
         // $coins->wallet_topup;

         $coin_equivalent = DB::table('settings')->where('setting_name','FlareProCoin')->first();
         $get_totalcoin_equivalent = $total_coins * $coin_equivalent->ngn;

         $charged_sub = $get_totalcoin_equivalent - $sub_detail->subscription_amount;
         //convert back to coins
         $coin_converts = $charged_sub/$coin_equivalent->ngn;
         $coin_convert = (int)$coin_converts;
         $reu = DB::table('user_statistics')->where('user_id', $user_id)->update(
              [
                'coin_balance'=> $coin_convert,
                'upload_release' => 0,
                'funds_added_count' => 0,
                'invite_points' => 0,
                'wallet_topup' => 0,
                'account_creation' => 0,
                'sub_purchase' => 0,
                'login_count' => 0
              ]
           );

         // update role
         DB::table('users')->where('id',$user_id)->update([
            'role_id'=> UserStatus::Artist
         ]);

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
         DB::table('sub_count')->insert([
            'user_id' => $user_id,
            'subscription_id' => $sub_id,
            'status' => 'active',
            'start_date' => now(),
            'expires_at' => $expiry,
         ]);

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

         
         auth()->user()->notify(
         new NewMessageNotification(
               'Subscription Successful',
               "Your Subscription of ₦{$sub_detail->subscription_amount} is successful"
         )
         );

        return $reu;
    }

    
 
}


