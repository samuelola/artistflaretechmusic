<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Permission extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'groupbyy'
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'permission_role');
    }

}
