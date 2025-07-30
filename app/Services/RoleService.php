<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;
use App\Interfaces\RoleInterface;

class RoleService implements RoleInterface 
{

    public function createRolee($data){
        $result_data =  Role::create($data);
        return $result_data;
        
    }

    public function updateRolee($id,$name){
        $result_data =  Role::where('id',$id)->update(['name'=>$name]);
        return $result_data;
        
    }

    public function deleteRolee($id){
         $result_data =  Role::where('id',$id)->delete();
         return $result_data;
    }

    
}