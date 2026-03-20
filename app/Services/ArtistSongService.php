<?php

namespace App\Services;

use App\Models\ArtistOwnerSong;
use Illuminate\Support\Facades\Storage;

class ArtistSongService
{
   
    public function saveSongs($artistId, $request)
    {
        $songs = $request->songs;
        $files = $request->file('files');

        $savedSongs = [];

        foreach ($songs as $index => $song) {

            // SAFETY CHECK (VERY IMPORTANT)
            if(!isset($files[$index])){
                continue;
            }

            $songFile = $files[$index];
            
            if(isset($songFile)){

                 // UPLOAD TO API
                $response = Http::withHeaders([
                    'X-APP-A-KEY' => env('APP_A_API_KEY'),
                ])->attach(
                    'audio_file',
                    file_get_contents($songFile->getRealPath()),
                    $songFile->getClientOriginalName()
                )->post(config('app.website_storage_link')."/api/upload_artist_owner_audio");

                if(!$response->successful()){
                    throw new \Exception("File upload failed for: ".$songFile->getClientOriginalName());
                }

                $dataa = $response->json();
                if(!isset($dataa['path'])){
                    throw new \Exception("Upload response missing path");
                }

                $path = $dataa['path'];

            }

            
            $saved = ArtistOwnerSong::create([
                'artist_ownership_identity_id' => $artistId,
                'title' => $song['title'],
                'artist_name' => $song['artist_name'],
                'release_year' => $song['release_year'],
                'genre' => $song['genre'],
                'duration' => $song['duration'],
                'distribution_status' => $song['distribution_status'],
                'spotify_link' => $song['spotify_link'] ?? null,
                'apple_link' => $song['apple_link'] ?? null,
                'audiomack_link' => $song['audiomack_link'] ?? null,
                'youtube_link' => $song['youtube_link'] ?? null,
                'file_path' => $path,
            ]);

            $savedSongs[] = $saved;
        }

         return $savedSongs;
    }


   


 
}