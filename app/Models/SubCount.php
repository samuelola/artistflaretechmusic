<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCount extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table ='sub_count';

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class,'subscription_id');
    }
}


