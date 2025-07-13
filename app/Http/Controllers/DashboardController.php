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


class DashboardController extends Controller
{

    //Alert::success('Success','Welcome '.auth()->user()->first_name );
    
    
    public function showDashboard(Request $request)
    {
       
        
        $users = User::distinct('first_name')->count();
        $users_count_last_30days = User::where('created_at', '>', now()->subDays(30)->endOfDay())->count();
        $total_subscription = DB::table("subscription_payment_details")->count();
        $total_subscription_last_30days = DB::table("subscription_payment_details")->where('paymentdate', '>', now()->subDays(30)->endOfDay())->count();
        //$total_albums = DB::table("users")->sum('albums');
        $total_albums = User::sum('albums');
        $total_albumss = (int)$total_albums;
        $total_tracks = DB::table("trackdetails")->where(['Release Count'=>0,'Release Count'=>1])->distinct('User Name')->count();
        $total_trackss = (int)$total_tracks;
        $total_labels = DB::table("labeldetails")->count();
        $total_labelss = (int)$total_labels;
        $get_all_users = User::orderBy('id','desc')->paginate(10);
        $subscribers = DB::table("subscription_payment_details")->distinct('email')->orderBy('id','desc')->paginate(10);
        $plans = DB::table('subscription_plan')->orderBy('id','asc')->paginate(10);

        
        if ($request->ajax()) {
            $view = view('dashboard.pages.data', compact('subscribers'))->render();
            $vieww = view('dashboard.pages.dataa', compact('get_all_users'))->render();
            $viewplan = view('dashboard.pages.dataaplan', compact('plans'))->render();
            return response()->json(['html' => $view,'newhtml'=>$vieww,'newhtmlplan'=>$viewplan]);
        }


        // $deposit = DB::table('users')
        //              ->select([
        //                DB::raw('YEAR(join_date)as year'),
        //                DB::raw('SUM(albums) as albums'),
        //                DB::raw('SUM(tracks) as tracks')
        //              ])
        //              ->orderBy('year', 'ASC')
        //              ->groupBy('year')
        //              ->where(DB::raw('YEAR(join_date)'), '!=', 'null' )
        //              ->get();

        $thealbums = DB::table('users')
                     ->select([
                       DB::raw('YEAR(join_date)as year'),
                       DB::raw('SUM(albums) as albums'),
                     ])
                     ->orderBy('year', 'ASC')
                     ->groupBy('year')
                     ->where(DB::raw('YEAR(join_date)'), '!=', 'null' )
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
        ->get();
        
         $thelang = DB::table('languages')
        ->get();
   
        return view('dashboard.pages.home',compact(
            'users',
            'users_count_last_30days',
            'total_subscription',
            'total_subscription_last_30days',
            'total_albumss',
            'total_trackss',
            'total_labelss',
            'get_all_users',
            'subscribers',
            'plans',
            'theyear',
            'albumvalue',
            'trackvalue',
            'thelang'
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
                     ->get();
            if($year_data){
                 return response()->json(['data' => $year_data,'theyyear'=>$request->date_filter_data]);                
            }else{
                 return response()->json(['nodata'=>'No data found']); 
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
                     ->get();
            if($lang_data){
                 return response()->json(['langdata' => $lang_data]);                
            }else{
                 return response()->json(['nodata'=>'No data found']); 
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
        $response = Http::withToken($decrypted)->get('http://superadmin.test/api/user');
        $loggedUserInfo = $response->body();
        $rel = json_decode($loggedUserInfo);
        $user = User::where('id',$rel->user_details->id)->first();
        Auth::setUser($user);
        return Redirect::to('http://superadmin.test/dashboard');
       }

        return Redirect::to('http://auth.test');
    }
    public function logout(Request $request) {
        $rri = Session::get('tokken');
        $decrypted = $rri;
        $response = Http::withToken($decrypted)->post('http://superadmin.test/api/logout');
        if($response->successful() == true){
            return Redirect::to('http://auth.test');
            $request->session()->forget('tokken');
        }
       
        
    }

    public function viewDashboard(Request $request, $id){
        
        if (Session::has('success')){
            Alert::Success('Success', Session::get('success'));
        }
        $decrypted = decrypt($id);
        $user_info = DB::table('users')->where('id',$decrypted)->first();
        return view('dashboard.pages.users.user_info',compact('user_info'));
    }
    
}