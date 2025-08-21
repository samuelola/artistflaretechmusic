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
use Carbon\Carbon;
use App\Enum\UserStatus;


class DashboardController extends Controller
{

    //Alert::success('Success','Welcome '.auth()->user()->first_name );
    
    
    public function showDashboard(Request $request)
    {

        //check for expiration
        $dateAfter = DB::table('sub_count')->where('user_id',auth()->user()->id)->first();
        if(!is_null($dateAfter)){
            $d_date = $dateAfter->expires_at;
            if (now()->greaterThan($d_date)){
                DB::table('users')->where('id',auth()->user()->id)->update([
                    'role_id'=> UserStatus::Guest
                ]);
                DB::table('sub_count')->where('id',auth()->user()->id)->update([
                    'status'=> 'notactive'
                ]);

            }

            // $reminderDate = Carbon::parse($d_date)->subDays(3);
            // if (Carbon::now()->toDateString() === $reminderDate->toDateString()) {
            //     //send reminder email
            //     $send_email_sub = (new SubscriptionMailService())->sendExpireReminderMail($user);
            // }
           
            
        }
        
       
        $users = User::where('role_id','!=',1)->distinct('first_name')->count();
        $users_count_last_30days = User::where('created_at', '>', now()->subDays(30)->endOfDay())->count();
        $total_subscription = DB::table("subscription_payment_details")->count();
        $total_subscription_last_30days = DB::table("subscription_payment_details")->where('paymentdate', '>', now()->subDays(30)->endOfDay())->count();
        $total_albums_user = DB::table("users")->where('id',auth()->user()->id)->sum('albums');
        $get_all_users = User::where('role_id','!=',1)->orderBy('id','desc')->paginate(10);
        $subscribers = DB::table("subscription_payment_details")->distinct('email')->orderBy('id','desc')->paginate(10);
        $plans = DB::table('subscription_plan')->orderBy('id','asc')->paginate(10);
        $getwall_bal = DB::table('user_wallet')->where('user_id',auth()->user()->id)->first();
        $min_bal = $getwall_bal->minimium_balance;
        $main_bal = $getwall_bal->balance;
        $total_balance = $min_bal + $main_bal;
        $resultsub_count = DB::table('users')
        ->join('subscription_payment_details', 'subscription_payment_details.Email', '=', 'users.email')
        ->where('users.email', auth()->user()->email)
        ->count();

        $resulttrack_count = DB::table('users')
        ->join('trackdetails', 'trackdetails.Email', '=', 'users.email')
        ->where('users.email', auth()->user()->email)
        ->count();

        $usser = DB::table("users")->where('id',auth()->user()->id)->first();
        $g1 = $usser->first_name;
        $g2 = $usser->last_name;
        $rrg = $g1.' '.$g2;
        $total_labelUser = DB::table("product_details")->distinct('Label_Name')->where('Sound_Recording_Performing_Artist_s',$rrg)->count();


        
        if ($request->ajax()) {
            $view = view('dashboard.pages.data', compact('subscribers'))->render();
            $vieww = view('dashboard.pages.dataa', compact('get_all_users'))->render();
            $viewplan = view('dashboard.pages.dataaplan', compact('plans'))->render();
            return response()->json(['html' => $view,'newhtml'=>$vieww,'newhtmlplan'=>$viewplan]);
        }

        $thealbums = DB::table('users')
                     ->select([
                       DB::raw('YEAR(join_date)as year'),
                       DB::raw('SUM(albums) as albums'),
                     ])
                     ->orderBy('year', 'ASC')
                     ->groupBy('year')
                     ->where(DB::raw('YEAR(join_date)'), '!=', 'null' )
                     ->where('active','Yes')
                     ->get();
                      
                     
        $albumvalue = [];              
            foreach($thealbums as $dd){
                $albumvalue[] = $dd->albums;
        }

        $thetracks = DB::table('users')
                     ->select([
                       DB::raw('YEAR(join_date)as year'),
                       DB::raw('SUM(tracks) as tracks'),
                     ])
                     ->orderBy('year', 'ASC')
                     ->groupBy('year')
                     ->where(DB::raw('YEAR(join_date)'), '!=', 'null' )
                     ->where('active','Yes')
                     ->get(); 
                     
        $albumvalue = [];              
            foreach($thealbums as $dd){
                $albumvalue[] = $dd->albums;
        }

        $trackvalue = [];              
            foreach($thetracks as $dd){
                $trackvalue[] = $dd->tracks;
        }
                     
        $theyear = DB::table('users')
        ->select(DB::raw('YEAR(join_date)as year'))
        ->orderBy('year', 'ASC')           
        ->groupBy('year')
        ->where(DB::raw('YEAR(join_date)'), '!=', 'null' )
        ->where('active','Yes')
        ->get();
        
         $thelang = DB::table('languages')
        ->get();

        $thecountry = DB::table('countries')
        ->get();

        $login_count = DB::table('user_statistics')->where('user_id',auth()->user()->id)->first();
        $fund_count = DB::table('user_statistics')->where('user_id',auth()->user()->id)->first();

   
        return view('dashboard.pages.home',compact(
            'fund_count',
            'login_count',
            'resulttrack_count',
            'total_labelUser',
            'total_albums_user',
            'resultsub_count',
            'total_balance',
            'getwall_bal',
            'users',
            'users_count_last_30days',
            'total_subscription',
            'total_subscription_last_30days',
            'get_all_users',
            'subscribers',
            'plans',
            'theyear',
            'albumvalue',
            'trackvalue',
            'thelang',
            'thecountry'
        ));
    }

    public function filterInfo(Request $request){

        // if($request->has('date_filter_data')){
        //     $year_data = DB::table('users')
        //              ->select([
        //                DB::raw('YEAR(join_date)as year'),
        //                DB::raw('SUM(albums) as albums'),
        //                DB::raw('SUM(tracks) as tracks')
        //              ])
        //              ->orderBy('year', 'ASC')
        //              ->groupBy('year')
        //              ->where(DB::raw('YEAR(join_date)'), '=', $request->date_filter_data )
        //              ->get();
        //     return response()->json(['data' => $year_data]); 
        // }

        if($request->has('date_filter_data')){
            $year_data = DB::table('users')
                     ->select([
                       DB::raw('MONTH(join_date)as month'),
                       DB::raw('SUM(albums) as albums'),
                       DB::raw('SUM(tracks) as tracks')
                     ])
                     ->orderBy('month', 'ASC')
                     ->groupBy('month')
                     ->where(DB::raw('YEAR(join_date)'), '=', $request->date_filter_data )
                     ->where('active','Yes')
                     ->get();
            if($year_data){
                 return response()->json(['data' => $year_data,'theyyear'=>$request->date_filter_data]);                
            }      
            
        }
        
        if($request->has('filter_language_data')){
            $lang_data = DB::table('users')
                     ->select([
                       DB::raw('YEAR(join_date)as year'), 
                       DB::raw('SUM(albums) as albums'),
                       DB::raw('SUM(tracks) as tracks')
                     ])
                     ->orderBy('year', 'ASC')
                     ->groupBy('year')
                     ->where('language',$request->filter_language_data)
                     ->where(DB::raw('YEAR(join_date)'), '!=', 'null' )
                     ->where('active','Yes')
                     ->get();
            if($lang_data){
                 return response()->json(['langdata' => $lang_data]);                
            }         
        }

        if($request->has('filter_country_data')){
            $country_data = DB::table('users')
                     ->select([
                       DB::raw('YEAR(join_date)as year'), 
                       DB::raw('SUM(albums) as albums'),
                       DB::raw('SUM(tracks) as tracks')
                     ])
                     ->orderBy('year', 'ASC')
                     ->groupBy('year')
                     ->where('country',$request->filter_country_data)
                     ->where(DB::raw('YEAR(join_date)'), '!=', 'null' )
                     ->where('active','Yes')
                     ->get();
            if($country_data){
                 return response()->json(['countrydata' => $country_data]);                
            }        
        }
       
        
        
    }

    public function analytics(Request $request)
    {
        return view('dashboard.pages.analytics');
    }

    public function profile(Request $request)
    {
        if (Session::has('success')){
            Alert::Success('Success', Session::get('success'));
        }
        return view('dashboard.pages.profile');
    }
    

    public function showDashboardd(Request $request){
        $token = $request->pt;
        $decrypted = Crypt::decryptString($token);
        Session::put('tokken',$decrypted);

       if ($decrypted) {
        $response = Http::withToken($decrypted)->get('http://artistuser.test/api/user');
        $loggedUserInfo = $response->body();
        $rel = json_decode($loggedUserInfo);
        $user = User::where('id',$rel->user_details->id)->first();
        Auth::setUser($user);
        return Redirect::to('http://artistuser.test/dashboard');
       }
        return Redirect::to('http://auth.test');
    }
    
    public function logout(Request $request) {
        $rri = Session::get('tokken');
        $decrypted = $rri;
        $response = Http::withToken($decrypted)->post('http://artistuser.test/api/logout');
        if($response->successful() == true){
            return Redirect::to('http://auth.test');
            $request->session()->forget('tokken');
        }
       
        
    }

    public function viewDashboard(Request $request, $id){
        
        if (Session::has('success')){
            Alert::Success('Success', Session::get('success'));
        }
        if(empty($permissionedituserPermission)){
           abort(403);
        }
        $decrypted = decrypt($id);
        $user_info = DB::table('users')->where('id',$decrypted)->first();
        return view('dashboard.pages.users.user_info',compact('user_info'));
    }

    public function allTracks(Request $request){

        $all_th_tracks = DB::table('users')
        ->join('trackdetails', 'trackdetails.Email', '=', 'users.email')
        ->where('users.email', auth()->user()->email)
        ->orderBy('id','desc')
        ->paginate(10);
         if ($request->ajax()) {
            $viewttracks = view('dashboard.pages.trackspage', compact('all_th_tracks'))->render();
            return response()->json(['htmltracks' => $viewttracks]);
        }
         return view('dashboard.pages.tracks',compact('all_th_tracks'));
    }

    public function viewTracks(Request $request,$id){
         $track_user_detail = DB::table('trackdetails')->where('id',$id)->first();
         return view('dashboard.pages.track_details',compact('track_user_detail'));
    }
    
}