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

    public $songId;
    public $filePath;

    public function __construct($songId, $filePath)
    {
        $this->songId = $songId;
        $this->filePath = $filePath;
    }

    public function handle()
    {
        try {

           $song = ArtistOwnerSong::find($this->songId);

            if(!$song){
                Log::error("Song not found ID: ".$this->songId);
                return;
            }

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

            // =========================
            // UPDATE EXISTING SONG
            // =========================
            Log::info($song);
            $song->update([
                'file_path' => $data['path'],
                'upload_status' => 'completed'
            ]);

            // DELETE TEMP FILE AFTER SUCCESS
            unlink($fileFullPath);

        } catch (\Exception $e) {
            Log::error("Queue upload error: ".$e->getMessage());
        }
    }
}