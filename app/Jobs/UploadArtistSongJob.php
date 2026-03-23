<?php

namespace App\Jobs;

use App\Models\ArtistOwnerSong;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UploadArtistSongJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $artistId;
    public $song;
    public $filePath;

    public function __construct($artistId, $song, $filePath)
    {
        $this->artistId = $artistId;
        $this->song = $song;
        $this->filePath = $filePath;
    }

    public function handle()
    {
        try {

            $fileFullPath = storage_path('app/public/'.$this->filePath);

            if(!file_exists($fileFullPath)){
                Log::error("File not found: ".$fileFullPath);
                return;
            }

            $response = Http::withHeaders([
                'X-APP-A-KEY' => env('APP_A_API_KEY'),
            ])->attach(
                'audio_file',
                file_get_contents($fileFullPath),
                basename($fileFullPath)
            )->post(config('app.website_storage_link')."/api/upload_artist_owner_audio");

            if(!$response->successful()){
                Log::error("Upload failed: ".$fileFullPath);
                return;
            }

            $data = $response->json();

            if(!isset($data['path'])){
                Log::error("Missing path for ".$fileFullPath);
                return;
            }

            // SAVE TO DB
            ArtistOwnerSong::updateOrCreate(
            [
                'artist_ownership_identity_id' => $this->artistId,
                'title' => $this->song['title'], // unique key per artist
            ],
            [
                'artist_name' => $this->song['artist_name'],
                'release_year' => $this->song['release_year'],
                'genre' => $this->song['genre'],
                'duration' => $this->song['duration'],
                'distribution_status' => $this->song['distribution_status'],
                'spotify_link' => $this->song['spotify_link'] ?? null,
                'apple_link' => $this->song['apple_link'] ?? null,
                'audiomack_link' => $this->song['audiomack_link'] ?? null,
                'youtube_link' => $this->song['youtube_link'] ?? null,
                'file_path' => $data['path'],
            ]
        );

            // DELETE TEMP FILE AFTER SUCCESS
            unlink($fileFullPath);

        } catch (\Exception $e) {
            Log::error("Queue upload error: ".$e->getMessage());
        }
    }
}