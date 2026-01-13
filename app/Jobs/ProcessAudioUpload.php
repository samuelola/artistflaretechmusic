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
            Cache::put($this->cacheKey, ['status' => 'error', 'message' => 'Release not found'], 600);
            return;
       }

       $baseUrl = config('app.website_storage_link');
       \Log::info('Uploading audios to: ' . $baseUrl);

       try {
        

           $request = Http::withHeaders([
                'X-APP-A-KEY' => env('APP_A_API_KEY'),
                    ])
                  ->asMultipart();
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

                if (file_exists($fileData['real_path'])) {
                   unlink($fileData['real_path']);
                }
            }

          $response = $request->post($baseUrl . '/api/upload_audios');
          if ($response->failed()) {
            Cache::put($this->cacheKey, [
                'status' => 'error',
                'message' => 'Failed to upload files to storage service'
            ], 600);
            return;
          }

            // if (file_exists($fileData['real_path'])) {
            //     unlink($fileData['real_path']);
            // }

          $files = $response->json()['files'] ?? [];
          $saved = [];
        foreach ($files as $file) {
            $originalName = $file['original_name'];
            $path = $file['path'];
            $url = $file['url'];

            $audio = AudioFile::create([
                'music_release_id' => $release->id,
                'filename' => $originalName,
                'path' => $path,
                'duration_ms' => $this->durations[$originalName] ?? null,
            ]);

            $isrc = $this->generateIsrcForTrack($release);

            $track = Track::create([
                'music_release_id' => $release->id,
                'audio_file_id' => $audio->id,
                'title' => pathinfo($originalName, PATHINFO_FILENAME),
                'duration_ms' => $audio->duration_ms,
                // 'isrc' => $isrc,
                'isrc' => NULL,
            ]);

            $saved[] = [
                'audio_id' => $audio->id,
                'track_id' => $track->id,
                'filename' => $originalName,
                'duration_ms' => $audio->duration_ms,
                //'isrc' => $isrc,
                'isrc' => NULL,
                //'audio_url' => config('app.website_storage_link').$url ,
                'audio_url' => config('app.website_storage_link'). '/storage/' . ltrim($track->audioFile->path, '/') ,
            ];
        }

        Cache::put($this->cacheKey, [
            'status' => 'ok',
            'music_release_id' => $release->id,
            'files' => $saved
        ], 600);

       }
       catch (\Exception $e) {
            \Log::error('Audio upload batch failed: ' . $e->getMessage());
            Cache::put($this->cacheKey, [
                'status' => 'error',
                'message' => $e->getMessage()
            ], 600);
       }
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
