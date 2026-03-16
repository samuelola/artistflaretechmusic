<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function songUpload(Request $request){

        return view('dashboard.pages.monetize_songs.index');
        
    }
}
