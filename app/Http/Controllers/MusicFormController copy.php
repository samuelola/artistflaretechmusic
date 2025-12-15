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


class MusicFormController extends Controller
{
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
                 
        return view('dashboard.pages.music_form', compact(
            'musical_roles',
            'subscription_limit',
            'stores',
            'subcount',
            'r_outlets',
            'languages',
            'genres'
        ));
    }

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
    public function uploadAudio(Request $request)
    {
        $request->validate([
            'music_release_id' => 'nullable|integer',
            'audios.*' => 'required|mimes:mp3,wav,aac,m4a,flac,ogg|max:40960'
        ]);

        $durations = json_decode($request->input('durations','{}'), true) ?: [];

        $release = $request->music_release_id 
            ? MusicRelease::find($request->music_release_id)
            : MusicRelease::create(['title' => 'Untitled Release']);

        if (!$request->hasFile('audios')) {
            return response()->json(['status'=>'error','message'=>'No audio files uploaded'],422);
        }

        $saved = [];
        foreach ($request->file('audios') as $file) {

            $filename = $file->getClientOriginalName();
            //$path = $file->store('audios','public');
            $uniqueName = Str::uuid()->toString() . '.' . $filename;

            // Store file using the unique name
            $path = $file->storeAs('audios', $uniqueName, 'public');

            $audio = AudioFile::create([
                'music_release_id' => $release->id,
                'filename' => $filename,
                'path' => $path,
                'duration_ms' => $durations[$filename] ?? null
            ]);

            $isrc = $this->generateIsrcForTrack($release);

            $track = Track::create([
                'music_release_id' => $release->id,
                'audio_file_id' => $audio->id,
                'title' => pathinfo($filename, PATHINFO_FILENAME),
                'duration_ms' => $audio->duration_ms,
                'isrc' => $isrc
            ]);

            $saved[] = [
                'audio_id' => $audio->id,
                'track_id' => $track->id,
                'filename' => $audio->filename,
                'duration_ms' => $audio->duration_ms,
                'isrc' => $isrc,
                'audio_url' => Storage::url($audio->path)
            ];
        }

        return response()->json(['status'=>'ok','music_release_id'=>$release->id,'files'=>$saved]);
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
            //'title' => $t['title'] ?? $track->title,
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

        if ($request->hasFile('artwork')) {

             // delete old
            $old = $release->artworks()->first();
            if ($old && Storage::disk('public')->exists($old->path)) {
                Storage::disk('public')->delete($old->path);
            }
            $old?->delete();

            $path = $imageFile->storeAs('artworks', time().'.'.$imageFile->extension());

            $art = Artwork::create([
                'music_release_id'=>$release->id,
                'path'=>$path,
                'mime'=>$request->file('artwork')->getMimeType()
            ]);
        }
        

        return response()->json(['status'=>'ok','artwork'=>['id'=>$art->id,'url'=>Storage::url($path)]]);
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

        return response()->json(['status' => 'ok']);
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
        ->with(['artworks', 'tracks.participants', 'outlets', 'tracks.audioFile'])
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

        // === Artworks ===
        'artworks' => $draft->artworks->map(function ($art) {
            return [
                'id' => $art->id,
                'url' => Storage::url($art->path),
            ];
        }),

        // === Tracks ===
         'tracks' => $draft->tracks->map(function ($track) {
    $audioUrl = null;
    $missing = false;

    if ($track->audioFile && Storage::exists($track->audioFile->path)) {
        $audioUrl = Storage::url($track->audioFile->path);
    } elseif ($track->audioFile) {
        $missing = true;
    }

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
        'audio_url' => $audioUrl,
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
            'audioFiles'
        ])->findOrFail($id);

          $genres = DB::table('genres')->get();
          $subcount = SubCount::with('subscription')
                    ->where(['user_id'=>auth()->user()->id,'status'=>'active'])
                    ->first();  
          $languages = DB::table('languages')->select('name')->get();
          $subscription_limit = DB::table('subscription_limit')->select('the_number')->get();
          $musical_roles = DB::table('musical_roles')->select('name')->get();
          $stores = DB::table('music_stores')->select('id','name','release_date')->get();
        
        return view('dashboard.pages.edit_music_form', compact(
            'release', 
            'stores',
            'subcount',
            'genres',
            'languages',
            'subscription_limit',
            'musical_roles',
            
        ));
    }

        
    public function loadEditRelease($id)
    {
        try {
            $release = MusicRelease::with([
                'artworks',
                'tracks.audioFile',
                'outlets'
            ])->findOrFail($id);

            // Format response
            $formatted = [
                'id' => $release->id,
                'title' => $release->title,
                'plan' => $release->plan,
                'release_type' => $release->release_type,
                'stereo_type' => $release->stereo_type,
                'stereo_code' => $release->stereo_code,
                'label_name' => $release->label_name,
                'release_date' => $release->release_date,
            ];

            /* --------------------------- Artwork section --------------------------- */
            $formatted['artworks'] = $release->artworks->map(function ($art) {
                return [
                    'id' => $art->id,
                    'path' => $art->path,
                    'url' => Storage::url($art->path),
                ];
            });

            /* ---------------------------- Tracks section --------------------------- */
            $formatted['tracks'] = $release->tracks->map(function ($track) {
                return [
                    'id' => $track->id,
                    'title' => $track->title,
                    'filename' => $track->filename,
                    'duration_ms' => $track->duration_ms,
                    'isrc' => $track->isrc,
                    'artist' => $track->artist,
                    'feature_artist' => $track->feature_artist,
                    'iswc' => $track->iswc,
                    'instrumental' => $track->instrumental,
                    'language' => $track->language,
                    'parental' => $track->parental,
                    'lyrics' => $track->track_lyrics,
                    'for' => $track->stream_type,
                    'genre' => $track->genre,
                    'participants' => json_decode($track->participants, true) ?? [],
                    'audio_url' => $track->audioFile ? Storage::url($track->audioFile->path) : null,
                ];
            });

            /* ---------------------------- Outlets section -------------------------- */
            $formatted['outlets'] = $release->outlets->map(function ($outlet) {
                return [
                    'id' => $outlet->id,
                    'outlet_id' => $outlet->outlet_id,
                    'outlet_release_date' => $outlet->outlet_release_date,
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
            if ($old && Storage::disk('public')->exists($old->path)) {
                Storage::disk('public')->delete($old->path);
            }
            $old?->delete();

            // save new
            $path = $imageFile->storeAs('artworks', time().'.'.$imageFile->extension());
            $release->artworks()->create([
                'path' => $path,
                'mime'=>$request->file('artwork')->getMimeType()
            ]);
        }

        return response()->json(['status' => 'ok', 'message' => 'Artwork updated']);
    }


    public function updateAudio(Request $request, $id){

    $release = MusicRelease::findOrFail($id);

    $validator = Validator::make($request->all(), [
        'audios.*' => 'required|file|mimes:mp3,wav,aac,m4a,flac,ogg|max:51200',
        'track_ids' => 'nullable|array',
        'track_ids.*' => 'nullable|integer|exists:tracks,id',
        'is_update' => 'nullable', // new flag from frontend
       
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 'error',
            'errors' => $validator->errors(),
        ], 422);
    }

    // --- Handle durations gracefully (string or array) ---
    $durationsInput = $request->input('durations', []);
    if (is_string($durationsInput)) {
        $durations = json_decode($durationsInput, true) ?: [];
    } elseif (is_array($durationsInput)) {
        $durations = $durationsInput;
    } else {
        $durations = [];
    }

    $savedTracks = [];
    $isUpdate = filter_var($request->input('is_update'), FILTER_VALIDATE_BOOLEAN);

    DB::transaction(function () use ($request, $release, $durations, &$savedTracks, $isUpdate) {
        $audioFiles = $request->file('audios', []);
        $trackIds = $request->input('track_ids', []);

        foreach ($audioFiles as $i => $file) {
            if (!$file) continue;

            $originalName = $file->getClientOriginalName();
            $durationMs = $durations[$originalName] ?? $durations[$i] ?? null;
            $trackId = $trackIds[$i] ?? null;

            $uniqueName = Str::uuid()->toString() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('audios', $uniqueName, 'public');

            $track = null;
            $audio = null;

            if ($isUpdate && $trackId) {
                // --- CASE 1: Update existing track/audio ---
                $track = Track::where('music_release_id', $release->id)->find($trackId);
                if ($track) {
                    // delete old file if exists
                    if ($track->audioFile && Storage::disk('public')->exists($track->audioFile->path)) {
                        Storage::disk('public')->delete($track->audioFile->path);
                    }

                    // update existing or create new audio file
                    $audio = $track->audioFile;
                    if ($audio) {
                        $audio->update([
                            'filename' => $originalName,
                            'path' => $path,
                            'duration_ms' => $durationMs,
                        ]);
                    } else {
                        $audio = $track->audioFile()->create([
                            'music_release_id' => $release->id,
                            'filename' => $originalName,
                            'path' => $path,
                            'duration_ms' => $durationMs,
                        ]);
                    }

                    $track->update([
                        'duration_ms' => $durationMs,
                        'audio_file_id' => $audio->id,
                    ]);
                }
            } else {
                // --- CASE 2: Prevent duplicate insert ---
                $existingTrack = $release->tracks()
                    ->where('title', pathinfo($originalName, PATHINFO_FILENAME))
                    ->first();

                if ($existingTrack) {
                    // update duration and replace audio
                    $track = $existingTrack;
                    if ($track->audioFile && Storage::disk('public')->exists($track->audioFile->path)) {
                        Storage::disk('public')->delete($track->audioFile->path);
                    }

                    $audio = $track->audioFile;
                    if ($audio) {
                        $audio->update([
                            'filename' => $originalName,
                            'path' => $path,
                            'duration_ms' => $durationMs,
                        ]);
                    } else {
                        $audio = $track->audioFile()->create([
                            'music_release_id' => $release->id,
                            'filename' => $originalName,
                            'path' => $path,
                            'duration_ms' => $durationMs,
                        ]);
                    }

                    $track->update([
                        'duration_ms' => $durationMs,
                        'audio_file_id' => $audio->id,
                    ]);
                } else {
                    // --- CASE 3: Fresh new track ---
                    $audio = $release->audioFiles()->create([
                        'filename' => $originalName,
                        'path' => $path,
                        'duration_ms' => $durationMs,
                    ]);

                    $isrc = $this->generateIsrcForTrack($release);

                    $track = $release->tracks()->create([
                        'title' => pathinfo($originalName, PATHINFO_FILENAME),
                        'duration_ms' => $durationMs,
                        'audio_file_id' => $audio->id,
                        'isrc' => $isrc,
                    ]);
                }
            }

            $savedTracks[] = [
                'track_id' => $track->id,
                'filename' => $audio->filename,
                'title' => $track->title,
                'duration_ms' => $track->duration_ms,
                'isrc' => $track->isrc,
                'artist' => $track->artist ?? '',
                'feature_artist' => $track->feature_artist ?? '',
                'iswc' => $track->iswc ?? '',
                'instrumental' => $track->instrumental ?? '',
                'language' => $track->language ?? '',
                'parental' => $track->parental ?? '',
                'lyrics' => $track->track_lyrics ?? '',
                'for' => json_decode($track->stream_type ?? '[]', true),
                'genre' => json_decode($track->genre ?? '[]', true),
                'participants' => $track->participants->map(function ($p) {
                    return [
                        'participant' => $p->participant,
                        'role' => json_decode($p->role ?? '[]', true),
                        'payout' => $p->payout,
                    ];
                }),
                'audio_url' => Storage::url($audio->path),
            ];
        }
    });

    return response()->json([
        'status' => 'ok',
        'message' => $isUpdate
            ? 'Audio tracks updated successfully.'
            : 'Audio uploaded successfully.',
        'tracks' => $savedTracks,
    ]);
}





   public function updateTracks(Request $request, $id){
    $release = MusicRelease::with('tracks.participants')->findOrFail($id);

    // Validate - track_id can be null (new track), other fields required by your UI
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
        'tracks.*.participants.*.payout' => 'nullable',
    ]);

    $saved = [];

    DB::transaction(function () use ($validated, $release, &$saved) {
        foreach ($validated['tracks'] as $t) {
            $track = null;

            // Try to find an existing track belonging to this release
            if (!empty($t['track_id'])) {
                $track = $release->tracks()->where('id', $t['track_id'])->first();
            }

            // If not found, create a new track linked to this release
            if (!$track) {
                $isrc = $t['isrc'] ?? null;
                if (empty($isrc)) {
                    $isrc = $this->generateIsrcForTrack($release);
                }

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
                    'stream_type' => isset($t['stream_type']) ? json_encode($t['stream_type']) : $track->stream_type,
                ]);
            } else {
                // update existing
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
                    // do not overwrite isrc if already present unless explicitly provided
                    'isrc' => $t['isrc'] ?? $track->isrc,
                ]);
            }

            // Participants: if provided, replace them; if not provided, leave existing participants alone
            if (array_key_exists('participants', $t)) {
                // remove existing participants
                $track->participants()->delete();

                if (is_array($t['participants']) && count($t['participants']) > 0) {
                    foreach ($t['participants'] as $p) {
                        if (empty($p['participant'])) continue;
                        $roles = $p['roles'] ?? [];
                        if (!is_array($roles)) $roles = [$roles];

                        $track->participants()->create([
                            'participant' => $p['participant'],
                            'role' => json_encode($roles),
                            'payout' => $p['payout'] ?? 0,
                        ]);
                    }
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
        if (Storage::disk('public')->exists($track->audioFile->path)) {
            Storage::disk('public')->delete($track->audioFile->path);
        }

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
            if ($track->audioFile && Storage::disk('public')->exists($track->audioFile->path)) {
                Storage::disk('public')->delete($track->audioFile->path);
            }

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

        // Delete the audio file from storage if it exists
        if ($track->audio_file && Storage::exists($track->audio_file)) {
            Storage::delete($track->audio_file);
        }

        // Delete audio file record
        if ($track->audioFile) {
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