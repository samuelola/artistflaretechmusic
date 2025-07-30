<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface PermissionInterface{

    public function createPermission($data);
    public function deletePermission($id);
    public function updatePermission($id,$name);
    
}