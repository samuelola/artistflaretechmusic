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
use App\Models\Transaction;
use App\Models\UserStatistics;
use App\Notifications\NewMessageNotification;
use App\Notifications\TestNotification;
use App\Models\MusicRelease;
use App\Models\Track;
use App\Models\ArtistOwnerIdentity;


class DashboardController extends Controller
{

    public function testnoti(){

    //   $user = auth()->user(); 
    // $user->notify(new TestNotification("This is a real-time test"));
    // return "Test notification sent to user {$user->id}";
    }

    public function showDashboard(Request $request)
    {
    
        $baseQueryUserStatistics = DB::table('user_statistics')
        ->where('user_id', auth()->id());
        $baseQuery = DB::table('users');
        $baseQuery1 = DB::table('users')->where('role_id','!=',1);
        $baseSubQuery = DB::table("transactions");
        $get_yearr =  DB::raw('YEAR(join_date)as year');
       
        $users = (clone $baseQuery)->distinct('first_name')->count();
       
        $total_subscription = (clone $baseSubQuery)->count();
        $total_subscription_last_30days = (clone $baseSubQuery)
            ->where([
                ['created_at', '>', now()->subDays(30)->startOfDay()],
                ['user_id', '=', auth()->id()],
                ['remarks', '=', 'Subscription Payment'],
            ])
            ->count();               
        $total_albums_user = DB::table("users")->where('id',auth()->user()->id)->sum('albums');
        
        $subscribers = (clone $baseSubQuery)->orderBy('id','desc')->paginate(10);
        //$subscribers = (clone $baseSubQuery)->distinct('email')->orderBy('id','desc')->paginate(10);
        $plans = DB::table('subscription_plan')->orderBy('id','asc')->paginate(10);
        $getwall_bal = DB::table('user_wallet')->where('user_id',auth()->user()->id)->first();
        $min_bal = $getwall_bal->minimium_balance;
        $main_bal = $getwall_bal->balance;
        $total_balance = $min_bal + $main_bal;
       
        
        $resultsub_count = Transaction::where(['user_id'=>auth()->user()->id,'remarks'=>'Subscription Payment'])->count();
        
        $resulttrack_count = (clone $baseQuery)
        ->join('trackdetails', 'trackdetails.Email', '=', 'users.email')
        ->where('users.email', auth()->user()->email)
        ->count();

        $usser = (clone $baseQuery)->where('id',auth()->user()->id)->first();
        $g1 = $usser->first_name;
        $g2 = $usser->last_name;
        $rrg = $g1.' '.$g2;
        $total_labelUser = DB::table("product_details")->distinct('Label_Name')->where('Sound_Recording_Performing_Artist_s',$rrg)->count();

        $get_transactions = Transaction::with(['user','subscription'])
                                         ->where('user_id',auth()->user()->id)
                                         ->orderBy('id','desc')
                                         ->paginate(10);

        $get_ref_code = UserStatistics::select(['referral_code','invite_points'])->where('user_id',auth()->user()->id)->first();    
        $stats_total = (clone $baseQueryUserStatistics)
        ->select(
            'coin_balance',
            'invite_members',
            'upload_release',
            'funds_added_count',
            'invite_points',
            'wallet_topup',
            'account_creation',
            // 'sub_purchase'
            )
        ->first();
        
        $stats_totals = (int) array_sum((array) $stats_total);

        $getCryptoWallet = DB::table('crypto_wallets')->where(['user_id'=>auth()->id(),'coin'=>'FLA'])->first();

        if(!is_null($getCryptoWallet)){
            DB::table('crypto_wallets')->where(['user_id'=>auth()->id(),'coin'=>'FLA'])->update(['balance'=>$stats_totals]);
        }

        $get_all_users = (clone $baseQuery1)->orderByDesc('id')->paginate(10);

        
        if ($request->ajax()) {
            $viewTransaction = view('dashboard.pages.tranxdata', compact('get_transactions'))->render();
            $view = view('dashboard.pages.data', compact('subscribers'))->render();
            $vieww = view('dashboard.pages.dataa', compact('get_all_users'))->render();
            $viewplan = view('dashboard.pages.dataaplan', compact('plans'))->render();
            return response()->json([
                'html' => $view,
                'newhtml'=>$vieww,
                'newhtmlplan'=>$viewplan,
                'newhtmltransaction' => $viewTransaction
            ]);
        }

        $thealbums = $this->search_filter_albums($baseQuery);
                      
                     
        $albumvalue = [];              
            foreach($thealbums as $dd){
                $albumvalue[] = $dd->albums;
        }

        $thetracks = $this->search_filter_tracks($baseQuery); 
                     
        $albumvalue = [];              
            foreach($thealbums as $dd){
                $albumvalue[] = $dd->albums;
        }

        $trackvalue = [];              
            foreach($thetracks as $dd){
                $trackvalue[] = $dd->tracks;
        }
                     
        $theyear = $this->the_year($baseQuery,$get_yearr);
        
        $thelang = DB::table('languages')
        ->get();

        $thecountry = DB::table('countries')
        ->get();

        $stat_count = DB::table('user_statistics')->where('user_id',auth()->user()->id)->first();

        $artist_owner = ArtistOwnerIdentity::where('user_id', auth()->id())->first();
        
        return view('dashboard.pages.home',compact(
            'stats_total',
            'stats_totals',
            'get_ref_code',
            'get_transactions',
            'stat_count',
            'resulttrack_count',
            'total_labelUser',
            'total_albums_user',
            'resultsub_count',
            'total_balance',
            'getwall_bal',
            'users',
            'total_subscription',
            'total_subscription_last_30days',
            'get_all_users',
            'subscribers',
            'plans',
            'theyear',
            'albumvalue',
            'trackvalue',
            'thelang',
            'thecountry',
            'artist_owner'
        ));
    }

    public function search_filter_albums($query){

      return (clone $query)
            ->select([
            DB::raw('YEAR(join_date)as year'),
            DB::raw('SUM(albums) as albums'),
            ])
            ->orderBy('year', 'ASC')
            ->groupBy('year')
            ->where(DB::raw('YEAR(join_date)'), '!=', 'null' )
            ->where('active','Yes')
            ->get();
        
    }

    public function search_filter_tracks($query){

      return (clone $query)
            ->select([
            DB::raw('YEAR(join_date)as year'),
            DB::raw('SUM(tracks) as tracks'),
            ])
            ->orderBy('year', 'ASC')
            ->groupBy('year')
            ->where(DB::raw('YEAR(join_date)'), '!=', 'null' )
            ->where('active','Yes')
            ->get();
        
    }

    public function the_year($query,$get_yearr){
        return (clone $query)
                ->select((clone $get_yearr))
                ->orderBy('year', 'ASC')           
                ->groupBy('year')
                ->where(DB::raw('YEAR(join_date)'), '!=', 'null' )
                ->where('active','Yes')
                ->get();
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
        $response = Http::withToken($decrypted)->get('http://artistdashboard.test/api/user');
        $loggedUserInfo = $response->body();
        $rel = json_decode($loggedUserInfo);
        $user = User::where('id',$rel->user_details->id)->first();
        Auth::setUser($user);
        return Redirect::to('http://artistdashboard.test/dashboard')->with('showModal', true);
       }
        return Redirect::to('http://adminflaretech.test');
    }
    
    public function logout(Request $request) {
        $rri = Session::get('tokken');
        $decrypted = $rri;
        $response = Http::withToken($decrypted)->post('http://artistdashboard.test/api/logout');
        if($response->successful() == true){
            //$request->session()->forget('tokken');
        $request->session()->flush();
        $request->session()->regenerateToken();
        return Redirect::to('http://authflaretech.test');
            
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

   

    public function theTracks(Request $request,$id){

         $all_th_tracks = Track::with(['release','audioFile','participants'])
                                  ->where('music_release_id',$id)
                                  ->latest()
                                  ->paginate(10);                         
                      
         if ($request->ajax()) {
            $viewttracks = view('dashboard.pages.trackspage', compact('all_th_tracks'))->render();
            return response()->json(['htmltracks' => $viewttracks]);
        }
         return view('dashboard.pages.tracks',compact('all_th_tracks','id'));
         
    }

    

    public function viewTracks(Request $request,$id){
         $track_user_detail = Track::with(['audioFile','participants','release'])
                              ->where('id',$id)->first();                   
         return view('dashboard.pages.track_details',compact('track_user_detail'));
    }

    public function download($id){

    $track = Track::with('audioFile')->findOrFail($id);
    // Example if your audio path is stored in the database
    $filePath = $track->audioFile->path ?? null;

    if (!$filePath) {
        return abort(404, 'File not found');
    }

    // External base URL
    
    $origPath = config('services.external_url.website2');
    $remoteUrl = $origPath.'/storage/'.$filePath;
    
    return redirect()->away($remoteUrl);

   
   }

   public function share($id){
    $track = Track::findOrFail($id);
    return view('dashboard.pages.track_share', compact('track'));
   }
    
}