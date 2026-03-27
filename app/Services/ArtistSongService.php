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
            $songs = $request->songs;
            $files = $request->file('files');

            $createdSongs = [];

            foreach ($songs as $index => $songData) {

            // =========================
            // 1. SAVE FILE TEMPORARILY
            // =========================
            $file = $files[$index];

            $tempPath = $file->store('temp_songs', 'public');

            // =========================
            // 2. CREATE SONG IMMEDIATELY
            // =========================
                $song = ArtistOwnerSong::create([
                    'artist_ownership_identity_id' => $artistId,
                    'title' => $songData['title'],
                    'artist_name' => $songData['artist_name'],
                    'release_year' => $songData['release_year'],
                    'genre' => $songData['genre'],
                    'duration' => $songData['duration'],
                    'distribution_status' => $songData['distribution_status'],
                    'spotify_link' => $songData['spotify_link'] ?? null,
                    'apple_link' => $songData['apple_link'] ?? null,
                    'audiomack_link' => $songData['audiomack_link'] ?? null,
                    'youtube_link' => $songData['youtube_link'] ?? null,

                    // VERY IMPORTANT
                    'file_path' => null, // will be updated later
                    'upload_status' => 'processing'
                ]);

                // =========================
                // 3. DISPATCH QUEUE JOB
                // =========================
                UploadArtistSongJob::dispatch(
                    $song->id,     // pass song ID instead of artistId
                    $tempPath
                );

                $createdSongs[] = $song;
            }


            $songs = ArtistOwnerSong::where('artist_ownership_identity_id', $artistId)
            ->with('contributors')
            ->get();

            return $songs;
    }


   


 
}