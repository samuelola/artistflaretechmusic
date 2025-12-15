<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\Release;
use App\Models\SubCount;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Facades\Storage;
use App\Rules\ValidAudioViaApi;
use App\Models\MusicRelease;

class ReleaseController extends Controller
{
    public function musicProduct(){
        
        $release_products = MusicRelease::with(['user','tracks','artworks'])
        ->withCount('tracks')
        ->where([
            'user_id' => auth()->user()->id,
            'status' => 'submitted',
            // 'distributed' => 'no'
        ])
        ->get();
        return view('dashboard.pages.music_product',compact('release_products'));
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
    
    if ($request->hasFile('artwork_image')) {

        $validator = Validator::make($request->all(), [
        'artwork_image' => 'required|file|mimes:jpg,jpeg,png,gif,bmp,tif,tiff|max:10240',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
        }
        $imageFile = $request->file('artwork_image');
        $img = Image::read($imageFile->getPathname());
        $width = $img->width();
        $height = $img->height();

        // Delete the old image if it exists
        if (!empty($release->artwork_image) && Storage::exists($release->artwork_image)) {
            Storage::delete($release->artwork_image);
        }

        // if ($width !== $height) {
        //     return response()->json([
        //         'status' => 'error',
        //         'errors' => ['artwork_image' => ['Image must be square (equal width and height).']]
        //     ], 422);
        // }

        // if ($width < 1400 || $width > 4000) {
        //     return response()->json([
        //         'status' => 'error',
        //         'errors' => ['artwork_image' => ['Image dimensions must be between 1400x1400 and 4000x4000 pixels.']]
        //     ], 422);
        // }

        $filePath = $imageFile->storeAs('artwork', time().'.'.$imageFile->extension());
        $release->update([
            'artwork_image' => $filePath,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Artwork updated successfully!',
            'release_id' => $release->id,
            'path' => Storage::url($filePath),
        ]);

        
    }elseif ($request->filled('existing_artwork_image')) {
        // Keep the old image if no new one is uploaded
        $release->update([
            'artwork_image' => $request->existing_artwork_image,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Artwork kept as existing image.',
            'release_id' => $release->id,
            'path' => Storage::url($release->artwork_image),
        ]);
    }

    // If nothing at all
    return response()->json([
        'status' => 'error',
        'message' => 'No image provided.',
    ], 400);
}

   //  Step 3: Audio upload
    if($step == 3){
        
        if ($request->hasFile('audioUpload')) {

            // dd($request->file('audioUpload'));
            // dd($request->file('audioUpload')->getMimeType());
            $request->validate([
                'audioUpload' => [
                    'required',
                    'mimes:mp3,wav,ogg,flac,aac,wma,m4a',
                    'max:10240',
                ],
            ]);

            // Delete the old image if it exists
            if (!empty($release->audioUpload) && Storage::exists($release->audioUpload)) {
                Storage::delete($release->audioUpload);
            }


            $audioFile = $request->file('audioUpload');
            $filePath = $audioFile->storeAs('audios', time().'.'.$audioFile->extension());

            $release->update([
            'audioUpload' => $filePath,
            ]);

             return response()->json([
            'status' => 'success',
            'message' => 'Audio uploaded successfully!',
            'release_id' => $release->id,
            'path' => Storage::url($filePath),
           ]);
        }
        elseif ($request->filled('existing_audio')) {
            // Keep the old image if no new one is uploaded
            $release->update([
                'audioUpload' => $request->existing_audio,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Artwork kept as existing image.',
                'release_id' => $release->id,
                'path' => Storage::url($release->audioUpload),
            ]);
        }

        // If nothing at all
        return response()->json([
            'status' => 'error',
            'message' => 'No audio provided.',
        ], 400);

        
    }

    //  Step 4: Track Details upload

    if($step == 4){

          $data = $request->validate([
            'track_details' => 'required',
            'artist' => 'required|string|max:255',
            'genre' => 'required|array', //array
            'featured_artist' => 'required',
            'isrc' => 'required|string|max:255',
            'iswc' => 'nullable|string|max:255',
            'instrumental' => 'required',
            'language'  => 'required',
            'parent_advice' => 'required',
            'stream_type' => 'required|array', //array
            'role' => 'required|array',
            'role.*' => 'array',
            'payout' => 'required|array',
            'participant' => 'required|array'
        ]);

        if (array_sum($data['payout']) !== 100) {
            return response()->json([
                'status' => 'error',
                'message' => 'Total payout must equal 100%'
            ], 422);
        }

        $release->update([
            'track_details' => $data['track_details'],
            'artist' => $data['artist'],
            'genre' => json_encode($data['genre']), //array
            'featured_artist' => $data['featured_artist'],
            'isrc' => $data['isrc'],
            'iswc' => $data['iswc'],
            'instrumental' => $data['instrumental'],
            'language'  => $data['language'],
            'parent_advice' => $data['parent_advice'],
            'stream_type' => json_encode($data['stream_type']), //array
            'role' => json_encode($data['role']), //array
            'payout' => json_encode($data['payout']), // array,
            'participant' => json_encode($data['participant']) //array
        ]);

          
        
    }

    //  Step 5: last step/Stores

    if($step == 5){
        $data = $request->validate([
            'stores' => 'required|array'
        ]);

        $release->update([
            'stores' => json_encode($data['stores']),
            'status' => 'completed',
            'completed_at' => now(),
        ]);
        
    }

     // last step

    // if ($step == 3) {
    // $release->update([
    //     'status' => 'completed',
    //     'completed_at' => now(),
    //     ]);
    // }

        return response()->json([
            'status' => 'success',
            'message' => "Step $step saved successfully!",
            'release_id' => $release->id
        ]);

    }

    public function fetchMusic(Request $request, $id){
        $release_music = Release::find($id);

        if (!$release_music) {
            return response()->json(['status' => 'error', 'message' => 'Info not found']);
        }

        return response()->json([
            'status' => 'success',
            'data' => $release_music
        ]);
   }

   public function startMusicRelease(Request $request){
    $userId = auth()->id(); // assuming releases are tied to a user

    //  Look for unfinished release for this user
    $release = Release::where('user_id', $userId)
                      ->whereNull('completed_at') // or use a "status" column like draft
                      ->latest()
                      ->first();

    if (!$release) {
        //  No unfinished release, create a new one
        $release = Release::create([
            'user_id' => $userId,
            'status' => 'draft', // or leave completed_at null
        ]);
    }

    return response()->json([
        'status' => 'success',
        'release_id' => $release->id
    ]);
   }

    public function editMusicProduct(Request $request,$id){
        
          $release_music = Release::find($id);
          $genres = DB::table('genres')->get();
          $subcount = SubCount::with('subscription')
                    ->where(['user_id'=>auth()->user()->id,'status'=>'active'])
                    ->first();  
          $languages = DB::table('languages')->select('name')->get();
          $subscription_limit = DB::table('subscription_limit')->select('the_number')->get();
          $musical_roles = DB::table('musical_roles')->select('name')->get();
          $stores = DB::table('music_stores')->select('id','name','release_date')->get(); 
          // Fetch participants for this release
       
          $participants = [
            'participant' => json_decode($release_music->participant ?? '[]'),
            'role' => json_decode($release_music->role ?? '[]'),
            'payout' => json_decode($release_music->payout ?? '[]')
          ];
                     
          return view('dashboard.pages.edit_music_release',compact(
            'release_music','subcount','genres','languages','subscription_limit','musical_roles','stores','participants'));
    }

    public function updateMusicRelease(Request $request){

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

        if ($step == 1) {
           $data = [
            'plan' => $request->plan,
            'release_type' => $request->release_type,
            'release_title' => $request->release_title,
            'stereo_type' => $request->stereo_type,
            'stereo_code' => $request->stereo_code,
            'label_name' => $request->label_name,
            'release_date' => $request->release_date,
           ];
           $release->update($data);

        }

        // End of Step 1: Release Details

        //  Step 2: Artwork upload
    if ($step == 2) {

    $validator = Validator::make($request->all(), [
        'artwork_image' => 'mimes:jpg,jpeg,png,gif,bmp,tif,tiff|max:10240',
    ]);

    if ($validator->fails()) {
        return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
    } 
   
    if ($request->hasFile('artwork_image')) {
        $imageFile = $request->file('artwork_image');
        $img = Image::read($imageFile->getPathname());
        $width = $img->width();
        $height = $img->height();

        // if ($width !== $height) {
        //     return response()->json([
        //         'status' => 'error',
        //         'errors' => ['artwork_image' => ['Image must be square (equal width and height).']]
        //     ], 422);
        // }

        // if ($width < 1400 || $width > 4000) {
        //     return response()->json([
        //         'status' => 'error',
        //         'errors' => ['artwork_image' => ['Image dimensions must be between 1400x1400 and 4000x4000 pixels.']]
        //     ], 422);
        // }

                $filePath = $imageFile->storeAs('artwork', time().'.'.$imageFile->extension());
                $release->update([
                    'artwork_image' => $filePath,
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Artwork uploaded successfully!',
                    'release_id' => $release->id,
                    'path' => Storage::url($filePath),
                ]);
            }
        }
         // End of Step 2: Artwork upload

        //  Step 3: Audio upload
    if($step == 3){
        // dd($request->file('audioUpload'));
        // dd($request->file('audioUpload')->getMimeType());
        $request->validate([
            'audioUpload' => [
                'mimes:mp3,wav,ogg,flac,aac,wma,m4a',
                'max:10240',
            ],
        ]);

        if ($request->hasFile('audioUpload')) {

            $audioFile = $request->file('audioUpload');
            $filePath = $audioFile->storeAs('audios', time().'.'.$audioFile->extension());

            $release->update([
            'audioUpload' => $filePath,
            ]);

             return response()->json([
            'status' => 'success',
            'message' => 'Audio uploaded successfully!',
            'release_id' => $release->id,
            'path' => Storage::url($filePath),
           ]);
        }

        
    }
     // End of Step 3: Audio upload


     //  Step 4: Track Details upload

    if($step == 4){

          $data = $request->validate([
            'track_details' => 'required',
            'artist' => 'required|string|max:255',
            'genre' => 'required|array', //array
            'featured_artist' => 'required',
            'isrc' => 'required|string|max:255',
            'iswc' => 'nullable|string|max:255',
            'instrumental' => 'required',
            'language'  => 'required',
            'parent_advice' => 'required',
            'stream_type' => 'required|array', //array
            'role' => 'required|array',
            'role.*' => 'array',
            'payout' => 'required|array',
            'participant' => 'required|array'
        ]);

        if (array_sum($data['payout']) !== 100) {
            return response()->json([
                'status' => 'error',
                'message' => 'Total payout must equal 100%'
            ], 422);
        }

        $release->update([
            'track_details' => $data['track_details'],
            'artist' => $data['artist'],
            'genre' => $data['genre'], //array
            'featured_artist' => $data['featured_artist'],
            'isrc' => $data['isrc'],
            'iswc' => $data['iswc'],
            'instrumental' => $data['instrumental'],
            'language'  => $data['language'],
            'parent_advice' => $data['parent_advice'],
            'stream_type' => $data['stream_type'], //array
            'role' => json_encode($data['role']), //array
            'payout' => json_encode($data['payout']), // array,
            'participant' => json_encode($data['participant']) //array
        ]);
    }

    //  Step 5: last step/Stores

    if($step == 5){
        $data = $request->validate([
            'stores' => 'required|array'
        ]);

        $release->update([
            'stores' => $data['stores'],
            'status' => 'completed',
            'completed_at' => now(),
        ]);
        
    }

     return response()->json([
            'status' => 'success',
            'message' => "Step $step saved successfully!",
            'release_id' => $release->id
     ]);


    }


    
}
