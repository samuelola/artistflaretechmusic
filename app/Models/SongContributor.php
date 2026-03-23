<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SongContributor extends Model
{
    use HasFactory;

    protected $table = 'song_contributors';
    protected $fillable = [
        'artist_owner_song_id',
        'name',
        'role',
        'percentage',
    ];

    // Relationship to song
    public function song()
    {
        return $this->belongsTo(ArtistOwnerSong::class, 'artist_owner_song_id');
    }
}
