<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\MusicRelease;
use App\Models\Track;
use App\Models\AudioFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class ProcessAudioUpdate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $release;
    protected $tmpPath;
    protected $durationMs;
    protected $trackId;
    protected $isUpdate;
    protected $cacheKey;
    protected $originalName;

   public function __construct(MusicRelease $release, string $tmpPath, ?int $durationMs,$originalName, ?int $trackId, bool $isUpdate, string $cacheKey)
    {
        $this->release = $release;
        $this->tmpPath = $tmpPath;
        $this->durationMs = $durationMs;
        $this->trackId = $trackId;
        $this->isUpdate = $isUpdate;
        $this->cacheKey = $cacheKey;
        $this->originalName = $originalName;
    }

    public function handle()
{
    Cache::put($this->cacheKey, ['status' => 'processing'], 600);

    try {
        // Upload to App B
        $apiUrl = config('app.website_storage_link'); // App B endpoint
        \Log::info('Uploading audios to: ' . $apiUrl);
        $response = Http::asMultipart()->attach(
            'audio',
            file_get_contents($this->tmpPath),
            $this->originalName
        )->post($apiUrl);

        if ($response->failed()) {
            throw new \Exception('Failed to upload');
        }

        $files = $response->json()['files'] ?? [];

        $fileData = $response->json(); // original_name, path, url

        //Remove local temp
        @unlink($this->tmpPath);

        //Handle track creation or update in App A
        $track = null;
        $audio = null;
        $release = MusicRelease::findOrFail($this->releaseId);

        //find audio
        $release = MusicRelease::findOrFail($this->releaseId);

        foreach ($files as $file) {

            $originalName = $file['original_name'];
            $path = $file['path'];
            $url = $file['url'];

            if ($this->isUpdate) {
              $audiofilles = AudioFile::where('music_release_id', $release->id)->find($this->trackId);
            }

            if(!$audiofilles){

                $audio = AudioFile::create([
                    'music_release_id' => $release->id,
                    'filename' => $originalName,
                    'path' => $path,
                    'duration_ms' => $this->durations[$originalName] ?? null,
                ]);
            }

        }

        

        


        if ($this->isUpdate && $this->trackId) {
            // Update existing track/audio
            $track = Track::where('music_release_id', $release->id)->find($this->trackId);
        }

        if (!$track) {
            $track = $release->tracks()->create([
                'title' => pathinfo($fileData['original_name'], PATHINFO_FILENAME),
                'duration_ms' => $this->durationMs,
                'isrc' => $this->generateIsrcForTrack($release),
            ]);
        }

        // Save AudioFile record using App B path/url
        $audio = $release->audioFiles()->create([
            'music_release_id' => $release->id,
            'filename' => $fileData['original_name'],
            'path' => $fileData['path'],
            'duration_ms' => $this->durationMs,
        ]);

        

        $track->update(['audio_file_id' => $audio->id]);

        // Cache final result
        Cache::put($this->cacheKey, [
                'status' => 'done',
                'track' => [
                    'track_id' => $track->id ?? null,
                    'filename' => $audio->filename ?? null,
                    'title' => $track->title ?? null,
                    'duration_ms' => $track->duration_ms ?? null,
                    'isrc' => $track->isrc ?? null,
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
                    'audio_url' => Storage::url($audio->path ?? ''),
                ],
                'message' => $this->isUpdate
                    ? 'Track updated successfully'
                    : 'Track uploaded successfully'
            ], 600);

    } catch (\Exception $e) {
        Cache::put($this->cacheKey, [
            'status' => 'failed',
            'message' => $e->getMessage(),
        ], 600);
    }
}


    // Example helper for ISRC generation
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
