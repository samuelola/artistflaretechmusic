<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MusicRelease;
use App\Models\Artwork;
use App\Models\AudioFile;
use App\Models\Track;
use App\Models\TrackParticipant;
use App\Models\Outlet;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\SubCount;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Jobs\ProcessAudioUpload;
use App\Jobs\ProcessAudioUpdate;
use App\Jobs\ProcessAudioUpdateMetadata;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use App\Services\YouTubeService;
use App\Models\Verification;



class MusicFormController extends Controller
{

    function getYoutubeVideoId($url){
    preg_match(
        '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i',
        $url,
        $matches
        );

        return $matches[1] ?? null;
    }

    public function youtubeValidation (Request $request,YouTubeService $youtube){
        
         $videoId = $this->getYoutubeVideoId($request->youtube_url);
         if (!$videoId) {
            return response()->json([
                'valid' => false,
                'message' => 'Invalid YouTube URL',
            ]);
        }

        $result = $youtube->validateVideo($videoId);     
        if (!isset($result['valid'])) {
            $result['valid'] = true; // fallback if $youtube->validateVideo returns just details
        }


        return response()->json($result);
    }


    // Show create form (stepper)
    public function showStep()
    {
        $musical_roles = DB::table('musical_roles')->select('name')->get();
        $subscription_limit = DB::table('subscription_limit')->select('the_number')->get();
        $stores = DB::table('music_stores')->select('id','name')->get();
        $subcount = SubCount::with('subscription')
                    ->where(['user_id'=>auth()->user()->id,'status'=>'active'])
                    ->first();
        $r_outlets = DB::table('outlets')->select('status')->first(); 
        $languages = DB::table('languages')->select('name')->get();
        $genres = DB::table('genres')->get(); 
        $getBanks = DB::table('banks')->get();
        $rels = json_decode($getBanks); 
                 
        return view('dashboard.pages.music_form', compact(
            'musical_roles',
            'subscription_limit',
            'stores',
            'subcount',
            'r_outlets',
            'languages',
            'genres',
            'rels'
        ));
    }


    // start verification 

   public function verification(Request $request)
    {
        $request->validate([
            'music_release_id' => 'required|integer',
            'official_id' => 'required',
            'account_number' => 'required',
            'bank' => 'required',
            'account_name' => 'required',
            'social_media_handles' => 'required|array',
            'video_links' => 'required|url'
        ]);

        $release = MusicRelease::findOrFail($request->music_release_id);

        $verification = Verification::where('music_release_id', $release->id)->first();

        // Conditional validation for upload_doc
        if (!$verification || !$verification->id_document) {
            $request->validate([
                'upload_doc' => 'required|file|mimes:jpg,jpeg,png,pdf|max:10240'
            ]);
        } else {
            $request->validate([
                'upload_doc' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240'
            ]);
        }

        //Keep old document by default
        $docPath = $verification?->id_document;

        // Handle new upload
        if ($request->hasFile('upload_doc')) {

            // Delete old document remotely
            if ($verification && $verification->id_document) {
                Http::withHeaders([
                    'X-APP-A-KEY' => env('APP_A_API_KEY'),
                ])->delete(
                    config('app.website_storage_link') . "/api/delete_verification",
                    ['upload_doc' => $verification->id_document]
                );
            }

            $imageFile = $request->file('upload_doc');

            $response = Http::withHeaders([
                'X-APP-A-KEY' => env('APP_A_API_KEY'),
            ])->attach(
                'upload_doc',
                file_get_contents($imageFile->getRealPath()),
                $imageFile->getClientOriginalName()
            )->post(config('app.website_storage_link') . "/api/upload_verification");

            if ($response->failed()) {
                return response()->json(['status' => 'error', 'message' => 'Upload failed'], 500);
            }

            $data = $response->json();
            $docPath = $data['path']; //overwrite only when new upload exists
        }

        $get_bank = DB::table('banks')->where('code', $request->bank)->first();

        //UPDATE OR CREATE (single verification per release)
        $verification = Verification::updateOrCreate(
            ['music_release_id' => $release->id],
            [
                'official_id' => $request->official_id,
                'id_document' => $docPath,
                'account_number' => $request->account_number,
                'bank_code' => $request->bank,
                'bank_name' => $get_bank->name ?? null,
                'account_name' => $request->account_name,
                'social_media_handles' => json_encode($request->social_media_handles),
                'video_link' => $request->video_links,
            ]
        );

        return response()->json([
            'status' => 'ok',
            'music_release_id' => $release->id,
        ]);
    }


    // end verification
    

    // AJAX save step
    public function ajaxSaveStep(Request $request)
    {
       
        $data = $request->only(['music_release_id','step','fields']);
        $fields = $request->input('fields', []);
        $release = $data['music_release_id'] ? MusicRelease::find($data['music_release_id']) : null;

        if (!$release) {
            // Create a new release and auto-generate EAN
            $release = MusicRelease::create([
                'title' => $fields['title'] ?? 'Untitled Release',
                'stereo_code' => $this->generateEANCode(), //generate EAN here
            ]);
            
        }

        if (isset($fields['plan'])) $release->plan = $fields['plan'];
        if (isset($fields['release_type'])) $release->release_type = $fields['release_type'];
        if (isset($fields['title'])) $release->title = $fields['title'];
        if (isset($fields['stereo_type'])) $release->stereo_type = $fields['stereo_type'];
        if (isset($fields['stereo_code'])) $release->stereo_code = $fields['stereo_code'];
        if (isset($fields['label_name'])) $release->label_name = $fields['label_name'];
        if (isset($fields['release_date'])) $release->release_date = $fields['release_date'];

        // only overwrite stereo_code if not already set
        if (empty($release->stereo_code)) {
            $release->stereo_code = $this->generateEANCode();
        }


        $meta = $release->meta ?? [];
        $release->meta = array_merge($meta, $fields);
        $release->user_id = auth()->id();
        if ($release->status !== 'submitted') {
            $release->status = 'draft';
        }
        $release->save();

         return response()->json([
            'status' => 'ok',
            'music_release_id' => $release->id,
            'stereo_code' => $release->stereo_code // send EAN back to JS
        ]);
    }


    

    // Upload audio files
    public function uploadAudio(Request $request){
    $request->validate([
        'music_release_id' => 'nullable|integer',
        'audios.*' => 'required|mimes:mp3,wav,aac,m4a,flac,ogg|max:40960'
    ]);

    $durations = json_decode($request->input('durations','{}'), true) ?: [];

    $release = $request->music_release_id
        ? MusicRelease::find($request->music_release_id)
        : MusicRelease::create(['title' => 'Untitled Release']);    

    if (!$request->hasFile('audios')) {
        return response()->json(['status' => 'error', 'message' => 'No audio files uploaded'], 422);
    }

   
        // Create a unique cache key for polling
        $cacheKey = 'audio_upload_' . Str::uuid();

         // Store uploaded files temporarily
        $files = [];
        foreach ($request->file('audios') as $file) {
        $tempName = Str::uuid() . '.' . $file->getClientOriginalExtension();
        // Store locally so it persists until job runs
        $storedPath = $file->storeAs('temp_audios', $tempName, 'local');
        $absolutePath = Storage::disk('local')->path($storedPath);

        // Log path for debugging
        \Log::info('Stored temp file at: ' . $absolutePath);

        // Verify existence immediately
        if (!file_exists($absolutePath)) {
            \Log::warning('Temp file not found right after storing: ' . $absolutePath);
        }


        $files[] = [
            'original_name' => $file->getClientOriginalName(),
            'real_path' => $absolutePath,
            'extension' => $file->getClientOriginalExtension(),
        ];
       }


        // Dispatch job with paths, not file objects
        dispatch(new ProcessAudioUpload($release->id, $durations, $files, $cacheKey));

        return response()->json([
            'status' => 'processing',
            'cache_key' => $cacheKey,
            'music_release_id' => $release->id,
        ]);
    }

    public function audioUploadStatus($cacheKey){
        $result = Cache::get($cacheKey);
        if (!$result) {
            return response()->json(['status' => 'processing']);
        }

        return response()->json($result);
    }

    

    private function generateIsrcForTrack(MusicRelease $release)
    {
        $country = config('music.isrc_country','US');
        $registrant = config('music.isrc_registrant','XXX');
        $yy = now()->format('y');

        for($i=0;$i<10;$i++){
            $designation = str_pad(random_int(0,99999),5,'0',STR_PAD_LEFT);
            $isrc = strtoupper("{$country}{$registrant}{$yy}{$designation}");
            if(!Track::where('isrc',$isrc)->exists()) return $isrc;
        }

        return strtoupper("{$country}{$registrant}{$yy}".uniqid());
    }

    private function generateEANCode(){
        // You can change prefix (e.g., label/country code)
        $prefix = '890'; 
        $body = '';
        for ($i = 0; $i < 9; $i++) {
            $body .= random_int(0, 9);
        }
        $base = $prefix . $body;

        // Calculate checksum
        $digits = str_split($base);
        $sum = 0;
        foreach ($digits as $i => $n) {
            $sum += $n * ($i % 2 === 0 ? 1 : 3);
        }
        $checksum = (10 - ($sum % 10)) % 10;

        $ean = $base . $checksum;

        // Ensure unique
        if (MusicRelease::where('stereo_code', $ean)->exists()) {
            return $this->generateEANCode();
        }

        return $ean;
    }


    // Save tracks including participants
    public function saveTrackDetails(Request $request){
        
    $payload = $request->validate([
        'music_release_id' => 'required|integer|exists:music_releases,id',
        'tracks' => 'required|array',
        'tracks.*.id' => 'nullable|integer|exists:tracks,id',
        'tracks.*.title' => 'required|string|max:255',
        'tracks.*.artist' => 'required|string|max:255',
        'tracks.*.feature_artist' => 'required|string|max:255',
        'tracks.*.iswc' => 'nullable|string|max:255',
        'tracks.*.instrumental' => 'required',
        'tracks.*.language' => 'required',
        'tracks.*.parental' => 'required',
        'tracks.*.genre' => 'required|array',
        'tracks.*.stream_type' => 'required|array',
        // allow participants as optional array
        'tracks.*.participants' => 'nullable|array',
        'tracks.*.participants.*.participant' => 'nullable|string|max:255',
        'tracks.*.participants.*.roles' => 'nullable|array',
        'tracks.*.participants.*.payout' => 'nullable|string|max:255',
        'tracks.*.track_lyrics' => 'nullable',
        
    ]);

    $release = MusicRelease::findOrFail($payload['music_release_id']);

    foreach ($payload['tracks'] as $t) {
        // Safely handle missing 'id'
        $track = isset($t['id']) && $t['id']
            ? Track::find($t['id'])
            : new Track(['music_release_id' => $release->id]);

        if (!$track) {
            $track = new Track(['music_release_id' => $release->id]);
        }

        $track->fill([
            'title' => $t['title'] ?? $track->title,
            'artist' => $t['artist'] ?? $track->artist,
            'feature_artist' => $t['feature_artist'] ?? $track->feature_artist,
            'iswc' => $t['iswc'] ?? $track->iswc,
            'instrumental' => $t['instrumental'] ?? $track->instrumental,
            'language' => $t['language'] ?? $track->language,
            'parental' => $t['parental'] ?? $track->parental,
            'genre' => isset($t['genre']) ? json_encode($t['genre']) : $track->genre,
            'stream_type' => isset($t['stream_type']) ? json_encode($t['stream_type']) : $track->stream_type,
            'duration_ms' => $t['duration_ms'] ?? $track->duration_ms,
            'track_lyrics' => $t['track_lyrics'] ?? $track->track_lyrics,
        ]);

        $track->save();

       
        $participants = [];
        if (isset($t['participants']) && is_array($t['participants'])) {
            $participants = $t['participants'];
        }

        if (!empty($participants)) {
            $track->participants()->delete();

            foreach ($participants as $p) {
                if (empty($p['participant'])) continue;

                $roles = $p['roles'] ?? [];
                if (!is_array($roles)) {
                    $roles = [$roles];
                }

                $track->participants()->create([
                    'participant' => $p['participant'],
                    'role' => json_encode($roles),
                    'payout' => $p['payout'] ?? '',
                ]);
            }
        }
    }

    return response()->json(['status' => 'ok']);
}


    // Artwork upload
    
   public function uploadArtwork(Request $request)
    {
        $request->validate([
            'music_release_id'=>'required|integer',
            'artwork'=>'required|image|max:10240'
        ]);
        $release = MusicRelease::findOrFail($request->music_release_id);
        $imageFile = $request->file('artwork');
        $img = Image::read($imageFile->getPathname());
        $width = $img->width();
        $height = $img->height();
        if ($width !== $height) {
            return response()->json([
                'status' => 'error',
                'errors' => ['artwork_image' => ['Image must be square (equal width and height).']]
            ], 422);
        }

        if ($width < 1400 || $width > 4000) {
            return response()->json([
                'status' => 'error',
                'errors' => ['artwork_image' => ['Image dimensions must be between 1400x1400 and 4000x4000 pixels.']]
            ], 422);
        }

        if ($request->hasFile('artwork')) {

            // --- Delete old artwork (ask App B) ---
            $old = $release->artworks()->first();

            // $tokken = Session::get('tokken');
             
            if ($old) {
                Http::withHeaders([
                'X-APP-A-KEY' => env('APP_A_API_KEY'),
                    ])
                  ->delete(config('app.website_storage_link')."/api/delete_artwork", [
                  'path' => $old->path
                ]);

                $old?->delete();
            }

            // $response = Http::attach(
            //     'artwork',
            //     file_get_contents($imageFile->getRealPath()),
            //     $imageFile->getClientOriginalName()
            // )->post("http://flarestorage.test/api/upload_artworks");

            $response = Http::withHeaders([
                'X-APP-A-KEY' => env('APP_A_API_KEY'),
                    ])
                        ->attach(
                            'artwork',
                            file_get_contents($imageFile->getRealPath()),
                            $imageFile->getClientOriginalName()
                        )->post(config('app.website_storage_link')."/api/upload_artworks");

                  
            if ($response->failed()) {
                return response()->json(['status' => 'error', 'message' => 'Upload failed'], 500);
            }
            
            $data = $response->json();
            
            // --- Create new record in App A ---

            $art = Artwork::create([
                'music_release_id' => $release->id,
                'path' => $data['path'],
                'mime' => $imageFile->getMimeType(),
            ]);

        }
        

        // --- Return unified response ---
        return response()->json([
            'status' => 'ok',
            'artwork' => [
                'id' => $art->id,
                //'url' => $data['url'],
                'url' => config('app.website_storage_url') . '/' . ltrim($art->path, '/'),
            ]
        ]);
    }


    // Save outlets
    public function saveOutlets(Request $request){

        $data = $request->validate([
            'music_release_id' => 'required|integer|exists:music_releases,id',
            'outlets' => 'required|array',
            'outlets.*.outlet_id' => 'required|integer',
            'outlets.*.outlet_release_date' => 'date',
        ]);

        $release = MusicRelease::findOrFail($data['music_release_id']);

        // Remove existing outlets for this release
        $release->outlets()->delete();

        // Store new outlet associations
        foreach ($data['outlets'] as $outlet) {
            $release->outlets()->create([
                'outlet_id' => $outlet['outlet_id'],
                'outlet_release_date' => $outlet['outlet_release_date'] ?? null,
                'status' => 'uploaded',
            ]);
        }

        return response()->json([
            'status' => 'ok',
            'music_release_id' => $release->id,
            ]
        );
    }

    // Final submission: validates all steps are complete
    
   
    public function submitFinal(Request $request){
    $request->validate([
        'music_release_id' => 'required|integer',
    ]);

    $release = MusicRelease::with([
        'tracks.participants',
        'tracks.audioFile', 
        'artworks',
        'outlets'
    ])->find($request->music_release_id);

    if (!$release) {
        return response()->json([
            'status' => 'error',
            'message' => 'Release not found.'
        ], 404);
    }

    $missing = [];

    // === Basic release info ===
    if (empty($release->title))        $missing[] = 'Title';
    if (empty($release->label_name))   $missing[] = 'Label name';
    if (empty($release->release_date)) $missing[] = 'Release date';
    if ($release->artworks->isEmpty()) $missing[] = 'Artwork';

    // === Tracks ===
    if ($release->tracks->isEmpty()) {
        $missing[] = 'Tracks';
    } else {
        foreach ($release->tracks as $track) {
            if (empty($track->title))       $missing[] = "Track {$track->title} title";
            if (empty($track->duration_ms)) $missing[] = "Track {$track->title} duration";
            if (empty($track->isrc))        $missing[] = "Track {$track->title} ISRC";

            //Check for missing audio file
            if (!$track->audioFile) {
                $missing[] = "Track {$track->id} audio file";
            }

            //Check participants
            if ($track->participants->isEmpty()) {
                $missing[] = "Track {$track->title} participants";
            } else {
                foreach ($track->participants as $p) {
                    if (empty($p->participant)) $missing[] = "Track {$track->title}  participant name";
                    if (empty($p->role))        $missing[] = "Track {$track->id}  participant role";
                    if (empty($p->payout))      $missing[] = "Track {$track->id}  participant payout";
                }
            }
        }
    }

    // === Outlets ===
    if ($release->outlets->isEmpty()) {
        $missing[] = 'Outlets';
    }

    // === Validation failed ===
    if (!empty($missing)) {
        return response()->json([
            'status' => 'error',
            'message' => 'Cannot submit — missing required fields.',
            'missing_fields' => $missing,
        ], 422);
    }

    // === Mark as submitted ===
    $release->status = 'submitted';
    $release->distributed = 'no';
    $release->submitted_at = now();
    $release->save();

    // get user release
    $user_count_release = DB::table('music_releases')->where(['user_id'=>auth()->id(),'status'=>'submitted'
        ])->orderBy('id','desc')->first();

    if($user_count_release){
         $check_stats = DB::table('user_statistics')->where('user_id',auth()->id())->first();    
            if(is_null($check_stats->upload_release)){
                            DB::table('user_statistics')
                                ->where('user_id', auth()->id())
                                ->update(['upload_release'=>10]);
            }else{
                DB::table('user_statistics')
                    ->where('user_id', auth()->id())
                    ->increment('upload_release',10);
        }    
    }    

   

    return response()->json([
        'status' => 'ok',
        'message' => 'Release successfully submitted!',
    ]);
  }



    public function loadDraft(){

    $draft = MusicRelease::where('user_id', auth()->id())
        ->where('status', '!=', 'submitted')
        ->with(['artworks', 'tracks.participants', 'outlets', 'tracks.audioFile','verification'])
        ->latest()
        ->first();

    if (!$draft) {
        return response()->json(['status' => 'no_draft']);
    }

    // Build a safe, serializable response array
    $response = [
        'id' => $draft->id,
        'title' => $draft->title,
        'plan' => $draft->plan,
        'release_type' => $draft->release_type,
        'stereo_type' => $draft->stereo_type,
        'stereo_code' => $draft->stereo_code,
        'label_name' => $draft->label_name,
        'release_date' => $draft->release_date,

        

        // === Verification ===
        'verification' => $draft->verification
            ? [
                'exists' => true,
                'official_id' => $draft->verification->official_id,
                'id_document_url' => $draft->verification->id_document
                    ? config('app.website_storage_url') . '/' . ltrim($draft->verification->id_document, '/')
                    : null,
                'account_number' => $draft->verification->account_number,
                'bank_code' => $draft->verification->bank_code,
                'account_name' => $draft->verification->account_name,
                'social_media_handles' => json_decode($draft->verification->social_media_handles ?? '[]', true),
                'video_link' => $draft->verification->video_link,
            ]
            : [
                'exists' => false,
                'official_id' => null,
                'id_document_url' => null,
                'account_number' => null,
                'bank_code' => null,
                'account_name' => null,
                'social_media_handles' => [],
                'video_link' => null,
            ],


        // === Artworks ===
        'artworks' => $draft->artworks->map(function ($art) {
            return [
                'id' => $art->id,
                'url' => config('app.website_storage_url') . '/' . ltrim($art->path, '/'),
            ];
        }),

        // === Tracks ===
         'tracks' => $draft->tracks->map(function ($track) {
    $audioUrl = null;
    $missing = false;

    // if ($track->audioFile && Storage::exists($track->audioFile->path)) {
    //     $audioUrl = Storage::url($track->audioFile->path);
    // } elseif ($track->audioFile) {
    //     $missing = true;
    // }

    return [
        'id' => $track->id,
        'title' => $track->title,
        'artist' => $track->artist,
        'feature_artist' => $track->feature_artist,
        'duration_ms' => $track->duration_ms,
        'isrc' => $track->isrc,
        'iswc' => $track->iswc,
        'instrumental' => $track->instrumental,
        'language' => $track->language,
        'parental' => $track->parental,
        'lyrics' => $track->track_lyrics ?? '',
        'genre' => json_decode($track->genre ?? '[]', true),
        'for' => json_decode($track->stream_type ?? '[]', true),

        //Audio info with existence check
        'audio_file' => $track->audioFile
            ? [
                'url' => $audioUrl,
                'filename' => basename($track->audioFile->path),
                'missing' => $missing,
            ]
            : null,
        'audio_url' => config('app.website_storage_url') . '/' . ltrim($track->audioFile->path, '/'),
        'participants' => $track->participants->map(function ($p) {
            return [
                'participant' => $p->participant,
                'role' => json_decode($p->role ?? '[]', true),
                'payout' => $p->payout,
            ];
        }),
    ];
}),


            // === Outlets ===
            'outlets' => $draft->outlets->map(function ($o) {
                return [
                    'outlet_id' => $o->outlet_id,
                    'outlet_release_date' => $o->outlet_release_date,
                    'status' => $o->status,
                ];
            }),
        ];

        return response()->json([
            'status' => 'ok',
            'release' => $response,
        ]);
    }


    public function editMusicProductForm($id){
        $release = MusicRelease::with([
            'artworks', 
            'tracks.participants', 
            'outlets',
            'audioFiles',
        ])->findOrFail($id);

          $genres = DB::table('genres')->get();
          $subcount = SubCount::with('subscription')
                    ->where(['user_id'=>auth()->user()->id,'status'=>'active'])
                    ->first();  
          $languages = DB::table('languages')->select('name')->get();
          $subscription_limit = DB::table('subscription_limit')->select('the_number')->get();
          $musical_roles = DB::table('musical_roles')->select('name')->get();
          $stores = DB::table('music_stores')->select('id','name','release_date')->get();
          $getBanks = DB::table('banks')->get();
          $rels = json_decode($getBanks); 
        
        return view('dashboard.pages.edit_music_form', compact(
            'release', 
            'stores',
            'subcount',
            'genres',
            'languages',
            'subscription_limit',
            'musical_roles',
            'getBanks',
            'rels'
        ));
    }

        
    public function loadEditRelease($id)
    {
        try {
            $release = MusicRelease::with([
                'artworks',
                'tracks.audioFile',
                'outlets',
                'verification'
            ])->findOrFail($id);

            // Format response
            $formatted = [
                'id' => $release->id,
                'title' => $release->title ?? '',
                'plan' => $release->plan,
                'release_type' => $release->release_type,
                'stereo_type' => $release->stereo_type,
                'stereo_code' => $release->stereo_code,
                'label_name' => $release->label_name,
                'release_date' => $release->release_date,
                // === Verification ===
        'verification' => $release->verification
            ? [
                'exists' => true,
                'official_id' => $release->verification->official_id,
                'id_document_url' => $release->verification->id_document
                    ? config('app.website_storage_url') . '/' . ltrim($release->verification->id_document, '/')
                    : null,
                'account_number' => $release->verification->account_number,
                'bank_code' => $release->verification->bank_code,
                'account_name' => $release->verification->account_name,
                'social_media_handles' => json_decode($release->verification->social_media_handles ?? '[]', true),
                'video_link' => $release->verification->video_link,
            ]
            : [
                'exists' => false,
                'official_id' => null,
                'id_document_url' => null,
                'account_number' => null,
                'bank_code' => null,
                'account_name' => null,
                'social_media_handles' => [],
                'video_link' => null,
            ],

            ];

            

            /* --------------------------- Artwork section --------------------------- */
            $formatted['artworks'] = $release->artworks->map(function ($art) {
                return [
                    'id' => $art->id ?? '',
                    'path' => $art->path ?? '',
                    'url' => config('app.website_storage_url') . '/' . ltrim($art->path, '/') ?? '',
                ];
            });

            /* ---------------------------- Tracks section --------------------------- */
            $formatted['tracks'] = $release->tracks->map(function ($track) {
                return [
                    'id' => $track->id ?? '',
                    'title' => $track->title ?? '',
                    'filename' => $track->filename ?? '',
                    'duration_ms' => $track->duration_ms ?? '',
                    'isrc' => $track->isrc ?? '',
                    'artist' => $track->artist ?? '',
                    'feature_artist' => $track->feature_artist ?? '',
                    'iswc' => $track->iswc ?? '',
                    'instrumental' => $track->instrumental ?? '',
                    'language' => $track->language ?? '',
                    'parental' => $track->parental ?? '',
                    'lyrics' => $track->track_lyrics ?? '',
                    'for' => $track->stream_type ?? '',
                    'genre' => $track->genre ?? '',
                    'participants' => json_decode($track->participants, true) ?? [],
                    'audio_url' => $track->audioFile ? config('app.website_storage_url') . '/' . ltrim($track->audioFile->path, '/') : null,
                ];
            });

            /* ---------------------------- Outlets section -------------------------- */
            $formatted['outlets'] = $release->outlets->map(function ($outlet) {
                return [
                    'id' => $outlet->id ?? '',
                    'outlet_id' => $outlet->outlet_id ?? '',
                    'outlet_release_date' => $outlet->outlet_release_date ?? '',
                ];
            });

            return response()->json([
                'status' => 'ok',
                'release' => $formatted
            ]);

        } catch (\Exception $e) {
            \Log::error('Error loading edit release: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to load release data.',
            ], 500);
        }
    }


    public function updateBasic(Request $request, $id){

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'plan' => 'nullable|string|max:255',
            'release_type' => 'nullable|string|max:255',
            'stereo_type' => 'nullable|string|max:255',
            'stereo_code' => 'nullable|string|max:255',
            'label_name' => 'nullable|string|max:255',
            'release_date' => 'required|date',
        ]);

        $release = MusicRelease::findOrFail($id);
        $release->update($validated);

        return response()->json(['status' => 'ok', 'message' => 'Basic info updated']);
   }

    public function updateArtwork(Request $request, $id){
        
        $request->validate([
            'artwork' => 'nullable|image|max:5120',
        ]);

        $release = MusicRelease::with('artworks')->findOrFail($id);
        $imageFile = $request->file('artwork');

        if ($request->hasFile('artwork')) {
            // delete old
            $old = $release->artworks()->first();
            if ($old) {
                Http::withHeaders([
                'X-APP-A-KEY' => env('APP_A_API_KEY'),
                    ])
                  ->delete(config('app.website_storage_link')."/api/delete_artwork", [
                  'path' => $old->path
                ]);
            }
            $old?->delete();

            // save new
            $response = Http::withHeaders([
                'X-APP-A-KEY' => env('APP_A_API_KEY'),
                    ])
                  ->attach(
                'artwork',
                file_get_contents($imageFile->getRealPath()),
                $imageFile->getClientOriginalName()
            )->post(config('app.website_storage_link')."/api/upload_artworks");

            if ($response->failed()) {
                return response()->json(['status' => 'error', 'message' => 'Upload failed'], 500);
            }
            
            $data = $response->json();

            $release->artworks()->create([
                'path' => $data['path'],
                'mime'=>$imageFile->getMimeType()
            ]);
        }

        return response()->json(['status' => 'ok', 'message' => 'Artwork updated']);
    }


    public function updateAudio(Request $request, $id)
{
    $release = MusicRelease::findOrFail($id);

    $validator = Validator::make($request->all(), [
        'audios.*' => 'nullable|file|mimes:mp3,wav,aac,m4a,flac,ogg|max:51200',
        'track_ids' => 'nullable|array',
        'track_ids.*' => 'nullable|integer|exists:tracks,id',
        'is_update' => 'nullable',
        'durations' => 'nullable',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 'error',
            'errors' => $validator->errors(),
        ], 422);
    }

    $isUpdate = filter_var($request->input('is_update'), FILTER_VALIDATE_BOOLEAN);

    $audioFiles = $request->file('audios', []);
    $trackIdsInput = $request->input('track_ids', []);
    $trackIds = is_string($trackIdsInput)
    ? json_decode($trackIdsInput, true) ?? []
    : (array) $trackIdsInput;

    // Normalize durations array
    $durationsInput = $request->input('durations', []);
    $durations = is_string($durationsInput)
        ? json_decode($durationsInput, true) ?? []
        : (array) $durationsInput;

    // Generate unique cacheKey for queue response
    $cacheKey = Str::uuid()->toString();
    Cache::put($cacheKey, ['status' => 'pending'], 600);

    // ────────────────────────────────────────────────
    // 1️⃣ METADATA-ONLY UPDATE
    // ────────────────────────────────────────────────
    if (empty($audioFiles) && $isUpdate) {
        dispatch(new ProcessAudioUpdateMetadata(
            $release,
            $trackIds,
            $durations,
            $cacheKey
        ));

        return response()->json([
            'status' => 'ok',
            'message' => 'Updating track metadata...',
            'cacheKey' => $cacheKey
        ]);
    }

    // ────────────────────────────────────────────────
    // 2️⃣ USER TRIED TO UPLOAD NOTHING (INVALID)
    // ────────────────────────────────────────────────
    if (empty($audioFiles)) {
        return response()->json([
            'status' => 'error',
            'message' => 'Please upload at least one audio file.'
        ], 422);
    }

    // ────────────────────────────────────────────────
    // 3️⃣ SAVE TEMP FILES FOR BACKGROUND JOB
    // ────────────────────────────────────────────────
    $tempFiles = [];

    foreach ($audioFiles as $i => $file) {
        $tmpName = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $tmpPath = $file->storeAs('temp_audios', $tmpName, 'local');
        $absolutePath = Storage::disk('local')->path($tmpPath);

        $tempFiles[] = [
            'tmp_path' => $absolutePath,
            'original_name' => $file->getClientOriginalName(),
            'duration_ms' => $durations[$file->getClientOriginalName()] ?? null,
            'track_id' => $trackIds[$i] ?? null,
        ];
    }

    // Dispatch update job
    dispatch(new ProcessAudioUpdate(
        $release,
        $tempFiles,
        $isUpdate,
        $cacheKey
    ));

    return response()->json([
        'status' => 'ok',
        'message' => 'Audio upload is being processed...',
        'cacheKey' => $cacheKey
    ]);
}





    public function checkAudioStatus($cacheKey){
        $data = Cache::get($cacheKey);
        if (!$data) {
        return response()->json([
            'status' => 'pending',
            'message' => 'Processing...'
        ]);
      }

       return response()->json($data);
  }



   

   public function updateTracks(Request $request, $id)
{
    $release = MusicRelease::with('tracks.participants')->findOrFail($id);

    // Validate incoming data
    $validated = $request->validate([
        'tracks' => 'required|array|min:1',
        'tracks.*.track_id' => 'nullable|integer|exists:tracks,id',
        'tracks.*.title' => 'required|string|max:255',
        'tracks.*.artist' => 'required|string|max:255',
        'tracks.*.feature_artist' => 'nullable|string|max:255',
        'tracks.*.iswc' => 'nullable|string|max:255',
        'tracks.*.language' => 'nullable|string|max:255',
        'tracks.*.genre' => 'nullable|array',
        'tracks.*.lyrics' => 'nullable|string',
        'tracks.*.instrumental' => 'required|string|max:10',
        'tracks.*.parental' => 'required',
        'tracks.*.stream_type' => 'nullable|array',
        'tracks.*.duration_ms' => 'nullable',
        'tracks.*.isrc' => 'nullable|string',
        'tracks.*.participants' => 'nullable|array',
        'tracks.*.participants.*.participant' => 'nullable|string',
        'tracks.*.participants.*.roles' => 'nullable|array',
        'tracks.*.participants.*.payout' => 'nullable|numeric',
    ]);

    $errors = [];

    // Validate participants per track
    foreach ($validated['tracks'] as $tIndex => $t) {
        $participants = $t['participants'] ?? [];
        $trackNumber = $tIndex + 1;

        if (empty($participants)) {
            $errors["tracks.{$tIndex}.participants"][] = "Track {$trackNumber}: At least one participant is required.";
            continue;
        }

        $totalPayout = 0;
        foreach ($participants as $pIndex => $p) {
            $name = trim((string) ($p['participant'] ?? ''));
            $roles = $p['roles'] ?? [];
            $payout = $p['payout'] ?? null;

            if ($name === '') {
                $errors["tracks.{$tIndex}.participants.{$pIndex}.participant"][] = "Track {$trackNumber}, Participant " . ($pIndex+1) . ": Name is required.";
            }
            if (!is_array($roles) || count($roles) === 0) {
                $errors["tracks.{$tIndex}.participants.{$pIndex}.roles"][] = "Track {$trackNumber}, Participant " . ($pIndex+1) . ": At least one role is required.";
            }
            if (!is_numeric($payout)) {
                $errors["tracks.{$tIndex}.participants.{$pIndex}.payout"][] = "Track {$trackNumber}, Participant " . ($pIndex+1) . ": Payout must be numeric.";
            } else {
                $totalPayout += (float) $payout;
            }
        }

        $totalPayout = round($totalPayout, 2);
        if ($totalPayout !== 100.00) {
            $errors["tracks.{$tIndex}.participants_total"][] = "Track {$trackNumber}: Participant payouts must sum to 100% (currently {$totalPayout}%).";
        }
    }

    if (!empty($errors)) {
        return response()->json([
            'status' => 'error',
            'errors' => $errors,
        ], 422);
    }

    $saved = [];

    DB::transaction(function () use ($validated, $release, &$saved) {
        foreach ($validated['tracks'] as $t) {
            $track = null;

            // Update existing track if ID is provided
            if (!empty($t['track_id'])) {
                $track = $release->tracks()->where('id', $t['track_id'])->first();
            }

            // Create new track if not found
            if (!$track) {
                $isrc = $t['isrc'] ?? $this->generateIsrcForTrack($release);
                $track = $release->tracks()->create([
                    'title' => $t['title'],
                    'artist' => $t['artist'],
                    'feature_artist' => $t['feature_artist'] ?? '',
                    'instrumental' => $t['instrumental'] ?? '',
                    'parental' => $t['parental'] ?? '',
                    'iswc' => $t['iswc'] ?? '',
                    'language' => $t['language'] ?? '',
                    'genre' => isset($t['genre']) ? json_encode($t['genre']) : json_encode([]),
                    'track_lyrics' => $t['lyrics'] ?? '',
                    'duration_ms' => $t['duration_ms'] ?? null,
                    'isrc' => $isrc,
                    'stream_type' => isset($t['stream_type']) ? json_encode($t['stream_type']) : json_encode([]),
                ]);
            } else {
                $track->update([
                    'title' => $t['title'],
                    'artist' => $t['artist'],
                    'feature_artist' => $t['feature_artist'] ?? $track->feature_artist,
                    'iswc' => $t['iswc'] ?? $track->iswc,
                    'instrumental' => $t['instrumental'] ?? $track->instrumental,
                    'parental' => $t['parental'] ?? $track->parental,
                    'language' => $t['language'] ?? $track->language,
                    'genre' => isset($t['genre']) ? json_encode($t['genre']) : $track->genre,
                    'stream_type' => isset($t['stream_type']) ? json_encode($t['stream_type']) : $track->stream_type,
                    'track_lyrics' => $t['lyrics'] ?? $track->track_lyrics,
                    'duration_ms' => isset($t['duration_ms']) 
                        ? (is_numeric($t['duration_ms']) 
                            ? $t['duration_ms'] 
                            : $this->parseDurationString($t['duration_ms'])) 
                        : $track->duration_ms,
                    'isrc' => $t['isrc'] ?? $track->isrc,
                ]);
            }

            // Participants
            if (array_key_exists('participants', $t)) {
                $track->participants()->delete();

                foreach ($t['participants'] as $p) {
                    if (empty(trim((string)($p['participant'] ?? '')))) continue;

                    $roles = $p['roles'] ?? [];
                    if (!is_array($roles)) $roles = [$roles];

                    $track->participants()->create([
                        'participant' => $p['participant'],
                        'role' => json_encode($roles),
                        'payout' => $p['payout'] ?? 0,
                    ]);
                }
            }

            $saved[] = [
                'track_id' => $track->id,
                'title' => $track->title,
                'artist' => $track->artist,
                'feature_artist' => $track->feature_artist,
                'isrc' => $track->isrc,
                'duration_ms' => $track->duration_ms,
                'lyrics' => $track->track_lyrics,
                'genre' => json_decode($track->genre ?? '[]', true),
                'participants' => $track->participants->map(function ($p) {
                    return [
                        'participant' => $p->participant,
                        'role' => json_decode($p->role ?? '[]', true),
                        'payout' => $p->payout,
                    ];
                })->toArray(),
            ];
        }
    });

    return response()->json([
        'status' => 'ok',
        'message' => 'Tracks updated',
        'tracks' => $saved,
    ]);
}




    public function updateOutlets(Request $request, $id){
        
    $release = MusicRelease::findOrFail($id);

    $validated = $request->validate([
        'outlets' => 'required|array',
        'outlets.*.outlet_id' => 'required|integer',
        'outlets.*.outlet_release_date' => 'required|date',
    ]);

    DB::transaction(function () use ($release, $validated) {
        $release->outlets()->delete();
        foreach ($validated['outlets'] as $outlet) {
            $outlet['status'] = 'uploaded';
            $release->outlets()->create($outlet);
        }
    });

    return response()->json([
        'status' => 'ok',
        'message' => 'Outlets updated successfully!',
        
    ]);
}


    

    public function updateVerification(Request $request, $id)
 {

    $request->validate([
        'official_id' => 'required',
        'account_number' => 'required',
        'bank' => 'required',
        'account_name' => 'required',
        'social_media_handles' => 'required|array',
        'video_links' => 'required|url'
    ]);

     $release = MusicRelease::findOrFail($id);

    // Get existing verification if any
    $verification = Verification::where('music_release_id', $release->id)->first();

    // Conditional validation for file upload
    if (!$verification || !$verification->id_document) {
        $request->validate([
            'upload_doc' => 'required|file|mimes:jpg,jpeg,png,pdf|max:10240'
        ]);
    } else {
        $request->validate([
            'upload_doc' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240'
        ]);
    }

    // Keep old document path by default
    $docPath = $verification?->id_document;

    // Handle new upload
    if ($request->hasFile('upload_doc')) {

        // Delete old document remotely (do NOT delete row)
        if ($verification && $verification->id_document) {
            Http::withHeaders([
                'X-APP-A-KEY' => env('APP_A_API_KEY'),
            ])->delete(
                config('app.website_storage_link') . "/api/delete_verification",
                ['upload_doc' => $verification->id_document]
            );
        }

        $imageFile = $request->file('upload_doc');

        $response = Http::withHeaders([
            'X-APP-A-KEY' => env('APP_A_API_KEY'),
        ])->attach(
            'upload_doc',
            file_get_contents($imageFile->getRealPath()),
            $imageFile->getClientOriginalName()
        )->post(config('app.website_storage_link') . "/api/upload_verification");

        if ($response->failed()) {
            return response()->json(['status' => 'error', 'message' => 'Upload failed'], 500);
        }

        $data = $response->json();
        $docPath = $data['path']; // overwrite only if new file exists
    }

    // Bank info
    $get_bank = DB::table('banks')->where('code', $request->bank)->first();

    //Update existing or create new
    $verification = Verification::updateOrCreate(
        ['music_release_id' => $release->id],
        [
            'official_id' => $request->official_id,
            'id_document' => $docPath,
            'account_number' => $request->account_number,
            'bank_code' => $request->bank,
            'bank_name' => $get_bank->name ?? null,
            'account_name' => $request->account_name,
            'social_media_handles' => json_encode($request->social_media_handles),
            'video_link' => $request->video_links,
        ]
    );

    return response()->json([
        'status' => 'ok',
        'music_release_id' => $release->id,
    ]);
}



   
    public function submitFinalUpdate(Request $request, $id){
    $release = MusicRelease::findOrFail($id);
    $release->update(['status' => 'submitted']);

    return response()->json([
        'status' => 'ok',
        'message' => 'Release submitted for review!',
    ]);
}


protected function parseDurationString($duration)
{
    if (is_numeric($duration)) {
        return (int) $duration;
    }

    $parts = explode(':', $duration);
    $parts = array_reverse($parts);
    $seconds = 0;

    foreach ($parts as $i => $part) {
        $seconds += ((int) $part) * pow(60, $i);
    }

    return $seconds * 1000; // milliseconds
}

   
 public function deleteAudio($trackId){
    $track = Track::with('audioFile', 'participants')->findOrFail($trackId);

    if ($track->audioFile) {
        // Delete physical file
        // if (Storage::disk('public')->exists($track->audioFile->path)) {
        //     Storage::disk('public')->delete($track->audioFile->path);
        // }

        Http::withHeaders([
                'X-APP-A-KEY' => env('APP_A_API_KEY'),
                    ])
                  ->delete(config('app.website_storage_link')."/api/delete_all_audios", [
                'path' => $track->audioFile->path
            ]);

        


        // Delete audio file record
        $track->audioFile->delete();
    }

    // Delete participants
    $track->participants()->delete();

    // Delete the track itself
    $track->delete();

    return response()->json(['status' => 'ok', 'message' => 'Track, audio, and participants deleted successfully']);
}


        public function getTracks($id){
        $release = MusicRelease::with('tracks.participants')->findOrFail($id);
        return response()->json([
            'status' => 'ok',
            'tracks' => $release->tracks
        ]);
    }


    public function clearAllAudios(Request $request){
    $request->validate([
        'music_release_id' => 'required|integer|exists:music_releases,id',
    ]);

    $release = MusicRelease::with('tracks.audioFile', 'tracks.participants')
        ->findOrFail($request->music_release_id);

    DB::transaction(function () use ($release) {
        foreach ($release->tracks as $track) {
            // Delete audio file from storage
            if ($track->audioFile) {
                Http::withHeaders([
                'X-APP-A-KEY' => env('APP_A_API_KEY'),
                    ])
                  ->delete(config('app.website_storage_link')."/api/delete_all_audios", [
                  'path' => $track->audioFile->path
                ]);

            }
            // if ($track->audioFile && Storage::disk('public')->exists($track->audioFile->path)) {
            //     Storage::disk('public')->delete($track->audioFile->path);
            // }

            // Delete audio file record
            if ($track->audioFile) {
                $track->audioFile->delete();
            }

            // Delete participants
            $track->participants()->delete();

            // Delete track record
            $track->delete();
        }
    });

    return response()->json([
        'status' => 'ok',
        'message' => 'All audios and tracks have been cleared successfully.',
    ]);
}


public function deleteAudioTrack(Request $request)
{
    $request->validate([
        'music_release_id' => 'required|integer',
        'track_id' => 'required|integer',
    ]);

    $track = Track::where('music_release_id', $request->music_release_id)
                  ->where('id', $request->track_id)
                  ->first();

    if (!$track) {
        return response()->json([
            'status' => 'error',
            'message' => 'Track not found.'
        ]);
    }

    try {
        // Delete related participants first
        if ($track->participants()->exists()) {
            $track->participants()->delete();
        }

        if ($track->audioFile) {
        // Delete physical file
        // if (Storage::disk('public')->exists($track->audioFile->path)) {
        //     Storage::disk('public')->delete($track->audioFile->path);
        // }

        Http::withHeaders([
                'X-APP-A-KEY' => env('APP_A_API_KEY'),
                    ])->delete(config('app.website_storage_link')."/api/delete_all_audios", [
            'path' => $track->audioFile->path
        ]);

        // Delete audio file record
        $track->audioFile->delete();
        }
        // Delete the track itself
        $track->delete();

        return response()->json(['status' => 'ok', 'message' => 'Track and related participants deleted successfully.']);

    } catch (\Throwable $e) {
        \Log::error('Error deleting track and participants: ' . $e->getMessage());
        return response()->json([
            'status' => 'error',
            'message' => 'Failed to delete track.'
        ], 500);
    }
}




}