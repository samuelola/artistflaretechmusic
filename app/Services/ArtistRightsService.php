<?php 

namespace App\Services;

use App\Models\ArtistRightsConfirmation;

class ArtistRightsService
{
    public function saveRights($artistId, array $data)
    {
        // Update or create for current artist
        ArtistRightsConfirmation::create(
           
            [
                'artist_ownership_identity_id' => $artistId,
                'rights1' => $data['rights1'],
                'rights2' => $data['rights2'],
                'rights3' => $data['rights3'],
                'rights4' => $data['rights4'],
                'rights5' => $data['rights5'],
            ]
        );
    }
}