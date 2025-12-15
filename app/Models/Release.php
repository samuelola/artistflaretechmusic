<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\FilterByTenant;

class Release extends Model
{
    //use FilterByTenant;

    protected $table = 'releases';
    protected $guarded = [];

    public function user(){

        return $this->belongsTo(User::class);
    }

    // protected $casts = [
    //     'genre' => 'array',
    //     'stream_type' => 'array',
    //     'stores' => 'array',
    // ];
}
