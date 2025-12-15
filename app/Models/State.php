<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    protected $table = 'states';

    public function states(){
        return $this->belongsTo(Country::class);
    }
}
