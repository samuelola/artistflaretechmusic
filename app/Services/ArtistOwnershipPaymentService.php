<?php

namespace App\Services;

use App\Models\ArtistOwnershipPayment;



class ArtistOwnershipPaymentService
{
    public function save($artistId, $request)
    {
        return ArtistOwnershipPayment::updateOrCreate(
            ['artist_ownership_identity_id' => $artistId],
            [
                'payout_method' => $request->payout_method,
                'bank_name' => $request->bank_name,
                'account_name' => $request->account_name,
                'account_number' => $request->account_number,
                'country' => $request->country,
                'mobile_number' => $request->mobile_number,
                'other_info' => $request->other_info,
            ]
        );
    }

}