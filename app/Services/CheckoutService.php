<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Interfaces\CheckoutInterface;
use App\Models\Userwallet;

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
 
}


