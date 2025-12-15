<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrackParticipant extends Model
{
    protected $guarded = [];
    
    public function track() { 
        return $this->belongsTo(Track::class); 
    }
}
