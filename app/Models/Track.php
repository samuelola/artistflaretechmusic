<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Track extends Model
{
    protected $guarded = [];
    protected $casts = [
        'extra' => 'array',
        'genre' => 'array',
        'stream_type' => 'array',
    ];

    public function release() {
         return $this->belongsTo(MusicRelease::class,'music_release_id');
    }
    public function audioFile() { 
        return $this->belongsTo(AudioFile::class,'audio_file_id'); 
    }
    public function participants() { 
        return $this->hasMany(TrackParticipant::class);
    }

}
