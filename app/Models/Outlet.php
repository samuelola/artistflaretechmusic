<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Outlet extends Model
{
    protected $guarded = [];

    protected $casts = ['settings' => 'array'];

    public function musicrelease() { 
        return $this->belongsTo(MusicRelease::class); 
    }

    public function store(){
        return $this->belongsTo(Musicstore::class,'outlet_id'); 
    }
}
