<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\Permission;
use App\Interfaces\PermissionInterface;


class PermissionService implements PermissionInterface
{

    public function createPermission($data){
        $result_data =  Permission::create($data);
        return $result_data;
    }

    public function updatePermission($id,$name){
        $result_data =  Permission::where('id',$id)->update(['name'=>$name]);
        return $result_data;
        
    }
    
     public function deletePermission($id){
         $result_data =  Permission::where('id',$id)->delete();
         return $result_data;
     }
    
}


