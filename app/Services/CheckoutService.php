<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Interfaces\CheckoutInterface;
use App\Models\Userwallet;
use App\Enum\MinimumBalance;

class CheckoutService implements CheckoutInterface
{

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

    public function checktotalbalance($total_balance,$sub_detail){

        if($total_balance < $sub_detail->subscription_amount){

            return true;
            
         }else{
            return false;
         }
    }

    
 
}


