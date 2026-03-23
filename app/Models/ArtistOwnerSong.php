<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArtistOwnerSong extends Model
{

    use HasFactory;

    protected $table = 'artist_song';

    protected $fillable = [
        'artist_ownership_identity_id',
        'title',
        'artist_name',
        'release_year',
        'genre',
        'duration',
        'distribution_status',
        'spotify_link',
        'apple_link',
        'audiomack_link',
        'youtube_link',
        'file_path'
    ];

    

    public function artist()
    {
        return $this->belongsTo(ArtistOwnerIdentity::class, 'artist_ownership_identity_id');
    }

    public function contributors()
    {
        return $this->hasMany(SongContributor::class, 'artist_owner_song_id');
    }
}
