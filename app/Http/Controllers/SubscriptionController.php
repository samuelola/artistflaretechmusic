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

   
    public function allsubscription(Request $request){
         
        $allsubs = DB::table('subscription_plan')->get();
        if ($request->ajax()) {
            $viewallSub = view('dashboard.pages.dataallsub', compact('allsubs'))->render();
            return response()->json(['htmlallsub' => $viewallSub]);
        }
        return view('dashboard.pages.all_subscription',compact('allsubs'));
    }
    
    public function edit_subscription(Request $request, $id){
         
        $editsubscription = DB::table('subscription_plan')->where('id',$id)->first();
        $num = DB::table('artist_no')->get();
        $number_of_trackproduct = DB::table('number_of_track')->get();
        $currency = DB::table('currency')->get();
        $subscription_duration = DB::table('subscription_duration')->get();
        $subscription_limit = DB::table('subscription_limit')->get();
        return view('dashboard.pages.edit_subscription_form',compact(
            'editsubscription',
            'num',
            'number_of_trackproduct',
            'currency',
            'subscription_duration',
            'subscription_limit'
        ));
    }

    public function editSub(Request $request, $id){

        DB::table('subscription_plan')
            ->where('id', $id)  // find your user by their email
            ->limit(1)
            ->update(
                [
                    'subscription_for' => json_encode($request->subscription_for),
                    'display_color' => $request->display_color,
                    'is_cancellation_enable' => $request->is_cancellation_enable,
                    'is_one_time_subscription' => $request->is_one_time_subscription,
                    'is_this_free_subscription' => $request->is_this_free_subscription,
                    'subscription_name'=>$request->subscription_name,
                    'artist_no'=>$request->artist_no,
                    'stock_keeping_unit'=>$request->stock_keeping_unit,
                    'no_of_tracks'=>$request->no_of_tracks,
                    'no_of_products'=>$request->no_of_products,
                    'max_no_of_tracks_per_products'=>$request->max_no_of_tracks_per_products,
                    'max_no_of_artists_per_products' => $request->max_no_of_artists_per_products,
                    'subscription_for'=>$request->subscription_for,
                    'track_file_quality' => $request->track_file_quality,
                    'currency' => $request->currency,
                    'subscription_amount' => $request->subscription_amount,
                    'plan_info_text' => $request->plan_info_text,
                    'include_tax' => $request->include_tax,
                    'subscription_duration' => $request->subscription_duration,
                    'subscription_limit_per_year' => $request->subscription_limit_per_year,
                ]); 

        return redirect()->route('allsubscription')->with('success','Sucription updated Successfully');        
    }

    public function choosesubscription(Request $request)
    {
        $currencyExchangeRate = DB::table('currency')->where('code','NGN')->first();
        $allsubs = DB::table('subscription_plan')->get();
        return view('dashboard.pages.choose_subscription',compact('allsubs','currencyExchangeRate'));
    }
    
}
