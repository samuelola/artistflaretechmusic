<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface RoleInterface{

    public function createRolee($data);
    public function updateRolee($id,$name);
    public function deleteRolee($id);
    
}