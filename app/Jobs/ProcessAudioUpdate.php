<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Models\MusicRelease;
use App\Models\Track;

class ProcessAudioUpdate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected MusicRelease $release;
    protected array $files;     // each: ['tmp_path','original_name','duration_ms','track_id']
    protected bool $isUpdate;
    protected string $cacheKey;

    public function __construct(MusicRelease $release, array $files, bool $isUpdate, string $cacheKey)
    {
        $this->release  = $release;
        $this->files    = $files;
        $this->isUpdate = $isUpdate;
        $this->cacheKey = $cacheKey;
    }

    public function handle()
    {
        Cache::put($this->cacheKey, ['status' => 'processing'], 600);

        \Log::info("JOB STARTED: {$this->cacheKey}");

        try {
            $apiUrl = config('app.website_storage_link') . '/api/upload_audios';

            // Build multipart request
            $request = Http::withHeaders([
                'X-APP-A-KEY' => env('APP_A_API_KEY'),
                    ])
                  ->asMultipart();
            foreach ($this->files as $i => $file) {
                if (!file_exists($file['tmp_path'])) {
                    \Log::warning("Missing temp audio file: {$file['tmp_path']}");
                    continue;
                }

                $request->attach(
                    "audios[{$i}]",
                    file_get_contents($file['tmp_path']),
                    $file['original_name']
                );
            }

            $response = $request->post($apiUrl);

            if ($response->failed()) {
                throw new \Exception("Failed to upload to App-B");
            }

            $responseData = $response->json();
            if (!isset($responseData['files']) || !is_array($responseData['files'])) {
                throw new \Exception("Invalid response from App-B");
            }

            $finalTracks = [];

            foreach ($responseData['files'] as $index => $remoteFile) {

                $meta = $this->files[$index] ?? null;
                if (!$meta) continue;

                $trackId  = $meta['track_id'] ?? null;
                $duration = $meta['duration_ms'] ?? null;

                // cleanup tmp
                if (file_exists($meta['tmp_path'])) {
                    @unlink($meta['tmp_path']);
                }

                // Create audio file record
                $audio = $this->release->audioFiles()->create([
                    'music_release_id' => $this->release->id,
                    'filename'         => $remoteFile['original_name'],
                    'path'             => $remoteFile['path'],
                    'duration_ms'      => $duration,
                ]);

                // Update an existing track if track_id present (update mode)
                $track = null;
                if ($this->isUpdate && $trackId) {
                    $track = Track::where('music_release_id', $this->release->id)
                        ->find($trackId);
                }

                // If no track exists → create new
                if (!$track) {
                    $track = $this->release->tracks()->create([
                        'title'        => pathinfo($remoteFile['original_name'], PATHINFO_FILENAME),
                        'duration_ms'  => $duration,
                        // 'isrc'         => $this->generateIsrcForTrack($this->release),
                        'isrc'         => NULL,
                        'audio_file_id'=> $audio->id,
                    ]);
                } else {
                    // Update existing track
                    $track->update([
                        'duration_ms'   => $duration,
                        'audio_file_id' => $audio->id,
                    ]);
                }

                $finalTracks[] = [
                    'track_id'    => $track->id,
                    'filename'    => $audio->filename,
                    'title'       => $track->title,
                    'duration_ms' => $track->duration_ms,
                    // 'isrc'        => $track->isrc,
                    'isrc'        => NULL,
                    'audio_url'   => config('app.website_storage_link').$remoteFile['url'],  // correct ⬅
                ];
            }

            

            // Write back final result with *all* tracks
            Cache::put($this->cacheKey, [
                'status'  => 'done',
                'tracks'  => $finalTracks,
                'message' => $this->isUpdate
                    ? 'Tracks updated successfully'
                    : 'Tracks uploaded successfully'
            ], 600);

            \Log::info("JOB DONE: {$this->cacheKey}", ['finalTracks' => $finalTracks]);

        } catch (\Exception $e) {
            Cache::put($this->cacheKey, [
                'status'  => 'failed',
                'message' => $e->getMessage(),
            ], 600);

            \Log::error("JOB ERROR: {$this->cacheKey} : " . $e->getMessage());

            \Log::error("ProcessAudioUpdate failed: {$e->getMessage()}");
        }
    }

    protected function generateIsrcForTrack(MusicRelease $release)
    {
        $country    = config('music.isrc_country', 'US');
        $registrant = config('music.isrc_registrant', 'XXX');
        $yy         = now()->format('y');

        for ($i = 0; $i < 15; $i++) {
            $designation = str_pad(random_int(0, 99999), 5, '0', STR_PAD_LEFT);
            $isrc = strtoupper("{$country}{$registrant}{$yy}{$designation}");
            if (!Track::where('isrc', $isrc)->exists()) {
                return $isrc;
            }
        }

        return strtoupper("{$country}{$registrant}{$yy}" . uniqid());
    }
}
