<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Permission extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'module_id'
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function modules()
    {
        return $this->belongsTo(Module::class,'module_id');
    }

}
