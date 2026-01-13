<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class YouTubeService
{
    public function validateVideo(string $videoId): array
    {
        $response = Http::withoutVerifying()
            ->get(
            'https://www.googleapis.com/youtube/v3/videos',
            [
                'part' => 'status,snippet',
                'id'   => $videoId,
                'key'  => config('services.youtube.key'),
            ]
        );

        if ($response->failed()) {
            return [
                'valid' => false,
                'reason' => 'API request failed',
            ];
        }

        $items = $response->json('items');

        if (empty($items)) {
            return [
                'valid' => false,
                'reason' => 'Video does not exist',
            ];
        }

        $video = $items[0];

        if ($video['status']['privacyStatus'] !== 'public') {
            return [
                'valid' => false,
                'reason' => 'Video is not public',
            ];
        }

        return [
            'valid' => true,
            'title' => $video['snippet']['title'],
            'channel' => $video['snippet']['channelTitle'],
        ];
    }
}
