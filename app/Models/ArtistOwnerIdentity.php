<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArtistOwnerIdentity extends Model
{
    protected $table ="artist_ownership_identity";
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
        'user_id'
    ];

    public function user(){

       return $this->belongsTo(User::class,'user_id');
    }
}
