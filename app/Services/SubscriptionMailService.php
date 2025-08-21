<?php

namespace App\Services;

use App\Mail\Subscription;
use Illuminate\Support\Facades\Mail;
use DB;
use Carbon\Carbon;

class SubscriptionMailService
{
    public function sendSubMail($user){

      $sub_mail = DB::table('sub_count')
                ->selectRaw(
                    'subscription_plan.subscription_name,
                     subscription_plan.subscription_duration,
                     subscription_plan.subscription_amount,
                     subscription_plan.currency,
                     subscription_plan.artist_no,
                     subscription_plan.no_of_tracks,
                     subscription_plan.no_of_products,
                     currency.symbol,
                     currency.code,
                     sub_count.expires_at
                    '
                )
                ->join('subscription_plan', 'subscription_plan.id', '=', 'sub_count.subscription_id')
                ->join('currency','currency.code', '=', 'subscription_plan.currency')
                ->where('sub_count.user_id',auth()->user()->id)
                ->where('sub_count.status', '=','active')
                ->first();
        $d_date = $sub_mail->expires_at;        
        $sub_maile  = [
        'user' => $user->first_name,
        'sub_name' => $sub_mail->subscription_name,
        'sub_amount' => $sub_mail->subscription_amount,
        'sub_duration' => $sub_mail->subscription_duration,
        'sub_artist' => $sub_mail->artist_no,
        'sub_track' => $sub_mail->no_of_tracks,
        'product' => $sub_mail->no_of_products,
        'currency' => $sub_mail->symbol,
        'expires_at' => Carbon::parse($d_date)->format('d,M Y')
        ];       
       
    
       //$u = Mail::to($user->email)->queue(new Subscription($sub_maile));
       $u = Mail::to($user->email)->send(new Subscription($sub_maile));
       
       return $u;
    }

    
}