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

    $saved = [];

    foreach ($this->audioFiles as $fileData) {
        $originalName = $fileData['original_name'];
        $tempPath = $fileData['temp_path'];

        if (!Storage::exists($tempPath)) {
            continue;
        }

        // Move from temp to permanent
        $uniqueName = Str::uuid() . '.' . pathinfo($originalName, PATHINFO_EXTENSION);
        $newPath = Storage::disk('public')->putFileAs('audios', Storage::path($tempPath), $uniqueName);
        
        // OR simpler:
        // $newPath = 'audios/' . $uniqueName;
        // Storage::move($tempPath, $newPath);

        $audio = AudioFile::create([
            'music_release_id' => $release->id,
            'filename' => $originalName,
            'path' => $newPath,
            'duration_ms' => $this->durations[$originalName] ?? null,
        ]);

        //$isrc = app('App\Http\Controllers\MusicFormController')->generateIsrcForTrack($release);
        $isrc = $this->generateIsrcForTrack($release);

        $track = Track::create([
            'music_release_id' => $release->id,
            'audio_file_id' => $audio->id,
            'title' => pathinfo($originalName, PATHINFO_FILENAME),
            'duration_ms' => $audio->duration_ms,
            'isrc' => $isrc,
        ]);

        $saved[] = [
            'audio_id' => $audio->id,
            'track_id' => $track->id,
            'filename' => $audio->filename,
            'duration_ms' => $audio->duration_ms,
            'isrc' => $isrc,
            'audio_url' => Storage::url($audio->path),
        ];

        // Delete temp file
        Storage::delete($tempPath);
    }

    Cache::put($this->cacheKey, [
        'status' => 'ok',
        'music_release_id' => $release->id,
        'files' => $saved
    ],600);
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
