<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Interfaces\WalletTransferInterface;
use App\Models\Userwallet;
use App\Models\UserStatistics;
use Illuminate\Support\Str;
use App\Notifications\NewMessageNotification;


class WalletService implements  WalletTransferInterface{


    public function checkForMinTransferAmount($min,$amount){

         $checkwallett = Userwallet::where('user_id',auth()->user()->id)->first();
         if($amount < $min){
            return true;
        }else{
            return false;
        }

    }

    public function walletTransfer($amount,$recipient){
         
         $u = DB::transaction(function () use ($amount,$recipient) {
            $user_id = auth()->user()->id;
            $st_amount = $amount;
            $from = Userwallet::with('user')->lockForUpdate()->where('user_id',$user_id)->first();
            $to = Userwallet::with('user')->lockForUpdate()->where('user_id',$recipient)->first();
            
            $from->update(['balance' => $from->balance - $st_amount]);
            $to->update(['balance' => $to->balance + $st_amount]);
            $reference = 'REF-' . Str::upper(Str::random(10));
            DB::table('transactions')->insert([

                'reference' => $reference,
                'amount' => $st_amount,
                'user_id' => $user_id,
                'receiver_id' => $to->user->id,
                'status' => 'success',
                'paid_at' => now(), 
                'remarks' => "Transfer from {$from->user->first_name} Wallet to {$to->user->first_name} Wallet",
                'gateway' => 'System-Wallet',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('transactions')->insert([

                'reference' => $reference,
                'amount' => $st_amount,
                'user_id' => $to->user->id,
                'status' => 'success',
                'paid_at' => now(), 
                'remarks' => "Transfer from {$from->user->first_name} Wallet to {$to->user->first_name} Wallet",
                'gateway' => 'System-Wallet',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            //sender
            $sender = auth()->user();
            $sender->notify(
            new NewMessageNotification(
                'Transfer Successful',
                "Transfer of ₦{$st_amount} to {$to->user->first_name} Wallet is successful"
            )
            );
            
            // recepient
            $recipient = $to->user;
            $recipient->notify(
                new NewMessageNotification(
                    'Wallet Credit Alert',
                    "{$sender->first_name} has sent you ₦{$st_amount}"
                )
            );
              
            return true;
         });
          return $u;    
    }

    public function checkForMinTransferCoinAmount($amount){

         if($amount == 0){
            return true;
        }else{
            return false;
        }

    }

    public function walletCoinTransfer($amount,$recipient){
         
         $u = DB::transaction(function () use ($amount,$recipient) {
            $user_id = auth()->user()->id;
            $st_amount = $amount;
            $from = UserStatistics::with('user')->lockForUpdate()->where('user_id',$user_id)->first();
            $to = UserStatistics::with('user')->lockForUpdate()->where('user_id',$recipient)->first();
            
            $from->update(['coin_balance' => $from->coin_balance - $st_amount]);
            $to->update(['coin_balance' => $to->coin_balance + $st_amount]);
            $reference = 'REF-' . Str::upper(Str::random(10));
            DB::table('transactions')->insert([

                'reference' => $reference,
                'amount' => $st_amount,
                'user_id' => $user_id,
                'receiver_id' => $to->user->id,
                'status' => 'success',
                'paid_at' => now(), 
                'remarks' => "Transfer from {$from->user->first_name} Coin Wallet to {$to->user->first_name} Coin Wallet",
                'gateway' => 'System-Wallet',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('transactions')->insert([

                'reference' => $reference,
                'amount' => $st_amount,
                'user_id' => $to->user->id,// receiver id
                'status' => 'success',
                'paid_at' => now(), 
                'remarks' => "Received from {$from->user->first_name} Coin Wallet to {$to->user->first_name} Coin Wallet",
                'gateway' => 'System-Wallet',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            
            auth()->user()->notify(
            new NewMessageNotification(
                'Transfer FlareCoins Successful',
                "Transfer of {$st_amount} FlareProCoins to {$to->user->first_name} Coin Wallet is successful"
            )
            );
              
            return true;
         });
          return $u;    
    }
}