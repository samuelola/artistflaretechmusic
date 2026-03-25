<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArtistCatalogOwnershipSubmit extends Model
{
    protected $table = 'catalog_ownership_submission';
    protected $fillable = [
        'artist_ownership_identity_id',
        'digital_name',
        'digital_date',
        'status',
        'agree_terms',
        'is_submitted'
    ];

}
