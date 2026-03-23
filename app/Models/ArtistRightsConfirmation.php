<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ArtistRightsConfirmation extends Model
{
    use HasFactory;

    protected $table = 'rights_confirmations';
    protected $fillable = [
        'artist_ownership_identity_id',
        'rights1',
        'rights2',
        'rights3',
        'rights4',
        'rights5',
    ];

    public function artist()
    {
        return $this->belongsTo(ArtistOwnerIdentity::class, 'artist_ownership_identity_id');
    }
}
