<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use DB;

class AnalyticsController extends Controller
{
    public function adminAnalytics(Request $request)
    {
        $topusers = DB::table('users')
        ->select([
            'first_name',
            DB::raw('COUNT(first_name) as name_count'),
        ])          
        ->orderBy('name_count', 'DESC')
        ->groupBy('first_name')
        ->where('first_name', '!=', 'null' )
        ->where('active','Yes')
        // ->where('created_at', '>', now()->subDays(30)->endOfDay())
        ->take(5)
        ->get();

        
        $first_namevalue = [];              
            foreach($topusers as $dd){
                $first_namevalue[] = $dd->first_name;
        }

        $name_countvalue = [];              
            foreach($topusers as $dd){
                $name_countvalue[] = $dd->name_count;
        }


        $toptracks = DB::table('trackdetails')
        ->select([
            'TrackName',
            DB::raw('COUNT(TrackName) as track_count'),
        ])          
        ->orderBy('track_count', 'DESC')
        ->groupBy('TrackName')
        ->where('TrackName', '!=', '' )
        ->take(5)
        ->get();

        $TrackName_val = [];              
            foreach($toptracks as $dd){
                $TrackName_val[] = $dd->TrackName;
        }

        $track_count_val = [];              
            foreach($toptracks as $dd){
                $track_count_val[] = $dd->track_count;
        }

        $toplangs = DB::table('users')
                ->selectRaw(
                    'languages.name,
                    COUNT(users.language) as language_count')
                ->join('languages', 'languages.iso', '=', 'users.language')
                ->groupBy('languages.name')
                ->where('users.language', '!=','')
                ->orderBy('language_count', 'DESC')
                ->take(5)
                ->get();

        $langName_val = [];              
            foreach($toplangs as $dd){
                $langName_val[] = $dd->name;
        }

        $langCount_val = [];              
            foreach($toplangs as $dd){
                $langCount_val[] = $dd->language_count;
        }

        // artist chart and filter

        $get_all_artists = DB::table('users')
                              ->where('first_name', '!=', 'null' )
                              ->where('active','Yes')
                              ->get();

        $get_user_artists = DB::table('trackdetails')->distinct()->pluck('UserName')->toArray();  
        
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
        
        // trackdetails
        
        $theartisttracks = DB::table('trackdetails')
        ->select([
            'UserName',
            DB::raw('COUNT(UserName) as track_count'),
        ])          
        ->groupBy('UserName')
        ->where('UserName', '!=', '' )
        ->take(50)
        ->get();

       return view('dashboard.pages.analytics',compact(
        'first_namevalue',
        'name_countvalue',
        'topusers',
        'toptracks',
        'TrackName_val',
        'track_count_val',
        'toplangs',
        'langName_val',
        'langCount_val',
        'get_all_artists',
        'albumvalue',
        'trackvalue',
        'theyear',
        'theartisttracks',
        'get_user_artists'
       ));
    }

    public function filterArtistInfo(Request $request){
          
        if($request->has('date_filter_data')){
            $artist_data = DB::table('users')
                     ->select([
                       DB::raw('YEAR(join_date)as year'), 
                       DB::raw('SUM(albums) as albums'),
                       DB::raw('SUM(tracks) as tracks')
                     ])
                     ->orderBy('year', 'ASC')
                     ->groupBy('year')
                     ->where('id',$request->date_filter_data)
                     ->where(DB::raw('YEAR(join_date)'), '!=', 'null' )
                     ->get();
            if($artist_data){
                 return response()->json(['artistdata' => $artist_data]);                
            }         
        }
    }

    public function filterArtistTrackInfo(Request $request){
       
         if($request->has('date_filter_data')){
            $artist_data = DB::table('trackdetails')
                     ->select([
                       'TrackName',
                       'ReleaseCount'
                     ])
                     ->where('UserName',$request->date_filter_data)
                     ->get();
            if($artist_data){
                 return response()->json(['artist_track_data' => $artist_data]);                
            }         
         }
    }
}
