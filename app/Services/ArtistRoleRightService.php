<?php

namespace App\Services;


use App\Models\ArtistRoleRight;


class ArtistRoleRightService
{
    public function saveStep2($artistId, $data)
    {
       $role = ArtistRoleRight::updateOrCreate(
            ['artist_ownership_identity_id' => $artistId],
            [
                'role' => $data['role'],
                'ownership_type' => $data['ownership_type'],
                'ownership_percentage' => $data['ownership_percentage'] ?? null,
                'co_owners' => $data['co_owners'] ?? null,
            ]
        );

        return $role;
    }
}