<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArtistOwnershipPayment extends Model
{
    protected $table = 'artist_ownership_payment';
    protected $fillable = [
        'artist_ownership_identity_id',
        'payout_method',
        'bank_name',
        'account_name',
        'account_number',
        'country',
        'mobile_number',
        'other_info'
    ];

}
