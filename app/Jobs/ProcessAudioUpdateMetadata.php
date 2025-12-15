<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use App\Models\MusicRelease;
use App\Models\Track;

class ProcessAudioUpdateMetadata implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $release;
    protected $trackIds;
    protected $durations;
    protected $cacheKey;

    public function __construct(MusicRelease $release, array $trackIds, array $durations, string $cacheKey)
    {
        $this->release = $release;
        $this->trackIds = $trackIds;
        $this->durations = $durations;
        $this->cacheKey = $cacheKey;
    }

    public function handle()
    {
        Cache::put($this->cacheKey, ['status' => 'processing'], 600);

        try {
            $resultTracks = [];

            foreach ($this->trackIds as $trackId) {
                $track = Track::with('audioFile')->find($trackId);

                if (!$track || !$track->audioFile) {
                    continue;
                }

                $filename = $track->audioFile->filename;

                // Update duration if frontend provided it
                $newDuration = $this->durations[$trackId]
                    ?? $this->durations[$filename]
                    ?? $track->duration_ms;

                $track->update([
                    'duration_ms' => $newDuration
                ]);

                // Build formatted response track for UI
                $resultTracks[] = [
                    'track_id'    => $track->id,
                    'filename'    => $track->audioFile->filename,
                    'title'       => $track->title,
                    'duration_ms' => $track->duration_ms,
                    'isrc'        => $track->isrc,
                    'audio_url'   => config('app.website_storage_link') . '/storage/' . $track->audioFile->path,
                ];
            }

            Cache::put($this->cacheKey, [
                'status' => 'done',
                'tracks' => $resultTracks,
                'message' => 'Metadata updated successfully'
            ], 600);

        } catch (\Exception $e) {

            Cache::put($this->cacheKey, [
                'status' => 'failed',
                'message' => $e->getMessage()
            ], 600);
        }
    }
}
