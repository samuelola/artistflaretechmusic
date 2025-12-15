<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermissionRoute extends Model
{
    protected $table = 'permission_router';

    protected $fillable = [

        'permission_id',
        'router'
    ];

    public function permission(){
        return $this->belongsTo(Permission::class);
    }
}
