<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermissionRole extends Model
{
    protected $table = "permission_role";
    protected $fillable = ["role_id","permission_id"];

    public function roles()
    {
        return $this->belongsTo(Role::class);
    }

    public function permissions()
    {
        return $this->belongsTo(Permission::class);
    }
}
