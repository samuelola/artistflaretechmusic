<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use App\Models\Subscription;
use DB;

class SubscriptionService{

    public function storeSub($storeSub){
        $rel = (array)$storeSub;
        $subscription =  Subscription::create($rel);
        if(!$subscription){
            throw new \Exception ("Subscription cannot be created!");
        }
        return $subscription;
    }

    public function subscriptionInfo(){

        $num = DB::table('artist_no')->get();
        $number_of_trackproduct = DB::table('number_of_track')->get();
        $currency = DB::table('currency')->get();
        $subscription_duration = DB::table('subscription_duration')->get();
        $subscription_limit = DB::table('subscription_limit')->get();

        return [
            'artist_num'=> $num,
            'track_product' => $number_of_trackproduct,
            'curr' => $currency,
            'sub_duration' => $subscription_duration,
            'sub_limit' => $subscription_limit
        ];
    }
    
    public function chooseYourSub(){

        $currencyExchangeRate = DB::table('currency')->where('code','NGN')->first();
        $allsubs = DB::table('subscription_plan')->get();
        $user_sub = DB::table('sub_count')->where('user_id',auth()->user()->id)->orderBy('id','desc')->first();
        
        return [
            'rate' => $currencyExchangeRate,
            'subs' => $allsubs,
            'usersub' => $user_sub
        ];

    }

    
}