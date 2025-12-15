<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AudioFile extends Model
{
    protected $guarded = [];

    protected $table ='audio_files';

    public function release() { 
        return $this->belongsTo(MusicRelease::class); 
    }
    public function track() { 
        return $this->hasOne(Track::class,'audio_file_id'); 
    }
}
