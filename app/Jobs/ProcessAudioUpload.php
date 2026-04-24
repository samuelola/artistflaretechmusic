<?php

namespace App\Jobs;

use App\Models\AudioFile;
use App\Models\MusicRelease;
use App\Models\Track;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class ProcessAudioUpload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $releaseId;
    protected $durations;
    protected $audioFiles;
    protected $cacheKey;

    public function __construct($releaseId, $durations, $audioFiles, $cacheKey)
    {
        $this->releaseId = $releaseId;
        $this->durations = $durations;
        $this->audioFiles = $audioFiles;
        $this->cacheKey = $cacheKey;
    }
    
    

public function handle(): void
{
    $release = MusicRelease::find($this->releaseId);

    if (!$release) {
        Cache::put($this->cacheKey, [
            'status' => 'error',
            'message' => 'Release not found'
        ], 600);
        return;
    }

    $baseUrl = config('app.website_storage_link');

    try {

        $request = Http::withHeaders([
            'X-APP-A-KEY' => env('APP_A_API_KEY'),
        ])
        ->timeout(300) // 5 minutes
        ->connectTimeout(60)
        ->asMultipart();

        // Attach files
        foreach ($this->audioFiles as $i => $fileData) {

            if (!file_exists($fileData['real_path'])) {
                \Log::warning('Missing file: ' . $fileData['real_path']);
                continue;
            }

            $request = $request->attach(
                "audios[{$i}]",
                file_get_contents($fileData['real_path']),
                $fileData['original_name']
            );
        }

        $response = $request->post($baseUrl . '/api/upload_audios');
        

        if ($response->failed()) {
            $this->markAsFailed("Upload failed");
            return;
        }

        $uploadedFiles = $response->json()['files'] ?? [];

        if (empty($uploadedFiles)) {
            \Log::error('No files returned from storage API');
            $this->markAsFailed('No files returned');
            return;
        }

        $saved = [];

        foreach ($uploadedFiles as $i => $file) {

            if (!isset($this->audioFiles[$i])) {
                \Log::warning("Missing local file index: {$i}");
                continue;
            }

            $localFile = $this->audioFiles[$i];

            $audio = AudioFile::find($localFile['audio_id']);
            $track = Track::find($localFile['track_id']);

            if (!$audio || !$track) {
                \Log::warning('Missing DB record', $localFile);
                continue;
            }

            // UPDATE RECORDS
            $audio->update([
                'path' => $file['path'],
                'status' => 'completed'
            ]);

            $track->update([
                'status' => 'completed',
                'audio_file_id' => $audio->id
            ]);

            // DELETE TEMP FILE
            if (file_exists($localFile['real_path'])) {
                unlink($localFile['real_path']);
            }

            $saved[] = [
                'audio_id' => $audio->id,
                'track_id' => $track->id,
                'filename' => $localFile['original_name'], // FIXED
                'duration_ms' => $audio->duration_ms,
                'status' => 'completed',
                'audio_url' => env('R2_PUBLIC_URL') . '/' . ltrim($audio->path, '/'),
            ];
        }

        

        Cache::put($this->cacheKey, [
            'status' => 'ok',
            'music_release_id' => $release->id,
            'files' => $saved
        ], 600);

    } catch (\Exception $e) {

        \Log::error('Upload failed: ' . $e->getMessage());
        $this->markAsFailed($e->getMessage());
    }
}


    protected function markAsFailed($message)
    {
        foreach ($this->audioFiles as $file) {

            AudioFile::where('id', $file['audio_id'])
                ->update(['status' => 'failed']);

            Track::where('id', $file['track_id'])
                ->update(['status' => 'failed']);
        }

        Cache::put($this->cacheKey, [
            'status' => 'error',
            'message' => $message
        ], 600);
    }


    protected function generateIsrcForTrack(MusicRelease $release)
    {
        $country = config('music.isrc_country','US');
        $registrant = config('music.isrc_registrant','XXX');
        $yy = now()->format('y');

        for ($i = 0; $i < 10; $i++) {
            $designation = str_pad(random_int(0, 99999), 5, '0', STR_PAD_LEFT);
            $isrc = strtoupper("{$country}{$registrant}{$yy}{$designation}");
            if (!Track::where('isrc', $isrc)->exists()) return $isrc;
        }

        return strtoupper("{$country}{$registrant}{$yy}" . uniqid());
    }

    
}
