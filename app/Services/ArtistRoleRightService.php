<?php

namespace App\Services;


use App\Models\ArtistRoleRight;


class ArtistRoleRightService
{
    public function saveStep2($artistId, $data)
    {
       

            return ArtistRoleRight::updateOrCreate(
            ['artist_ownership_identity_id' => $artistId],
            [
                'role' => $data['role'],
                'ownership_type' => $data['ownership_type'],
                'ownership_percentage' => $data['ownership_percentage'] ?? null,
                'co_owners' => $data['ownership_type'] === 'co' 
                    ? $data['co_owners'] 
                    : null, 
            ]
        );
    }
}