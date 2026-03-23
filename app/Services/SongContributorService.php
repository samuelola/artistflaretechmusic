<?php

namespace App\Services;

use App\Models\SongContributor;

class SongContributorService
{
    /**
     * Save or update contributors for multiple songs
     *
     * @param array $songsData
     * @return array
     */
    public function saveContributors(array $songsData)
    {
        $saved = [];

        foreach($songsData as $songData) {
            $songId = $songData['artist_owner_song_id'] ?? null;
            $contributors = $songData['contributors'] ?? [];

            if(!$songId) continue;

            foreach($contributors as $contrib) {
                // updateOrCreate by song_id + contributor name + role
                $savedContributor = SongContributor::updateOrCreate(
                    [
                        'artist_owner_song_id' => $songId,
                        'name' => $contrib['name'],
                        'role' => $contrib['role'],
                    ],
                    [
                        'percentage' => $contrib['percentage'],
                    ]
                );

                $saved[] = $savedContributor;
            }
        }

        return $saved;
    }
}