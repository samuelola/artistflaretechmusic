<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use App\Models\Subscription;

class SubscriptionService{

    public function storeSub($storeSub){
        $rel = (array)$storeSub;
        $subscription =  Subscription::create($rel);
        return $subscription;
        
    }

    
}