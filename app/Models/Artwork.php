<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Artwork extends Model
{
    protected $guarded = [];
    protected $table ='artworks';

    public function musicrelease() { 
        return $this->belongsTo(MusicRelease::class);
    }
}
