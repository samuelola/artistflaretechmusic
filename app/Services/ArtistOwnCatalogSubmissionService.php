<?php

namespace App\Services;


use App\Models\ArtistCatalogOwnershipSubmit;
use App\Notifications\NewMessageNotification;


class ArtistOwnCatalogSubmissionService
{
    public function submit($artistId, $request, $artist)
    {
        
        ArtistCatalogOwnershipSubmit::create(
            
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

        
        $recipient = $artist->user;
        $recipient->notify(
            new NewMessageNotification(
                'Catalog Submission & Ownership Verification submission ',
                "Your Catalog Submission & Ownership Verification is successfull a waiting Admin approval"
            )
        );

        return true;
    }

}