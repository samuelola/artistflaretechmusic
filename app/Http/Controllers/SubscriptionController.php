<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\User;
use App\Models\Subscription;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Crypt;
use App\Http\Requests\SubscriptionRequest;
use App\Services\SubscriptionService;


class SubscriptionController extends Controller
{

    public function subscription_form(Request $request)
    {
        if (Session::has('success')){
            Alert::Success('Success', Session::get('success'));
        }
        $num = DB::table('artist_no')->get();
        $number_of_trackproduct = DB::table('number_of_track')->get();
        $currency = DB::table('currency')->get();
        $subscription_duration = DB::table('subscription_duration')->get();
        $subscription_limit = DB::table('subscription_limit')->get();
        
        return view('dashboard.pages.subscription_form',compact(
            'num',
            'number_of_trackproduct',
            'currency',
            'subscription_duration',
            'subscription_limit'
        ));
    }

    public function add_subscription(SubscriptionRequest $request){

        $data = $request->validated();
        $data['display_color'] = $request->display_color;
        $data['subscription_for'] = json_encode($request->subscription_for);
        $data['is_cancellation_enable'] = $request->is_cancellation_enable;
        $data['is_one_time_subscription'] = $request->is_one_time_subscription;
        $data['is_this_free_subscription'] = $request->is_this_free_subscription;
        $subService = (new SubscriptionService())->storeSub($data);
        return redirect()->back()->with('success','Subscription created Successfully');
    }

   
    
    
    
}
