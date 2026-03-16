<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class CatalogController extends Controller
{
    public function songUpload(Request $request){
       
        $all_countries = DB::table('countries')->get();
        return view('dashboard.pages.monetize_songs.index', compact('all_countries'));
        
    }
}
