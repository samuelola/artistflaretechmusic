<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\Release;
use App\Models\SubCount;
use Illuminate\Support\Facades\Validator;

class ReleaseController extends Controller
{
    public function musicProduct(){
        return view('dashboard.pages.music_product');
    }

    public function musicLabels(){
        return view('dashboard.pages.music_labels');
    }

    public function musicArtist(){
        return view('dashboard.pages.music_artists');
    }
    
    public function musicRelease(){
       
        $genres = DB::table('genres')->get();
        $languages = DB::table('languages')->select('name')->get();
        $subscription_limit = DB::table('subscription_limit')->select('the_number')->get();
        $musical_roles = DB::table('musical_roles')->select('name')->get();
        $stores = DB::table('music_stores')->select('id','name')->get();
        $subcount = SubCount::with('subscription')
                    ->where(['user_id'=>auth()->user()->id,'status'=>'active'])
                    ->first();           
        return view('dashboard.pages.music_release',compact(
            'genres','languages','subscription_limit','musical_roles','stores','subcount'));
    }

    public function storeMusicRelease(Request $request){

    $step = $request->step;
    $releaseId = $request->release_id;

    //  Must have release_id (created on page load)
    $release = Release::find($releaseId);
    if (!$release) {
        return response()->json([
            'status' => 'error',
            'message' => 'Release not found'
        ], 404);
    }

    //  Step 1: Basic info
    if ($step == 1) {
        $data = $request->validate([
            'plan' => 'required|string|max:255',
            'release_type' => 'required|string|max:255',
            'release_title' => 'required|string|max:255',
            'stereo_type' => 'required|string|max:255',
            'stereo_code' => 'required|string|max:255',
            'label_name' => 'required|string|max:255',
            'release_date' => 'required|date',
        ]);

        $release->update($data);
    }

    //  Step 2: Artwork upload
    if ($step == 2) {
        $validator = Validator::make($request->all(), [
            'artwork_image' => 'required|file|mimes:jpg,jpeg,png,gif,bmp,tif,tiff|max:10240', // 10MB
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
        }

        if ($request->hasFile('artwork_image')) {
            $filePath = $request->file('artwork_image')->store('artwork', 'public');
            $release->update([
                'artwork_image' => $filePath,
            ]);
        }
    }

    return response()->json([
        'status' => 'success',
        'message' => "Step $step saved successfully!",
        'release_id' => $release->id
    ]);

    }

    public function fetchRegistration(Request $request, $id){
        $release_music = Release::find($id);

        if (!$release_music) {
            return response()->json(['status' => 'error', 'message' => 'Info not found']);
        }

        return response()->json([
            'status' => 'success',
            'data' => $release_music
        ]);
   }

   public function startMusicRelease(){
    $release = Release::create(); // creates empty record
    return response()->json([
        'status' => 'success',
        'release_id' => $release->id
    ]);
   }
    
}
