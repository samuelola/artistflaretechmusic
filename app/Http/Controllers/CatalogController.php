<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\User;
use App\Models\Country;
use App\Models\ArtistOwnerIdentity;
use App\Models\ArtistRoleRight;
use App\Models\ArtistOwnerSong;
use App\Models\ArtistRightsConfirmation;
use App\Models\ArtistOwnershipPayment;
use App\Models\ArtistCatalogOwnershipSubmit;


class CatalogController extends Controller
{
    public function songUpload(Request $request){
       
        $user = auth()->user();
        $all_countries = DB::table('countries')->get();
        // $artist = ArtistOwnerIdentity::where('user_id', $user->id)->first();
        
        $user_country = Country::where('iso2', $user->country)->first();
        $banks = DB::table('banks')->get();
        $genres = DB::table('genres')->get();
        $musical_roles = DB::table('musical_roles')->select('name')->get();

        // 1. Check for existing draft
        $artist = ArtistOwnerIdentity::where('user_id', $user->id)
            ->where('catalog_status', 'draft')
            ->latest()
            ->first();
        
         // 2. If none, create new
        if (!$artist) {
            $artist = new ArtistOwnerIdentity();
            $artist->user_id = $user->id;
            $artist->catalog_status = 'draft';
        }    
        $step2 = null;

        if($artist){
            $step2 = ArtistRoleRight::where('artist_ownership_identity_id', $artist->id)->first();
            $songsOwner = ArtistOwnerSong::where('artist_ownership_identity_id', $artist->id)->get();
            $rights = ArtistRightsConfirmation::where('artist_ownership_identity_id', $artist->id)->first();
            $payment = ArtistOwnershipPayment::where('artist_ownership_identity_id', $artist->id)->first();
            $submission = ArtistCatalogOwnershipSubmit::where('artist_ownership_identity_id', $artist->id)->first();
        }

        

        return view('dashboard.pages.monetize_songs.index', compact(
            'all_countries','user','user_country','artist','step2',
            'genres','songsOwner','musical_roles','rights','banks',
            'payment','submission'
        ));
        
    }
}
