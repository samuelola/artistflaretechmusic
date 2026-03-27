<?php

namespace App\Services;


use App\Models\ArtistCatalogOwnershipSubmit;


class ArtistOwnCatalogSubmissionService
{
    public function submit($artistId, $request)
    {
        return ArtistCatalogOwnershipSubmit::create(
            
            [
                'artist_ownership_identity_id' => $artistId,
                'digital_name' => $request->digital_name,
                'digital_date' => $request->digital_date,
                'status' => 'pending',
                'agree_terms' => $request->agree_terms,
                'is_submitted' => true,
                'submitted_at' => NOW()
            ]
        );
    }

}