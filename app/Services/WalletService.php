<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Interfaces\WalletTransferInterface;
use App\Models\Userwallet;
use Illuminate\Support\Str;

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
            $st_amount = (int)$amount;
            $from = Userwallet::with('user')->lockForUpdate()->where('user_id',$user_id)->first();
            $to = Userwallet::with('user')->lockForUpdate()->where('user_id',$recipient)->first();
            
            $from->update(['balance' => $from->balance - $st_amount]);
            $to->update(['balance' => $to->balance + $st_amount]);
            $reference = 'REF-' . Str::upper(Str::random(10));
            DB::table('transactions')->insert([

                'reference' => $reference,
                'amount' => $st_amount,
                'user_id' => $user_id,
                'status' => 'success',
                'paid_at' => now(), 
                'remarks' => "Transfer from {$from->user()->first_name} Wallet to {$to->user()->first_name}",
                'gateway' => 'System-Wallet',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
              
            return true;
         });
          return $u;    
    }
}