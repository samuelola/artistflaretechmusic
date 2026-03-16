<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArtistOwnerIdenity extends Model
{
    protected $fillable = [
        'full_name',
        'stage_name',
        'dob',
        'nationality',
        'country',
        'phone',
        'email',
        'youtube',
        'instagram',
        'facebook',
        'tiktok',
        'id_type',
        'government_id_path',
    ];
}
