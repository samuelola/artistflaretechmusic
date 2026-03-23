<?php

namespace App\Services;

use App\Models\ArtistOwnerSong;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use App\Jobs\UploadArtistSongJob;

class ArtistSongService
{
   
    public function saveSongs($artistId, $request)
    {
        // $songs = $request->songs;
        // $files = $request->file('files');
        
        $songs = array_values($request->songs);
        $files = array_values($request->file('files')); // reindex
        
        foreach ($songs as $index => $song) {

            if(!isset($files[$index])){
                continue;
            }

            $file = $files[$index];

            // SAVE TEMP FILE LOCALLY
            $path = $file->store('temp_songs_ownership', 'public');

            // DISPATCH QUEUE
            UploadArtistSongJob::dispatch($artistId, $song, $path);
        }

        return true;
    }


   


 
}