<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\User;
use App\Models\Country;
use App\Models\ArtistOwnerIdentity;
use App\Models\ArtistRoleRight;

class CatalogController extends Controller
{
    public function songUpload(Request $request){
       
        $user = auth()->user();
        $all_countries = DB::table('countries')->get();
        $user = User::where('id',$user->id)->first();
        $artist = ArtistOwnerIdentity::where('user_id', $user->id)->first();
        $user_country = Country::where('iso2', $user->country)->first();
        $step2 = null;

        if($artist){
            $step2 = ArtistRoleRight::where('artist_ownership_identity_id', $artist->id)->first();
        }

        return view('dashboard.pages.monetize_songs.index', compact('all_countries','user','user_country','artist','step2'));
        
    }
}
