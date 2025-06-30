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
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Crypt;


class SubscriptionController extends Controller
{

    public function subscription_form(Request $request)
    {
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

    public function add_subscription(Request $request){

        
    }


    
    
    
}
